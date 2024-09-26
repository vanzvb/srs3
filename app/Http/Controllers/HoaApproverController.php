<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CrmMain;
use App\Models\SrsUser;
use App\Models\SrsRequest;
use App\Models\LogHoaHist;
use Illuminate\Http\Request;
use App\Models\SrsAppointment;
use App\Models\SrsRequestStatus;
use App\Models\SrsRequestsArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Encryption\DecryptException;

class HoaApproverController extends Controller
{
    public function index(Request $request)
    {   
        $this->authorize('accessHoaApproval', SrsRequest::class);

        return view('hoa_approvers.hoa_approvers_index');
    }

    public function list(Request $request)
    {
        $this->authorize('accessHoaApproval', SrsRequest::class);

        if (!$request->ajax()) {
            abort(404);
        }

        $tableName = 'srs_requests';
        $december = Carbon::now()->subYear()->month(12)->startOfMonth();
        
        $srsQuery = SrsRequest::query();

        $requests = $srsQuery->with('stats')
        ->when($request->type == 0, function ($query) {
            return $query->withTrashed();
        })
        ->when($request->type == 1, function ($query) {
            return $query->whereIn('status', [0, 1])
            ->where(function ($query) {
                $query->where('status', '<', 1)
                ->orWhere(function ($query) {
                    $query->where('status', 1)
                    ->whereHas('stats', function ($q) {
                        $q->where('name', 'Approval - Admin');
                    });
                });
            });    
        })
        ->when($request->type == 2, function ($query) {
            return $query->where('status', '<', 2)
            ->where('admin_approved', 0);     
        })
        ->when($request->type == 3, function ($query) {
            return $query->onlyTrashed();
        })
        ->when($request->type == 4, function ($query) {
            return $query->where('status', 4);   
        })
        ->select('request_id', 'first_name', 'last_name', 'status', 'created_at')
        ->whereIn('hoa_id', auth()->user()->hoa->pluck('id')->toArray())
        // ->where('hoa_id', auth()->user()->hoa_id ?? 0)
        ->where('created_at', '>=', $december);

        $datatable = DataTables::of($requests)
        ->filterColumn('requestor', function ($query, $keyword) use ($tableName) {
            $sql = "CONCAT(".$tableName.".first_name,' ',".$tableName.".last_name)  like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        })
        ->filterColumn('created_at', function ($query, $keyword) {
            $query->whereRaw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?", ["%$keyword%"]);
        })
        ->filterColumn('request_id', function ($query, $keyword) {
            $query->whereRaw("request_id LIKE ?", ["%$keyword%"]);
        })
        ->addColumn('request_id', function ($request) {
            return '<a data-id="' . $request->request_id . '" class="view_request" href="/hoa-approvers/' . $request->request_id . '">' . $request->request_id . '</a>';
        })
        ->addColumn('requestor', function ($request) {
            return $request->first_name . ' ' . $request->last_name;
        })
        ->editColumn('created_at', function ($request) {
            return $request->created_at->format('M d, Y h:i A');
        })
        ->editColumn('status', function ($request) {
            if ($request->trashed()) {
                return 'Rejected';
            }

            if ($request->status == 61) {
                return 'Rejected by Admin';
            } else if ($request->status == 62) {
                return 'Rejected by HOA';
            }
            
            return $this->getStatus($request->status, $request->stats->where('name', 'Approval - Admin')->isNotEmpty());
        })
        ->rawColumns(['request_id'])
        ->make(true);

        return $datatable;
    }

    public function getStatus($status, $adminApproved)
    {
        switch ($status) {
            case 0:
                return 'Pending Approval';
                break;
            case 1:
                if ($adminApproved) {
                    return 'Approved by Admin';
                }
                return 'Approved by Enclave President';
                break;
            case 2:
                return 'Approved - Pending Appointment';
                break;
            case 3:
                return 'Appoinment Set';
                break;
            case 4:
                return 'Closed';
                break;
            case 5:
                return 'Archived';
                break;
        }
    }

    private function getCategoryName($categoryId)
    {
        switch ($categoryId) {
            case 1:
                return 'resident';
                break;
            case 2:
                return 'non-resident';
                break;
            case 3:
                return 'commercial';
                break;
        }
    }

    public function show($id)
    {   
        $this->authorize('accessHoaApproval', SrsRequest::class);

        // check if url has archived parameter
        if (request()->segment(3) == 'archived') {
            $year = request()->segment(4);
            $tableName = 'srs_requests_archive_'.$year;

            if (!Schema::hasTable($tableName)) {
                abort(404);
            }

            $srsQuery = SrsRequestsArchive::fromTable($tableName);
        } else {
            $srsQuery = SrsRequest::query();
        }

        $srsRequest = $srsQuery->with(['vehicles', 'appointment', 'appointment.timeslot', 'files.requirement' => function ($q) {
            $q->select('id', 'description');
        }, 'statuses', 'category' => function ($q) {
            $q->select('id', 'name');
        }, 'subCategory' => function ($q) {
            $q->select('id', 'name');
        }, 'hoa' => function ($q) {
            $q->select('id', 'name', 'type');
        }, 'nrHoa' => function ($q) {
            $q->select('id', 'name');
        }, 'customer' => function ($q) {
            $q->select('crm_id', 'customer_id', 'red_tag', 'reason_of_tag');
        }, 'invoice' => function ($q) {
            $q->select('id', 'invoice_no');
        }, 'customer.redTags' => function ($q) {
            $q->whereNull('status')
                ->orWhere('status', 0)
                ->orderBy('date_created', 'desc');
        }])
        ->withTrashed()
        ->findOrFail($id);

        if(!in_array($srsRequest->hoa_id, auth()->user()->hoa->pluck('id')->toArray()) || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
            abort(404);
        }

        // if($srsRequest->hoa_id != auth()->user()->hoa_id || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
        //     abort(404);
        // }
        
        $vehicles = [];
        foreach ($srsRequest->vehicles as $key => $vehicle) {
            $vehicles[] = [
                'key' => $key + 1,
                'req_type' => $vehicle->req_type ? 'Renewal' : 'New',
                'old_sticker_no' => $vehicle->old_sticker_no,
                'type' => $vehicle->type,
                'plate_no' => $vehicle->plate_no . ($vehicle->req_type == 1 && $vehicle->plate_no_remarks ? '<br> <b>[New: '.$vehicle->plate_no_remarks.']</b>' : ''),
                'brand' => $vehicle->brand,
                'series' => $vehicle->series,
                'year_model' => $vehicle->year_model,
                'color' => $vehicle->color . ($vehicle->req_type == 1 && $vehicle->color_remarks ? '<br> <b>[New: '.$vehicle->color_remarks.']</b>' : ''),
                'or' => '<a data-value="/hoa-approvers/srs/uploads/'. $vehicle->or_path .'" data-type="' . (explode('.', $vehicle->req1)[1] == 'pdf' ? 'pdf' : 'img') . '" href="#" class="modal_img">OR</a>',
                'cr' => '<a data-value="' . ($vehicle->cr_from_crm ? 'crm_model/cr/'.$vehicle->cr : '/hoa-approvers/srs/uploads/' . $vehicle->cr_path) . '" data-type="' . ($vehicle->cr ? (explode('.', $vehicle->cr)[1] == 'pdf' ? 'pdf' : 'img') : 'img' ) . '" href="#" class="modal_img">CR</a>'
            ];
        }

        $files = [];
        foreach ($srsRequest->files as $key => $file) {
            $imgType = explode('.', $file->name)[1] == 'pdf' ? 'pdf' : 'img';
            $files[] = '<a data-value="/hoa-approvers/srs/uploads/' . $file->req_path . '" data-type="' . $imgType . '" href="#" class="modal_img">' . $file->requirement->description . '</a>';
        }

        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                ->where('srs_requests.request_id', $srsRequest->request_id);
        }])->get();

        $routes = [
            '<tr>
                <td style="background: #f2f7f9; color: #1e3237;text-align: left;min-width: 150px !important;"><label>Initiated</label></td>
                <td style="background: #f2f7f9; color: #1e3237;min-width: 150px !important;text-align: left;">' . htmlspecialchars($srsRequest->first_name . ' ' . $srsRequest->last_name) . '</td>
                <td style="background: #f2f7f9; color: #1e3237;max-width: 100px !important;">' . $srsRequest->created_at->format('m/d/Y h:i A') . '</td>
                <td style="background: #f2f7f9; color: #1e3237;max-width: 200px !important;text-align: left;"></td>
            </tr>'
        ];

        foreach ($statuses as $status) {
            $row = '<tr>
                        <td style="text-align: left;min-width: 150px !important;"><label>' . $status->name . '</label></td>
                    ';

            if ($status->requests->isNotEmpty()) {
                $row .= '<td style="min-width: 150px !important;text-align: left;">' . $status->requests[0]->pivot->action_by . '</td>
                         <td style="max-width: 100px !important;">' . $status->requests[0]->pivot->created_at->format('m/d/Y h:i A') . '</td>
                        ';

                if ($status->name == 'Appointment Set' && $srsRequest->appointment) {
                    $cell = '<td style="max-width: 200px !important;text-align: left;"> Appointment<br>
                            '. $srsRequest->appointment->date->format('M d, Y') . '<br>
                            ' . $srsRequest->appointment->timeslot->time->format('h:i A');

                    if (auth()->user()->can('reset', SrsAppointment::class) && !$srsRequest->invoice) {
                        $cell .= '<br><a data-value="'.$srsRequest->request_id.'" href="#" id="reset_appt_btn">Reset Appointment</a>';
                    }

                    $cell .= '</td>';

                    $row .= $cell;
                    
                } else {
                    $row .= '<td style="max-width: 200px !important;text-align: left;"></td>';
                }
            } else {
                if ($status->name == "Approval - Enclave President" && (!$srsRequest->hoa || $srsRequest->hoa->type != 0)) {
                    $row .= '<td style="min-width: 150px !important;text-align: left;">N/A</td>
                         <td style="max-width: 100px !important;">N/A</td>
                        ';
                } else {
                    $row .= '<td style="min-width: 150px !important;text-align: left;"></td>
                         <td style="max-width: 100px !important;"></td>
                        ';
                }
                $row .= '<td style="max-width: 200px !important;text-align: left;"></td>';
            }


            $row .= '</tr>';

            $routes[] = $row;
        }

        $adminApproved = $statuses->firstWhere('name', 'Approval - Admin')->requests->isNotEmpty();
        $status = $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved);

        return view('hoa_approvers.hoa_approvers_show', compact('srsRequest', 'vehicles', 'files', 'routes', 'status'));
    }

    public function showFile($id, $date, $name, $hoa, $category)
    {
        $this->authorize('accessHoaApproval', SrsRequest::class);

        $dateTime = Carbon::parse($date);

        $path = storage_path('app/bffhai/' . $dateTime->format('Y') . '/' . $hoa . '/' . $this->getCategoryName($category) . '/' . $dateTime->format('m') . '/' . $name . '/' . $id);

        if (file_exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } else {
            abort(404);
        }
    }

    public function hoaApproved(Request $request)
    {
        $this->authorize('accessHoaApproval', SrsRequest::class);

        $srsRequest = SrsRequest::where('request_id', $request->request_id)
            ->whereDoesntHave('statuses', function ($query) {
                $query->where('status_id', 2);
            })
            ->first();

        if(!in_array($srsRequest->hoa_id, auth()->user()->hoa->pluck('id')->toArray()) ||       $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
            abort(404);
        }

        // if($srsRequest->hoa_id != auth()->user()->hoa_id || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
        //     abort(404);
        // }

        $actionBy = auth()->user()->email;
        $status = 1;

        if ($srsRequest->status == 1) {
            $this->sendRequestApprovedEmail($srsRequest);
            $status = 2;
        }

        $srsRequest->status = $status;
        $srsRequest->save();

        $srsRequest->statuses()->attach(2, ['action_by' => $actionBy]);

        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                ->where('srs_requests.request_id', $srsRequest->request_id);
        }])->get();

        $adminApproved = $statuses->firstWhere('name', 'Approval - Admin')->requests->isNotEmpty();
        $status = $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved);

        LogHoaHist::create([
            'action_by' => auth()->user()->name,
            'action' => 'HOA Approved Request ID: ' . $srsRequest->request_id,
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'status' => 1, 
            'msg' => 'Request Approved!', 
            'status_text' => $status,
            'action_by' => $actionBy,
            'updated_at' => $srsRequest->updated_at->format('m/d/Y h:i A')
        ]);
    }

    public function hoaReject(Request $request, SrsRequest $srsRequest)
    {   
        $this->authorize('accessHoaApproval', SrsRequest::class);

        if (!$request->ajax()) {
            return back();
        }

        if(!in_array($srsRequest->hoa_id, auth()->user()->hoa->pluck('id')->toArray()) || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
            abort(404);
        }

        // if($srsRequest->hoa_id != auth()->user()->hoa_id || $srsRequest->category_id == 2 || $srsRequest->hoa == null) {
        //     abort(404);
        // }

        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        $actionBy = auth()->user()->email;

        $srsRequest->load(['hoa']);
        $requestId = $srsRequest->request_id;
        $email = $srsRequest->email;

        $hoaEmails = [];

        if ($srsRequest->hoa) {
            if ($srsRequest->hoa->emailAdd1) {
                $hoaEmails[] = $srsRequest->hoa->emailAdd1;
            }

            if ($srsRequest->hoa->emailAdd2) {
                $hoaEmails[] = $srsRequest->hoa->emailAdd2;
            }

            if ($srsRequest->hoa->emailAdd3) {
                $hoaEmails[] = $srsRequest->hoa->emailAdd3;
            }
        }

        $srsRequest->status = 62;
        $srsRequest->reject_role = 10;
        $srsRequest->rejected_by = $actionBy;
        $srsRequest->reject_reason = $data['reason'];
        $srsRequest->save();

        $srsRequest->delete();

        dispatch(new \App\Jobs\SendRejectedNotificationJob($srsRequest, $srsRequest->email, $data['reason'], $hoaEmails));

        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                ->where('srs_requests.request_id', $srsRequest->request_id);
        }])->get();

        $adminApproved = $statuses->firstWhere('name', 'Approval - Admin')->requests->isNotEmpty();
        $status = $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved);

        LogHoaHist::create([
            'action_by' => auth()->user()->name,
            'action' => 'HOA Rejected Request ID: ' . $srsRequest->request_id . ' Reason: ' . $data['reason'],
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'status' => 1, 
            'msg' => 'Request Rejected!', 
            'status_text' => $status,
            'action_by' => $actionBy,
            'updated_at' => $srsRequest->updated_at->format('m/d/Y h:i A'),
            'reject_reason' => $data['reason']
        ]);
    }

    private function sendRequestApprovedEmail($srsRequest)
    {
        $srn = Crypt::encrypt($srsRequest->request_id);
        $url = URL::temporarySignedRoute('request.appointment', now()->addDays(3), ['key' => $srn]);

        // Mail::to($srsRequest->email)->send(new RequestApproved($srsRequest, $url));
        dispatch(new \App\Jobs\SendApprovedNotificationJob($srsRequest, $srsRequest->email, $url));
    }
}
