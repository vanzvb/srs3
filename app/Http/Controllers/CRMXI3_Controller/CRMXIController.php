<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;

use App\Models\CrmMain;
use App\Models\CRMXI3_Model\CRMXIMain;
use App\Models\SrsHoa;
use App\Models\LogCrmHist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LogVehicleHist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Gate;

class CRMXIController extends Controller
{
    public function index()
    {   
        // If current user has not access permission
        // if(!Gate::allows('access_crmx_i_permission')) {
        //     abort(403);
        // }

        $hoas = DB::select('SELECT * FROM crmxi3_hoas');
        $cities = DB::select('SELECT *FROM crmx_bl_city WHERE `status` = 1');

        //$categories = DB::select('select * from srs_categories');

        // $categories = DB::select('select * from spc_categories');
        $categories = DB::select('select * from crmxi3_categories');
        $civil_status = DB::select('select * from crmxi3_civil_status');
        $nationalities = DB::select('select * from crmxi3_nationalities');
        // return view('crmxi3.crmxi', ['crms' => $crms, 'hoas' => $hoas, 'cities' => $cities,  'categories' => $categories]);
        $subcats = DB::select('SELECT * FROM get_subcat');
        $hoatypes = DB::select('SELECT * FROM get_hoa_types');
        $vehicle_ownership_status = DB::select('SELECT * FROM get_vehicle_ownership_status');

        return view('crmxi3.crmxi', 
        [
            // 'crms' => $crms,
            'categories' => $categories,
            'hoas' => $hoas,
            'civil_status' => $civil_status,
            'nationalities' => $nationalities,
            'cities' => $cities,
            'subcats' => $subcats,
            'hoatypes' => $hoatypes,
            'vehicle_ownership_status' => $vehicle_ownership_status
        ]);
    }

    // public function getCRMs(Request $request)
    // {
    //         $crms = CRMXIMain::with(['vehicles' => function ($query){
    //             $query->select('account_id','plate_no','old_sticker_no','new_sticker_no');
    //         }])
    //         ->with(['cities' => function($query){
    //             $query->select('bl_id','description');
    //         }])
    //         ->with(['hoas' => function($query){
    //             $query->select('id','name');
    //         }])
    //         ->leftJoin('crmx_bl_city','crmx_bl_city.bl_id','crmxi3_mains.city')
    //         ->leftJoin('crmxi3_hoas','crmxi3_hoas.id','crmxi3_mains.hoa')
    //         ->select(
    //             'crmxi3_mains.crm_id',
    //             'crmxi3_mains.account_id',
    //             'crmxi3_mains.account_type',
    //             'crmxi3_mains.name',
    //             'crmxi3_mains.firstname',
    //             'crmxi3_mains.middlename',
    //             'crmxi3_mains.lastname',
    //             'crmxi3_mains.address',
    //             'crmxi3_mains.blk_lot',
    //             'crmxi3_mains.block',
    //             'crmxi3_mains.lot',
    //             'crmxi3_mains.house_number',
    //             'crmxi3_mains.street',
    //             'crmxi3_mains.building_name',
    //             'crmxi3_mains.subdivision_village',
    //             'crmxi3_mains.hoa',
    //             'crmxi3_mains.city',
    //             'crmxi3_mains.civil_status',
    //             'crmxi3_mains.nationality',
    //             'crmxi3_mains.zipcode',
    //             'crmxi3_mains.status',
    //             'crmxi3_mains.category_id',
    //             'crmxi3_mains.sub_category_id',
    //             'crmxi3_mains.hoa',
    //             'crmxi3_mains.created_by',
    //             'crmxi3_mains.email',
    //             'crmxi3_mains.tin',
    //             'crmxi3_mains.main_contact',
    //             'crmxi3_mains.secondary_contact',
    //             'crmxi3_mains.tertiary_contact',
    //             'crmx_bl_city.description as city_name',
    //             'crmxi3_hoas.name as hoa_name'
    //         );
        
    //     if($request->has('to_search') && $request->filled('to_search')){
    //         $to_search = $request->input('to_search');

    //         $crms->where(function ($query) use ($to_search){
    //             $lowerKeyword = mb_strtolower($to_search, 'UTF-8');
    //             $likeKeyword = '%' . $lowerKeyword . '%';
    //             $query->whereRaw('LOWER(account_id) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(firstname) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(middlename) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(lastname) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(email) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(blk_lot) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(block) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(lot) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(house_number) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(street) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(building_name) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(subdivision_village) LIKE ?', [$likeKeyword])
    //                 ->orWhereRaw('LOWER(zipcode) LIKE ?', [$likeKeyword])
    //                 ->orWhere(function($query) use ($likeKeyword){
    //                     $query->where(DB::raw("LOWER(crmxi3_mains.name)"), 'like', $likeKeyword);
    //                 })
    //                 ->orWhereHas('vehicles', function ($q) use ($likeKeyword) {
    //                     $q->where(function($query) use ($likeKeyword) {
    //                         $query->where(DB::raw("LOWER(crmxi3_vehicles.plate_no)"), 'like', $likeKeyword)
    //                               ->orWhere(DB::raw("LOWER(crmxi3_vehicles.new_sticker_no)"), 'like', $likeKeyword)
    //                               ->orWhere(DB::raw("LOWER(CONCAT(crmxi3_vehicles.plate_no,'(',crmxi3_vehicles.new_sticker_no,')'))"), 'like', $likeKeyword);
    //                     });
    //                 })
    //                 ->orWhereHas('cities', function ($q) use ($likeKeyword) {
    //                     $q->where(function($query) use ($likeKeyword) {
    //                         $query->where(DB::raw("LOWER(crmx_bl_city.description)"), 'like', $likeKeyword);
    //                     });
    //                 })
    //                 ->orWhereHas('hoas', function ($q) use ($likeKeyword) {
    //                     $q->where(function($query) use ($likeKeyword) {
    //                         $query->where(DB::raw("LOWER(crmxi3_hoas.name)"), 'like', $likeKeyword);
    //                     });
    //                 });
    //         });
    //     }else{
    //         $crms->whereRaw('false');
    //     }
    //     // dd($crms->get());
    //     return DataTables::eloquent($crms)
    //     ->editColumn('address', function ($crms) {
    //         if ($crms->block && $crms->lot && $crms->street) {
    //             // return $crms->blk_lot . ', ' . $crms->street . ($crms->building_name ? ', ' . $crms->building_name : '') . ($crms->subdivision_village ? ', ' . $crms->subdivision_village : '') . ($crms->hoa_name ? ', ' . $crms->hoa_name : '') . ($crms->city_name ? ', ' . $crms->city_name : '') . ($crms->zipcode ? ', ' . $crms->zipcode : '');
    //             return ($crms->block ? 'Blk ' . $crms->block : '') . ($crms->lot ? ' Lot ' . $crms->lot : '') . ($crms->house_number ? ', ' . $crms->house_number . ', '  : ''). ' ' . $crms->street . ($crms->building_name ? ', ' . $crms->building_name : '') . ($crms->subdivision_village ? ', ' . $crms->subdivision_village : '') . ($crms->hoa_name ? ', ' . $crms->hoa_name : '') . ($crms->city_name ? ', ' . $crms->city_name : '') . ($crms->zipcode ? ', ' . $crms->zipcode : '');
    //         } else {
    //             return $crms->address;
    //         }
    //     })
    //     ->editColumn('status', function ($crms) {
    //         if ($crms->status == 1) {
    //             return '<span class="badge text-bg-success">Active</span>';
    //         } elseif ($crms->status == 2) {
    //             return '<span class="badge text-bg-warning">Inactive</span>';
    //         } elseif ($crms->status == 3) {
    //             return '<span class="badge text-bg-danger">Suspended</span>';
    //         } elseif ($crms->status == 4) {
    //             return '<span class="badge text-bg-danger">Banned</span>';
    //         }
    //     })
    //     ->addColumn('account_type_name', function($crms){
    //         return $crms->account_type == 1 ? 'Company' : 'Individual';
    //     })
    //     ->addColumn('cname', function ($crms) {
    //         return $crms->account_type == 0 ? $crms->firstname . ' ' . $crms->lastname : $crms->firstname;
    //     })
    //     ->addColumn('email', function ($crms) {
    //         return $crms->email;
    //     })
    //     ->addColumn('vehicles', function ($crms) {
    //         return $crms->vehicles->map(function ($vehicle) {
    //             return  ($vehicle->new_sticker_no) ? $vehicle->plate_no . ' (' . $vehicle->new_sticker_no . ')' 
    //                                                 : (($vehicle->old_sticker_no) 
    //                                                     ? $vehicle->plate_no . ' (' . $vehicle->old_sticker_no . ')'
    //                                                     : $vehicle->plate_no);
    //         })->implode('<br>');
    //     })
    //     ->rawColumns(['status', 'vehicles'])
    //     ->make(true);
    // }
    public function getCRMs(Request $request)
    {   
        $crms = CRMXIMain::with([
            'creator' => function ($query) {
                $query->select('id', 'name');
            },
            'vehicles' => function ($query) {
                $query->select('crm_id', 'account_id', 'plate_no', 'new_sticker_no', 'old_sticker_no', 'sticker_date');
            },
            'acc_address' => function ($query) {
                $query->leftJoin('crmx_bl_city', 'crmxi3_address.city', '=', 'crmx_bl_city.bl_id')
                ->leftJoin('crmxi3_hoas', 'crmxi3_hoas.id', '=', 'crmxi3_address.hoa')
                ->select(
                    'crmxi3_address.id',
                    'crmxi3_address.account_id',
                    'crmxi3_address.block',
                    'crmxi3_address.lot',
                    'crmxi3_address.house_number',
                    'crmxi3_address.street',
                    'crmxi3_address.building_name',
                    'crmxi3_address.address',
                    'crmxi3_address.subdivision_village',
                    'crmxi3_address.blk_lot',
                    'crmxi3_address.city',
                    'crmxi3_address.zipcode',
                    'crmxi3_address.category_id',
                    'crmxi3_address.sub_category_id',
                    'crmxi3_address.hoa',
                    'crmxi3_address.hoa_type',
                    'crmxi3_hoas.name as hoa_name',
                    'crmx_bl_city.description  as city_name',
                );
            }
        ])
        // ->leftJoin('crmxi3_address', 'crmxi3_address.account_id', '=', 'crmxi3_mains.account_id')
        ->leftJoin('crmx_bl_city', 'crmx_bl_city.bl_id', '=', 'crmxi3_mains.city')
        ->leftJoin('crmxi3_hoas', 'crmxi3_hoas.id', '=', 'crmxi3_mains.hoa')
        ->select(
            'crmxi3_mains.crm_id',
            'crmxi3_mains.account_id',
            'crmxi3_mains.account_type',
            'crmxi3_mains.name',
            'crmxi3_mains.firstname',
            'crmxi3_mains.middlename',
            'crmxi3_mains.lastname',
            // 'crmxi3_mains.address',
            // 'crmxi3_address.blk_lot',
            // 'crmxi3_address.block',
            // 'crmxi3_address.lot',
            // 'crmxi3_address.house_number',
            // 'crmxi3_address.street',
            // 'crmxi3_address.building_name',
            // 'crmxi3_address.subdivision_village',
            // 'crmxi3_address.hoa as main_hoa',
            // 'crmxi3_address.city',
            'crmxi3_mains.civil_status',
            'crmxi3_mains.nationality',
            // 'crmxi3_address.zipcode',
            'crmxi3_mains.status',
            // 'crmxi3_address.category_id',
            // 'crmxi3_address.sub_category_id',
            // 'crmxi3_address.hoa',
            // 'crmxi3_address.hoa_type',
            'crmxi3_mains.created_by',
            'crmxi3_mains.email',
            'crmxi3_mains.tin',
            'crmxi3_mains.main_contact',
            'crmxi3_mains.secondary_contact',
            'crmxi3_mains.tertiary_contact',
            'crmx_bl_city.description as city_name',
            'crmxi3_hoas.name as hoa_name'
        )->distinct();
        
        if ($request->has('to_search') && $request->filled('to_search')) {
            $to_search = mb_strtolower($request->input('to_search'), 'UTF-8');
            $likeKeyword = '%' . $to_search . '%';
    
            $crms->where(function ($query) use ($likeKeyword) {
                $query->where('crmxi3_mains.account_id', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.firstname', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.middlename', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.lastname', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.email', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.blk_lot', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.block', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.lot', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.house_number', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.street', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.building_name', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.subdivision_village', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_mains.zipcode', 'LIKE', $likeKeyword)
                    ->orWhereRaw("LOWER(crmxi3_mains.name) LIKE ?", [$likeKeyword])
                    ->orWhereHas('vehicles', function ($q) use ($likeKeyword) {
                        $q->where('plate_no', 'LIKE', $likeKeyword)
                          ->orWhere('new_sticker_no', 'LIKE', $likeKeyword)
                          ->orWhere('old_sticker_no', 'LIKE', $likeKeyword);
                    })
                    ->orWhereHas('acc_address', function ($q) use ($likeKeyword) {
                        $q->where('block', 'LIKE', $likeKeyword)
                          ->orWhere('lot', 'LIKE', $likeKeyword)
                          ->orWhere('house_number', 'LIKE', $likeKeyword)
                          ->orWhere('street', 'LIKE', $likeKeyword)
                          ->orWhere('building_name', 'LIKE', $likeKeyword)
                          ->orWhere('address', 'LIKE', $likeKeyword)
                          ->orWhere('subdivision_village', 'LIKE', $likeKeyword)
                          ->orWhere('blk_lot', 'LIKE', $likeKeyword)
                          ->orWhere('city', 'LIKE', $likeKeyword)
                          ->orWhere('zipcode', 'LIKE', $likeKeyword)
                          ->orWhere('category_id', 'LIKE', $likeKeyword)
                          ->orWhere('sub_category_id', 'LIKE', $likeKeyword)
                          ->orWhere('hoa', 'LIKE', $likeKeyword)
                          ->orWhere('hoa_type', 'LIKE', $likeKeyword);
                    })
                    ->orWhere('crmx_bl_city.description', 'LIKE', $likeKeyword)
                    ->orWhere('crmxi3_hoas.name', 'LIKE', $likeKeyword);
            });
        } else {
            $crms->whereRaw('false');
        }

        return DataTables::eloquent($crms)
            // ->editColumn('address', function ($crms) {
            //     // if ($crms->block && $crms->lot && $crms->street) {
            //     if ($crms->street) {
            //         return ($crms->block ? 'Blk ' . $crms->block : ($crms->blk_lot ? $crms->blk_lot : '')) .
            //             ($crms->lot && !$crms->blk_lot ? ' Lot ' . $crms->lot : '') .
            //             ($crms->house_number ? ', ' . $crms->house_number . ', ' : '') . ' ' .
            //             $crms->street .
            //             ($crms->building_name ? ', ' . $crms->building_name : '') .
            //             ($crms->subdivision_village ? ', ' . $crms->subdivision_village : '') .
            //             ($crms->hoa_name ? ', ' . $crms->hoa_name : '') .
            //             ($crms->city_name ? ', ' . $crms->city_name : '') .
            //             ($crms->zipcode ? ', ' . $crms->zipcode : '');
            //     } else {
            //         return $crms->address;
            //     }
            // })
            ->addColumn('acc_address', function ($crms) {
                // if ($crms->block && $crms->lot && $crms->street) {
                    // return $crms->acc_address->map(function ($address, $i){
                    //     if ($address->street) {
                    //         return '<b>'.'Address ' . $i+1 . ':' . '</b>' . ' ' . ($address->block ? 'Blk ' . $address->block : ($address->blk_lot ? $address->blk_lot : '')) .
                    //             ($address->lot && !$address->blk_lot ? ' Lot ' . $address->lot : '') .
                    //             ($address->house_number ? ', ' . $address->house_number . ', ' : '') . ' ' .
                    //             $address->street .
                    //             ($address->building_name ? ', ' . $address->building_name : '') .
                    //             ($address->subdivision_village ? ', ' . $address->subdivision_village : '') .
                    //             ($address->hoa_name ? ', ' . $address->hoa_name : '') .
                    //             ($address->city_name ? ', ' . $address->city_name : '') .
                    //             ($address->zipcode ? ', ' . $address->zipcode : '');
                    //     } 
                    //     else {
                    //         return $address->address;
                    //     }
                    // })->implode('<br>');
                    return $crms->acc_address;
            })
            ->editColumn('status', function ($crms) {
                // if(!$crms->status){
                //     return '<span class="badge text-bg-success">Active</span>';
                // }
                // return match($crms->status) {
                //     1 => '<span class="badge text-bg-success">Active</span>',
                //     2 => '<span class="badge text-bg-warning">Inactive</span>',
                //     3 => '<span class="badge text-bg-danger">Suspended</span>',
                //     4 => '<span class="badge text-bg-danger">Banned</span>',
                //     default => '<span class="badge text-bg-success">Active</span>',
                // };
                if ($crms->status == 1) {
                    return '<span class="badge text-bg-success">Active</span>';
                } elseif ($crms->status == 2) {
                    return '<span class="badge text-bg-warning">Inactive</span>';
                } elseif ($crms->status == 3) {
                    return '<span class="badge text-bg-danger">Suspended</span>';
                } elseif ($crms->status == 4) {
                    return '<span class="badge text-bg-danger">Banned</span>';
                };
            })
            ->addColumn('account_type_name', function ($crms) {
                return $crms->account_type == 1 ? 'Company' : 'Individual';
            })
            ->addColumn('cname', function ($crms) {
                return $crms->account_type == 0 ? $crms->firstname . ' ' . $crms->lastname : $crms->firstname;
            })
            ->addColumn('email', function ($crms) {
                return $crms->email;
            })
            ->addColumn('vehicles', function ($crms) {
                return $crms->vehicles->map(function ($vehicle) {
                    return $vehicle->new_sticker_no ? $vehicle->plate_no . ' (' . $vehicle->new_sticker_no . ')' :
                        ($vehicle->old_sticker_no ? $vehicle->plate_no . ' (' . $vehicle->old_sticker_no . ')' : $vehicle->plate_no);
                })->implode('<br>');
            })
            ->addColumn('creator', function (CRMXIMain $crm) {
                return $crm->creator ? $crm->creator->name : '';
            })
            ->addColumn('vehicle_count', function ($crms) {
                return count($crms->vehicles);
            })
            ->rawColumns(['status', 'vehicles','acc_address'])
            ->make(true);

    }
    public function getSubCategories($id)
    {
        $sub_cat = DB::select("SELECT *
                        FROM get_subcat
                        WHERE category_id = $id
                        ");
        return response()->json($sub_cat);
    }

    public function getHoaTypes($id)
    {
        $hoas = DB::select("SELECT
                        	*
                        FROM get_hoa_types
                        WHERE sub_category_id = $id
                        ");
        return response()->json($hoas);
    }

    public function getVehicleOwnershipStatus($id)
    {
        $vos = DB::select("SELECT
                            	*
                            FROM get_vehicle_ownership_status
                            WHERE subcat_hoatype_id = $id
                        ");
        return response()->json($vos);
    }

    public function getZipcode($id)
    {
        $zipcode = DB::select("SELECT
                            	*
                            FROM crmxi3_zip_codes
                            WHERE city = $id
                        ");
        return response()->json($zipcode);
    }

    public function generateAccountNum($account_type,$category,$subcat,$hoa,$hoa_type)
    {
        $hoa_code = str_pad((string)$hoa,3,'0',STR_PAD_LEFT);
        $hoa_type_code = str_pad((string)$hoa_type,2,'0',STR_PAD_LEFT);
        $subcat_code = str_pad((string)$subcat,2,'0',STR_PAD_LEFT);
        $series = $account_type . $category . $subcat_code . '-' . $hoa_code . $hoa_type_code . '-';

        $last_account = CRMXIMain::select('account_id')->latest()->first();

        if($last_account){
            $last_account_id = (int)explode('-',$last_account->account_id)[2];
        }else{
            $last_account_id = 0;
        }

        do {
            $last_account_id++;
            $srn = str_pad((string)$last_account_id,6,'0',STR_PAD_LEFT);
            $check_crm = CRMXIMain::where('account_id', 'LIKE', '%-' . $srn)->exists();
        }while ($check_crm);

        return $series . $srn;
    }

    public function insertAccount (Request $req)
    {
        try {
            // dd($req->toPass);
            if($req->current_account_id){
                DB::transaction(function () use ($req) {
                    DB::table('crmxi3_mains')->where('account_id',$req->current_account_id)
                    ->update([
                        'account_type' => $req->account_type,
                        'name' =>  $req->account_type == 0 ? '' : $req->representative_name,
                        'firstname' =>  $req->account_type == 0 ? $req->first_name : $req->company_name,
                        'middlename' => $req->middle_name,
                        'lastname' => $req->last_name,
                        // 'blk_lot' => $req->blk_lot,
                        // 'block' => $req->block  ,
                        // 'lot' => $req->lot,
                        // 'house_number' => $req->house_number,
                        // 'street' => $req->street,
                        // 'building_name' => $req->building_name,
                        // 'subdivision_village' => $req->subdivision,
                        // 'city' => $req->city,
                        // 'zipcode' => $req->zip_code,
                        'tin' => $req->tin_no,
                        'civil_status' => $req->civil_status,
                        'nationality' => $req->nationality,
                        'email' => $req->email,
                        // 'category_id' => $req->category_id,
                        // 'sub_category_id' => $req->sub_category_id,
                        'hoa' => $req->hoa,
                        'main_contact' => $req->main_contact,
                        'secondary_contact' => $req->secondary_contact,
                        'tertiary_contact' => $req->tertiary_contact,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                });

                $acc_address = $req->toPass;
                for ($i = 0; $i < count($acc_address); $i++) {
                    DB::table('crmxi3_address')->updateOrInsert(
                        // Conditions to check if the record exists
                        [
                            'account_id' => $req->current_account_id,
                            'id' => $acc_address[$i]['current_id'] // Check for existing record based on id and account_id
                        ],
                        // Data to be updated or inserted (including created_at and created_by)
                        [
                            'account_id' => $req->current_account_id, // Ensure account_id is included for inserts
                            'block' => $acc_address[$i]['block'] ?? null,
                            'lot' => $acc_address[$i]['lot'] ?? null,
                            'house_number' => $acc_address[$i]['house_number'] ?? null,
                            'street' => $acc_address[$i]['street'],
                            'building_name' => $acc_address[$i]['building_name'] ?? null,
                            'subdivision_village' => $acc_address[$i]['subdivision'] ?? null,
                            'city' => $acc_address[$i]['city'] ?? null,
                            'zipcode' => $acc_address[$i]['zip_code'] ?? null,  
                            'category_id' => $acc_address[$i]['category_id'],
                            'sub_category_id' => $acc_address[$i]['sub_category'],
                            'hoa' => $acc_address[$i]['hoa'] ?? null,
                            'hoa_type' => $acc_address[$i]['hoa_type'] ?? null,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s')                        
                        ]
                    );
                    
                };

                return redirect()
                ->back()
                ->withInput()
                ->with(['success' => 'Successfully Edited', 'account_id' => $req->current_account_id]);
    
            }else{
                // dd($req);
                $account_id = $this->generateAccountNum(
                    $req->account_type,
                    $req->toPass[0]['category_id'], 
                    $req->toPass[0]['sub_category'], 
                    $req->toPass[0]['hoa'], 
                    $req->toPass[0]['hoa_type']
                );
                DB::transaction(function () use ($account_id, $req) {
                    DB::table('crmxi3_mains')->insert([
                        'account_id' => $account_id,
                        'account_type' => $req->account_type,
                        'name' =>  $req->account_type == 0 ? '' : $req->representative_name,
                        'firstname' =>  $req->account_type == 0 ? $req->first_name : $req->company_name,
                        'middlename' => $req->middle_name,
                        'lastname' => $req->last_name,
                        // 'blk_lot' => $req->blk_lot,
                        // 'block' => $req->block  ,
                        // 'lot' => $req->lot,
                        // 'house_number' => $req->house_number,
                        // 'street' => $req->street,
                        // 'building_name' => $req->building_name,
                        // 'subdivision_village' => $req->subdivision,
                        // 'city' => $req->city,
                        // 'zipcode' => $req->zip_code,
                        'tin' => $req->tin_no,
                        'civil_status' => $req->civil_status,
                        'nationality' => $req->nationality,
                        'email' => $req->email,
                        // 'category_id' => $req->category_id,
                        // 'sub_category_id' => $req->sub_category_id,
                        // 'hoa' => $req->hoa,
                        'status' => 1,
                        'main_contact' => $req->main_contact,
                        'secondary_contact' => $req->secondary_contact,
                        'tertiary_contact' => $req->tertiary_contact,
                        'created_by' => Auth::id()
                    ]);
                    $acc_address = $req->toPass;
                    for ($i = 0; $i < count($acc_address); $i++) {
                        DB::table('crmxi3_address')->insert([
                            'account_id' => $account_id,
                            'block' => $acc_address[$i]['block'] ?? null,
                            'lot' => $acc_address[$i]['lot'] ?? null,
                            'house_number' => $acc_address[$i]['house_number'] ?? null,
                            'street' => $acc_address[$i]['street'],
                            'building_name' => $acc_address[$i]['building_name'] ?? null,
                            'subdivision_village' => $acc_address[$i]['subdivision'] ?? null,
                            'city' => $acc_address[$i]['city'] ?? null,
                            'zipcode' => $acc_address[$i]['zip_code'] ?? null,  
                            'category_id' => $acc_address[$i]['category_id'],
                            'sub_category_id' => $acc_address[$i]['sub_category'],
                            'hoa' => $acc_address[$i]['hoa'] ?? null,
                            'hoa_type' => $acc_address[$i]['hoa_type'] ?? null,
                            'created_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                    }
                });
                return redirect()
                ->back()
                ->withInput()
                ->with(['success' => 'Successfully Added', 'account_id' => $account_id]);
    
            }
        } catch (\Exception $th) {
            // dd($th);
            return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed.');
        }
    }

    public function view_account(Request $request, $account_id)
    {
        // Forget vehicle count map
        session()->forget('vehicleCountMap');
        session()->forget('customer_vehicles');
        
        // if($req->has('account_id') && $req->filled('account_id')){
        // $account_id = $req->input('account_id');
        $crms_account = CRMXIMain::with([
            'acc_address' => function ($query) {
                $query->leftJoin('crmx_bl_city', 'crmxi3_address.city', '=', 'crmx_bl_city.bl_id')
                    ->leftJoin('crmxi3_hoas', 'crmxi3_hoas.id', '=', 'crmxi3_address.hoa')
                    ->leftJoin('crmxi3_categories', 'crmxi3_categories.id', 'crmxi3_address.category_id')
                    ->leftJoin('crmxi3_subcat', 'crmxi3_subcat.id', 'crmxi3_address.sub_category_id')
                    ->leftJoin('crmxi3_hoa_types', 'crmxi3_hoa_types.id', 'crmxi3_address.hoa_type')
                    ->select(
                        'crmxi3_address.id',
                        'crmxi3_address.account_id',
                        'crmxi3_address.block',
                        'crmxi3_address.lot',
                        'crmxi3_address.house_number',
                        'crmxi3_address.street',
                        'crmxi3_address.building_name',
                        'crmxi3_address.address',
                        'crmxi3_address.subdivision_village',
                        'crmxi3_address.blk_lot',
                        'crmxi3_address.city',
                        'crmxi3_address.zipcode',
                        'crmxi3_address.category_id',
                        'crmxi3_address.sub_category_id',
                        'crmxi3_address.hoa',
                        'crmxi3_address.hoa_type',
                        'crmxi3_hoas.name as hoa_name',
                        'crmx_bl_city.description  as city_name',
                        'crmxi3_categories.name as category_name',
                        'crmxi3_subcat.name as subcat_name',
                        'crmxi3_hoas.name as hoa_name',
                        'crmxi3_hoa_types.name as hoa_type_name'
                    );
            }
        ])
            ->where('account_id', $account_id)
            ->select(
                'crmxi3_mains.crm_id',
                'crmxi3_mains.account_id',
                'crmxi3_mains.customer_id',
                'crmxi3_mains.account_type',
                'crmxi3_mains.name',
                'crmxi3_mains.firstname',
                'crmxi3_mains.middlename',
                'crmxi3_mains.lastname',
                'crmxi3_mains.red_tag',
                // 'crmxi3_mains.address',
                // 'crmxi3_mains.blk_lot',
                // 'crmxi3_mains.block',
                // 'crmxi3_mains.lot',
                // 'crmxi3_mains.house_number',
                // 'crmxi3_mains.street',
                // 'crmxi3_mains.building_name',
                // 'crmxi3_mains.subdivision_village',
                // 'crmxi3_mains.category_id',
                // 'crmxi3_mains.sub_category_id',
                // 'crmxi3_mains.hoa',
                // 'crmxi3_mains.city',
                // 'crmxi3_mains.zipcode',
                'crmxi3_mains.status',
                'crmxi3_mains.created_by',
                'crmxi3_mains.created_at',
                'crmxi3_mains.email',
                'crmxi3_mains.main_contact',
                'crmxi3_mains.secondary_contact',
                'crmxi3_mains.tertiary_contact',
                // 'crmx_bl_city.description as city_name',
                // 'crmxi3_categories.name as category_name',
                // 'crmxi3_subcat.name as subcat_name',
                // 'crmxi3_hoas.name as hoa_name'
            )
            ->get();

        if ($request->query('req') !== NULL) {
            $req = $request->query('req');
        } else {
            $req = 1;
        }

        $vehicles =  DB::select('SELECT a.*, b.account_id FROM `crmxi3_vehicles` a INNER JOIN crmxi3_mains b ON a.account_id = b.account_id  WHERE a.account_id = "' . $account_id . '"');

        $hoas = DB::select('SELECT * FROM crmxi3_hoas');
        $cities = DB::select('SELECT *FROM crmx_bl_city WHERE `status` = 1');
        $categories = DB::select('select * from crmxi3_categories');
        $civil_status = DB::select('select * from crmxi3_civil_status');
        $nationalities = DB::select('select * from crmxi3_nationalities');
        $subcats = DB::select('SELECT * FROM get_subcat');
        $hoatypes = DB::select('SELECT * FROM get_hoa_types');
        $vehicle_ownership_status = DB::select('SELECT * FROM get_vehicle_ownership_status');
        $redtag_list = DB::select('SELECT * FROM crmxi3_redtag_reason');
        $redtags = DB::table('crmxi3_redtag')->where('crmxi3_redtag.account_id', $account_id)
            ->leftJoin('crmxi3_vehicles', 'crmxi3_vehicles.id', 'crmxi3_redtag.vehicle_id')
            ->select('crmxi3_redtag.*', 'crmxi3_vehicles.plate_no')
            ->get();
        
        // New Patch 11/19/24
        $year = date("Y");

        // get all renewed cars
        $renewed = CRMXIMain::query()
            ->join('crmxi3_vehicles', 'crmxi3_mains.account_id', '=', 'crmxi3_vehicles.account_id')
            ->join('crmxi3_address', 'crmxi3_vehicles.address_id', '=', 'crmxi3_address.id')
            ->join('crmxi3_categories', 'crmxi3_address.category_id', '=', 'crmxi3_categories.id')
            ->join('crmxi3_subcat', 'crmxi3_address.sub_category_id', '=', 'crmxi3_subcat.id')
            ->leftJoin('crmxi3_hoas', 'crmxi3_address.hoa', '=', 'crmxi3_hoas.id')
            ->join('crmxi3_vehicle_ownership_status', 'crmxi3_vehicles.vehicle_ownership_status_id', '=', 'crmxi3_vehicle_ownership_status.id')
            ->select(
                'crmxi3_vehicles.*',
                'crmxi3_mains.customer_id',
                'crmxi3_mains.account_id',
                'crmxi3_mains.hoa',
                'crmxi3_categories.name as category_name',
                'crmxi3_subcat.name as sub_category_name',
                'crmxi3_hoas.name as hoa_name',
                'crmxi3_vehicle_ownership_status.name as vehicle_ownership_status',
            )
            ->where('crmxi3_vehicles.account_id', $crms_account[0]->account_id)
            ->where('crmxi3_vehicles.sticker_date', $year)
            ->get();

        // Filter linked accounts to only get renewed cars
        $renewedCar = array_filter($renewed->toArray(), function ($vehicle) {
            return $vehicle['type'] == 'Car';
        });

        // Filter linked accounts to only get renewed motorcycles
        $renewedMotor = array_values(array_filter($renewed->toArray(), function ($vehicle) {
            return $vehicle['type'] == 'Motorcycle';
        }));

        // Get all vehicle count, grouped by address-related fields (category, subcategory, hoa)
        $distinctVehicleCounts = CRMXIMain::query()
            ->join('crmxi3_vehicles', 'crmxi3_mains.account_id', '=', 'crmxi3_vehicles.account_id')
            ->join('crmxi3_address', 'crmxi3_vehicles.address_id', '=', 'crmxi3_address.id')
            ->join('crmxi3_categories', 'crmxi3_address.category_id', '=', 'crmxi3_categories.id')
            ->join('crmxi3_subcat', 'crmxi3_address.sub_category_id', '=', 'crmxi3_subcat.id')
            ->leftJoin('crmxi3_hoas', 'crmxi3_address.hoa', '=', 'crmxi3_hoas.id')
            ->join('crmxi3_vehicle_ownership_status', 'crmxi3_vehicles.vehicle_ownership_status_id', '=', 'crmxi3_vehicle_ownership_status.id')
            ->select(
                'crmxi3_categories.name as category_name',
                'crmxi3_subcat.name as sub_category_name',
                'crmxi3_vehicles.type as type',
                'crmxi3_vehicles.plate_no as plate_no',
                'crmxi3_hoas.name as hoa_name',
                'crmxi3_vehicle_ownership_status.name as vehicle_ownership_status',
                'crmxi3_address.id as address_id',
                'crmxi3_vehicles.address_id as veh_address_id',
                'crmxi3_vehicles.id as vehicle_id',
            )
            ->selectRaw('
                COUNT(DISTINCT crmxi3_vehicles.plate_no) as vehicle_count,
                SUM(CASE WHEN YEAR(crmxi3_vehicles.sticker_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as current_year_vehicles
            ')
            ->where('crmxi3_mains.account_id', $crms_account[0]->account_id)
            ->groupBy(
                'category_name',
                'sub_category_name',
                'type',
                'hoa_name',
                'vehicle_ownership_status',
            );

        // Get distinct counts (grouped by address fields and vehicle-related fields)
        $distinctCounts = DB::table(DB::raw("({$distinctVehicleCounts->toSql()}) as sub"))
            ->mergeBindings($distinctVehicleCounts->getQuery()) // Merge the bindings
            ->select(
                'category_name',
                'sub_category_name',
                'type',
                'plate_no',
                'hoa_name',
                'veh_address_id',
                'vehicle_ownership_status',
                DB::raw('SUM(vehicle_count) as total_vehicle_count'), // Sum the vehicle counts
                DB::raw('SUM(current_year_vehicles) as current_year_vehicle_count')
            )
            ->groupBy(
                'category_name',
                'sub_category_name',
                'type',
                'hoa_name',
                'vehicle_ownership_status',
            )
            ->get();
        // End of New Patch 11/19/24

        $invoices = DB::table('spc3_invoice')
            ->select('spc3_invoice.created_at as invoice_created', 'spc3_invoice.*', 'crmxi3_mains.*', 'srs_users.*')
            ->leftJoin('crmxi3_mains', 'crmxi3_mains.crm_id', '=', 'spc3_invoice.c_id')
            ->leftJoin('srs_users', 'srs_users.id', '=', 'spc3_invoice.action_by')
            ->where('spc3_invoice.c_id', $crms_account[0]->crm_id)
            ->latest('spc3_invoice.created_at')
            ->get();

        $status_red = DB::table('crmxi3_redtag')
            ->where('account_id', '=', $crms_account[0]->account_id)
            ->where('status', '=', 1) // 0 - Lifted, 1 - Red Tag
            ->latest('date_created')
            ->count();

         // For patch 11/21/24
        $vehicle_owners = DB::table('crmxi3_vehicles')
            ->join('crmxi3_address', 'crmxi3_vehicles.address_id', '=', 'crmxi3_address.id')
            ->join('crmxi3_vehicle_owners', 'crmxi3_vehicles.id', '=', 'crmxi3_vehicle_owners.vehicle_id')
            ->where('crmxi3_vehicles.account_id', $crms_account[0]->account_id)
            ->groupBy('firstname', 'middlename', 'lastname')
            ->get();

        return view(
            'crmxi3.crmxi_view_account',
            [
                'crms_account' => $crms_account,
                'hoas' => $hoas,
                'cities' => $cities,
                'categories' => $categories,
                'civil_status' => $civil_status,
                'nationalities' => $nationalities,
                'subcats' => $subcats,
                'hoatypes' => $hoatypes,
                'vehicle_ownership_status' => $vehicle_ownership_status,
                'redtag_list' => $redtag_list,
                'redtags' => $redtags,
                'renewed' => $renewed,
                'req' => $req,
                'vehicles' => $vehicles,
                'invoices' => $invoices,
                'status_red' => $status_red,
                'renewedCar' => $renewedCar,
                'renewedMotor' => $renewedMotor,

                // New Patch 11/19/24
                'distinctCounts' => $distinctCounts,
                'distinctVehicleCounts' => $distinctVehicleCounts,

                // New Patch 11/21/24
                'vehicle_owners' => $vehicle_owners
            ]
        );
        // }
    }

    public function insertAddress (Request $req)
    {
        try {
            $acc_address = $req->toPass;
                for ($i = 0; $i < count($acc_address); $i++) {
                    DB::table('crmxi3_address')->updateOrInsert(
                        // Conditions to check if the record exists
                        [
                            'account_id' => $req->current_account_id,
                            'id' => $acc_address[$i]['current_id'] // Check for existing record based on id and account_id
                        ],
                        // Data to be updated or inserted (including created_at and created_by)
                        [
                            'account_id' => $req->current_account_id, // Ensure account_id is included for inserts
                            'block' => $acc_address[$i]['block'] ?? null,
                            'lot' => $acc_address[$i]['lot'] ?? null,
                            'house_number' => $acc_address[$i]['house_number'] ?? null,
                            'street' => $acc_address[$i]['street'],
                            'building_name' => $acc_address[$i]['building_name'] ?? null,
                            'subdivision_village' => $acc_address[$i]['subdivision'] ?? null,
                            'city' => $acc_address[$i]['city'] ?? null,
                            'zipcode' => $acc_address[$i]['zip_code'] ?? null,  
                            'category_id' => $acc_address[$i]['category_id'],
                            'sub_category_id' => $acc_address[$i]['sub_category'],
                            'hoa' => $acc_address[$i]['hoa'] ?? null,
                            'hoa_type' => $acc_address[$i]['hoa_type'] ?? null,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s')                        
                        ]
                    );
                    
                };

            return redirect()
            ->back()
            ->withInput()
            ->with(['success' => "Successfully Saved", 'account_id' => $req->current_account_id]);
        } catch (\Exception $th) {
            // dd($th);
            return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed.');
        }
    }
    

}
