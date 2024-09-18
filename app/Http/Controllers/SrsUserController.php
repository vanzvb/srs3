<?php

namespace App\Http\Controllers;

use App\Models\SrsHoa;
use App\Models\SrsRole;
use App\Models\SrsUser;
use App\Models\SrsRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SrsUserController extends Controller
{
    public function index()
    {
        $this->authorize('create', SrsUser::class);

        // $this->authorize('viewAny', SrsUser::class);

        // $users = SrsUser::where('role_id', '!=', 4)
        //                 ->where('id', '!=', Auth::id())
        //                 ->paginate(15);

        // return view('srs.admin.users', compact('users'));

        $hoas = SrsHoa::select('id', 'name')->get();

        $roles = SrsRole::select('id', 'name')->get();

        $logged_in_users = SrsUser::where('is_logged_in', 1)->count();

        return view('srs.admin.users', compact('roles', 'hoas', 'logged_in_users'));
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email' => [
                        'required', 
                        'string',
                        Rule::notIn(['bvp.moreno@bffhai.com', 'o.batistil@bffhai.com', 'ferdie.bonifacio@bffhai.com', 'diramos@bffhai.com', 'glomorales@bffhai.com', 'bffhai_ood@bffhai.com']),
                       ],
            'password' => 'required|string',
        ]);

        if (Auth::validate([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
    
            $user = SrsUser::where('email', $data['email'])->first();

            if ($user->status != 1) {
                // dd('zxc');
                return back()->withErrors(['msg' => 'Account does not have a valid license.']);
            }
        }
        
        if (Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => 1,
        ])) {
            $request->session()->regenerate();
           
            return redirect()->intended('dashboard');
        } else {
            return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        $user = SrsUser::find(auth()->id());
        $user->update([
            'is_logged_in' => 0,
            'login_at' => null,
        ]);

        Cache::forget('user-is-online-' . auth()->id());

        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }

    public function create()
    {
        $this->authorize('create', SrsUser::class);

        return view('auth.register');
    }

    public function store(Request $request)
    {   
        $this->authorize('create', SrsUser::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:srs_users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|exists:srs_roles,id',
            'hoa.*' => 'required_if:role,7|integer|exists:srs_hoas,id',
        ], [
            'hoa.required_if' => 'The :attribute field is required for presidents.',
        ], [
            'name' => 'ame',
            'email' => 'email',
            'role' => 'role',
            'hoa' => 'hoa',
        ]);
 
        $user = new SrsUser();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role'];
        $user->password = Hash::make($data['password']);
        $user->status = 1;

        if ($user->save()) {
            if($data['role'] == 7 && isset($data['hoa'])) {
                // attach hoa to user
                $user->hoa()->attach($data['hoa']);
            }

            return back()->with('userAddSuccess', 'User Added Successfully!');
        }
    }

    public function show(SrsUser $user)
    {
        // $this->authorize('viewAny', SrsUser::class);

        $this->authorize('create', SrsUser::class);

        $data = [
            'user'  => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role_id,
            'hoa'   => $user->hoa()->pluck('hoa_id')->toArray(),
            'is_logged_in' => $user->is_logged_in,
        ];

        return response()->json(['user' => $data]);
    }

    public function update(Request $request, SrsUser $user)
    {
        $this->authorize('update', SrsUser::class);

        // dd($request->all());

        if(auth()->user()->role_id == 4) {
            $user_role = 'required|integer|exists:srs_roles,id';
        } else {
            $request['user_role'] = $user->role_id;
            $user_role = 'nullable|integer|exists:srs_roles,id';
        }

        $data = $request->validate([
            'user_name'  => 'required|string',
            'user_email' => 'required|string|unique:srs_users,email,'.$user->id,
            'user_role' => $user_role,
            'user_hoa.*'  => 'nullable|required_if:user_role,7|integer|exists:srs_hoas,id',
            'password' => 'nullable|string|min:8|confirmed',
            'is_online_status_value' => 'nullable|integer|in:0,1',
        ], [
            'user_hoa.required_if' => 'The :attribute field is required for presidents.',
        ], [
            'user_name'  => 'ame',
            'user_email' => 'email',
            'user_role'  => 'role',
            'user_hoa'   => 'hoa',
        ]);
        
        $user->name = $data['user_name'];
        $user->email = $data['user_email'];
        $user->role_id = $data['user_role'];

        if($data['password'] != '' || $data['password'] != null) {
            $user->password = Hash::make($data['password']);
        }
   
        if(isset($data['user_hoa'])) {
            // attach hoa to user
            $user->hoa()->sync($data['user_hoa']);
        } else {
            $user->hoa()->detach();
        }

        if(isset($data['is_online_status_value']) && $data['is_online_status_value'] == 0) {
            $user->is_logged_in = 0;
        }

        $user->save();

        return back()->with('userEditSuccess', 'User Edited Successfully!');
    }

    public function getUsers(Request $request)
    {
        $login_status = $request->login_status;
        $role_filter = $request->role_filter;

        $users = SrsUser::with(['userRole', 'hoa'])
        ->when(auth()->user()->role_id != 4, function($query) {
            return $query->where('role_id', '!=', 4);
        })
        ->when($role_filter, function($query, $role_filter) {
            if($role_filter != '0') {
                return $query->where('role_id', $role_filter);
            }
        })
        ->when($login_status, function($query, $login_status) {
            if($login_status == 'online') {
                return $query->where('is_logged_in', 1);
            } 

            if($login_status == 'offline') {
                return $query->where('is_logged_in', 0);
            }

            return $query;
        });

        return DataTables::of($users)
        ->filterColumn('hoa', function($query, $keyword) {
            $query->whereHas('hoa', function($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%");
            });
        })
        ->editColumn('is_logged_in', function ($user) {
            return '<div class="form-check form-switch d-flex justify-content-center">
                    <input type="checkbox" role="switch" class="form-check-input toggle-login" data-id="'.$user->id.'" '.($user->is_logged_in == 1 ? 'checked' : '').'>
                </div>';

            // return $user->is_logged_in == 1 ? '<span class="badge bg-success">Online</span>' : '';
        })
        ->editColumn('login_at', function ($user) {
            return $user->login_at ? $user->login_at->format('M d, Y h:i A') : '';
        })
        ->editColumn('hoa', function ($user) {
            // check if count is greater than 0
            $html = '<ul class="list-unstyled">';

            if($user->hoa->count() > 0) {
                foreach($user->hoa as $hoa) {
                    $html .= '<li class="list-group-item">'.$hoa->name.'</li>';
                }
            } else {
                $html .= '<li class="list-group-item"></li>';
            }

            $html .= '</ul>';

            return $html;
        })
        ->addColumn('userRole', function ($user) {
            return $user->userRole->name;
        })
        ->addColumn('action', function ($user) {
            return '<a data-value="'.$user->id.'" class="btn btn-warning btn-sm edit_user" href="#">
                        <i class="bi bi-pencil-square"></i>
                    </a>';
        })
        ->order(function ($query) {
            if (request()->has('hoa')) {
                $query->orderBy('hoa', request('hoa'));
            }
        })
        ->rawColumns(['action', 'is_logged_in', 'hoa'])
        ->make(true);
    }

    public function resetAll(Request $request)
    {
        $this->authorize('update', SrsUser::class);

        $allowedUsers = [
            'itqa@atomitsoln.com',
            'lito.tampis@atomitsoln.con',
            'srsadmin@atomitsoln.com',
        ];

        if(!in_array(auth()->user()->email, $allowedUsers)) {
            return response()->json(['error' => 'You are not allowed to reset all users.'], 403);
        }

        $users = SrsUser::where('role_id', '!=', 4)->get();

        $users->each(function($user) {
            $user->update([
                'is_logged_in' => 0,
                'login_at' => null,
            ]);
        });

        return response()->json(['success' => 'All users are now offline.']);
    }

    public function toggleLogin(Request $request)
    {
        $id = $request->user_id;

        try {
            DB::transaction(function () use ($id) {
                $user = SrsUser::findOrfail($id);

                $user->update([
                    'is_logged_in' => !$user->is_logged_in,
                    'login_at' => !$user->is_logged_in ? now() : null,
                ]);

                if($user->is_logged_in == 1) {
                    $expiresAt = now()->addMinutes(5);
                    Cache::put('user-is-online-' . $user->id , true, $expiresAt);
                } else {
                    Cache::forget('user-is-online-' . $user->id);
                }
            });

            $logged_in_users = SrsUser::where('is_logged_in', 1)->count();

            return response()->json(['success' => 'User status updated.', 'logged_in_users' => $logged_in_users]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
}
