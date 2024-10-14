<?php

namespace App\Http\Controllers;

use App\Models\CrmMain;
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


class CRMController extends Controller
{
    private function insertLogCrm($action)
    {
        $log = new LogCrmHist();
        $log->action_by = auth()->user()->name;
        $log->action = $action;
        $log->save();
    }

    private function insertLogVehicles($action)
    {
        $log = new LogVehicleHist();
        $log->action_by = auth()->user()->name;
        $log->action = $action;
        $log->save();
    }

    public function getNextId($category, $subCategory, $hoa, $hoaType = 'string')
    {
        // $today = now();
        // $series = $today->format('y') . $today->format('m') . $category . $subCategory . '-';

        // $lastCrm = CrmMain::where('customer_id', 'like', $series . '%')->select('customer_id')->latest()->first();

        // if ($lastCrm) {
        //     $lastSeriesNumber = (int)str_replace($series, '', $lastCrm->customer_id);
        // } else {
        //     $lastSeriesNumber = 0;
        // }

        // do {
        //     $lastSeriesNumber++;
        //     $srn = $series . str_pad((string)$lastSeriesNumber, 5, '0', STR_PAD_LEFT);
        //     $crm = CrmMain::where('customer_id', $srn)->exists();
        // } while ($crm);

        // return $srn;

        if ($hoa) {
            if ($hoaType == 'int') {
                $hoaId = $hoa;
            } else {
                $srsHoa = SrsHoa::where('name', $hoa)->select('id')->first();
                if ($srsHoa) {
                    $hoaId = $srsHoa->id;
                } else {
                    $srsHoa = SrsHoa::where('name', 'LIKE', $hoa . '%')->select('id')->first();
                    if ($srsHoa) {
                        $hoaId = $srsHoa->id;
                    } else {
                        $hoaId = 0;
                    }
                }
            }
        } else {
            $hoaId = 0;
        }


        $series = $category . $subCategory . $hoaId . '-';
        $lastCrm = CrmMain::select('customer_id')->latest()->first();

        if ($lastCrm) {
            $lastSeriesNumber = (int)explode('-', $lastCrm->customer_id)[1];
        } else {
            $lastSeriesNumber = 0;
        }

        do {
            $lastSeriesNumber++;
            $srn = str_pad((string)$lastSeriesNumber, 6, '0', STR_PAD_LEFT);
            $crm = CrmMain::where('customer_id', 'LIKE', '%-' . $srn)->exists();
        } while ($crm);

        return $series . $srn;
    }

    public function index()
    {
        $this->authorize('access', CrmMain::class);
        
        $crms = CrmMain::orderBy('created_at', 'desc')->paginate(30);

        $hoas = DB::select('SELECT `name` FROM srs_hoas');
        $cities = DB::select('SELECT *FROM crmx_bl_city WHERE `status` = 1');

        //$categories = DB::select('select * from srs_categories');

        $categories = DB::select('select * from spc_categories');
        return view('crm.crmx', ['crms' => $crms, 'hoas' => $hoas, 'cities' => $cities,  'categories' => $categories]);
    }

    public function index_v2()
    {   
        // $user_access = [
        //     "itqa@atomitsoln.com",
        //     "lito.tampis@atomitsoln.com",
        //     "srsadmin@atomitsoln.com"
        // ];

        // if(!in_array(auth()->user()->email, $user_access)) {
        //     abort(403);
        // }

        $this->authorize('access', CrmMain::class);

        $crms = CrmMain::orderBy('created_at', 'desc')->paginate(30);

        $hoas = DB::select('SELECT `name` FROM srs_hoas');
        $cities = DB::select('SELECT *FROM crmx_bl_city WHERE `status` = 1');

        //$categories = DB::select('select * from srs_categories');

        $categories = DB::select('select * from spc_categories');
        return view('crm.crmx_v2', ['crms' => $crms, 'hoas' => $hoas, 'cities' => $cities,  'categories' => $categories]);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $crms = CrmMain::with(['creator' => function ($query) {
            $query->select('id', 'name');
        }])
            ->with(['vehicles' => function ($query) {
                $query->select('crm_id', 'plate_no', 'new_sticker_no', 'old_sticker_no');
            }])
            ->select('crm_id', 'customer_id', 'firstname', 'lastname', 'address', 'blk_lot', 'street', 'building_name', 'subdivision_village', 'hoa', 'city', 'zipcode', 'owned_vehicles', 'status', 'created_by', 'email')
            ->whereIn('status', [1, 2, 3, 4]);

        return DataTables::eloquent($crms)
            ->filterColumn('cname', function ($query, $keyword) {
                $sql = "CONCAT(crm_mains.firstname,' ',crm_mains.lastname)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('address', function ($query, $keyword) {
                $sql = "CONCAT(crm_mains.blk_lot,', ',crm_mains.street)  like ?";
                // $sql2 = "CONCAT(crm_mains.blk_lot,', ',crm_mains.street,', ',crm_mains.building_name,', ',crm_mains.subdivision_village,', ',crm_mains.hoa,', ',crm_mains.city,', ',crm_mains.zipcode)  like ?";
                $sql2 = "CONCAT(crm_mains.blk_lot,', ',crm_mains.street,', ',crm_mains.building_name,', ',crm_mains.subdivision_village)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"])
                    ->orWhereRaw($sql2, ["%{$keyword}%"])
                    ->orWhere(function ($q) use ($keyword) {
                        $q->where('blk_lot', $keyword)
                            ->orWhere('street', $keyword)
                            ->orWhere('building_name', $keyword)
                            ->orWhere('subdivision_village', $keyword)
                            ->orWhere('hoa', $keyword)
                            ->orWhere('city', $keyword)
                            ->orWhere('zipcode', $keyword);
                    });
            })
            ->filterColumn('vehicles', function ($query, $keyword) {
                $query->whereHas('vehicles', function ($q) use ($keyword) {
                    $q->where(DB::raw("CONCAT(crm_vehicles.plate_no,'(',crm_vehicles.new_sticker_no,')')"), 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('stickers', function ($query, $keyword) {
                $query->whereHas('vehicles', function ($q) use ($keyword) {
                    $q->where(function ($innerQuery) use ($keyword) {
                        $innerQuery->where('new_sticker_no', 'like', "%{$keyword}%")
                            ->orWhere('old_sticker_no', 'like', "%{$keyword}%");
                    })
                        ->orWhere(DB::raw("CONCAT(crm_vehicles.plate_no,'(',crm_vehicles.new_sticker_no,')')"), 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('email', function ($query, $keyword) {
                $sql = "CONCAT(crm_mains.email)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->editColumn('address', function (CrmMain $crm) {
                if ($crm->blk_lot && $crm->street) {
                    return $crm->blk_lot . ', ' . $crm->street . ($crm->building_name ? ', ' . $crm->building_name : '') . ($crm->subdivision_village ? ', ' . $crm->subdivision_village : '') . ($crm->hoa ? ', ' . $crm->hoa : '') . ($crm->city ? ', ' . $crm->city : '') . ($crm->zipcode ? ', ' . $crm->zipcode : '');
                } else {
                    return $crm->address;
                }
            })
            ->editColumn('status', function (CrmMain $crm) {

                if ($crm->status == 1) {
                    return '<span class="badge text-bg-success">Active</span>';
                } elseif ($crm->status == 2) {
                    return '<span class="badge text-bg-warning">Inactive</span>';
                } elseif ($crm->status == 3) {
                    return '<span class="badge text-bg-danger">Suspended</span>';
                } elseif ($crm->status == 4) {
                    return '<span class="badge text-bg-danger">Banned</span>';
                }
            })
            ->addColumn('cname', function (CrmMain $crm) {
                return $crm->firstname . ' ' . $crm->lastname;
            })
            ->addColumn('email', function (CrmMain $crm) {
                return $crm->email;
            })
            ->addColumn('vehicles', function (CrmMain $crm) {
                return $crm->vehicles->map(function ($vehicle) {
                    return ($vehicle->new_sticker_no) ? $vehicle->plate_no . '(' . $vehicle->new_sticker_no . ')' : $vehicle->plate_no . '(' . $vehicle->old_sticker_no . ')';
                })->implode('<br>');
            })
            ->addColumn('stickers', function (CrmMain $crm) {
                return $crm->vehicles->map(function ($vehicle) {
                    $stickerNo = '';
                    if ($vehicle->old_sticker_no) {
                        $stickerNo .= '(OSN) ' . $vehicle->old_sticker_no . PHP_EOL;
                    }
                    if ($vehicle->new_sticker_no) {
                        $stickerNo .= '(NSN) ' . $vehicle->new_sticker_no . PHP_EOL;
                    }
                    return $stickerNo;
                })->implode('');
            })

            ->addColumn('creator', function (CrmMain $crm) {
                return $crm->creator ? $crm->creator->name : '';
            })
            ->addColumn('crm_actions', function (CrmMain $crm) {
                $data = '';
                if ($crm->category_id == 0 && $crm->sub_category_id == 0) {
                    // $data .= '<a href="/crm/view-details-crm-old/' . $crm->crm_id . '/' . $crm->customer_id . '" class="me-3">
                    //                             <i class="fas fa-eye" style="color:#B2BEB5; font-size:20px"></i>
                    //                         </a>';
                    $data .= '<a href="/spc/view-scp-v2/' . $crm->crm_id . '/' . $crm->customer_id . '" class="me-3">
                                                <i class="fas fa-eye" style="color:#B2BEB5; font-size:20px"></i>
                                            </a>';
                } else {
                    $data .= '<a href="/crm/view-details-crm/' . $crm->crm_id . '/' . $crm->customer_id . '" class="me-3">
                                                <i class="fas fa-eye" style="color:#B2BEB5; font-size:20px"></i>
                                            </a>';
                }
                $data .= '<a href="/crm/edit-details-crm/' . $crm->crm_id . '">
                                            <i class="far fa-edit" style="color:#B2BEB5; font-size:20px"></i>
                                        </a>';

                $data .= '<a href="/crm_p2c/' . $crm->crm_id . '">
                    <i class="fa-solid fa-users" style="color:#B2BEB5; font-size:20px"></i>
                </a>
                ';
                
                return $data;
            })
            ->rawColumns(['status', 'crm_actions', 'vehicles'])
            ->make(true);
    }

    public function list_v2(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $crms = CrmMain::with(['creator' => function ($query) {
            $query->select('id', 'name');
        }])
        ->with(['vehicles' => function ($query) {
            $query->select('crm_id', 'plate_no', 'new_sticker_no', 'old_sticker_no', 'sticker_date');
                // only get the current year sticker
                // ->where('sticker_date', date('Y'));
        }])
        ->select('crm_id', 'customer_id', 'firstname', 'lastname', 'address', 'blk_lot', 'street', 'building_name', 'subdivision_village', 'hoa', 'city', 'zipcode', 'owned_vehicles', 'status', 'created_by', 'email')
        ->whereIn('status', [1, 2, 3, 4]);


        if ($request->has('q') && $request->filled('q')) {
            $keyword = $request->input('q');
        
            $crms->where(function ($query) use ($keyword) {
                $lowerKeyword = mb_strtolower($keyword, 'UTF-8');
                $likeKeyword = '%' . $lowerKeyword . '%';
                $query->whereRaw('LOWER(customer_id) LIKE ?', [$likeKeyword])
                    ->orWhere(function ($q) use ($likeKeyword) {
                        $q->where(function ($query) use ($likeKeyword) {
                            $query->whereRaw('LOWER(firstname) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(middlename) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(lastname) LIKE ?', [$likeKeyword]);
                        })
                        ->orWhereRaw('LOWER(CONCAT(firstname, " ", lastname)) LIKE ?', [$likeKeyword])
                        ->orWhereRaw('LOWER(CONCAT(firstname, " ", middlename," ", lastname)) LIKE ?', [$likeKeyword])
                        ->orWhereRaw('LOWER(CONCAT(lastname, " ", firstname)) LIKE ?', [$likeKeyword])
                        ->orWhereRaw('LOWER(CONCAT(lastname, " ", firstname," ", middlename)) LIKE ?', [$likeKeyword])
                        ->orWhereRaw('LOWER(CONCAT(lastname, ", ", firstname," ", middlename)) LIKE ?', [$likeKeyword])
                        ->orWhereRaw('LOWER(CONCAT(lastname, ", ", firstname,", ", middlename)) LIKE ?', [$likeKeyword]);
                    })
                    ->orWhereRaw('LOWER(email) LIKE ?', [$likeKeyword])
                    ->orWhereHas('vehicles', function ($q) use ($likeKeyword) {
                        $q->where(function($query) use ($likeKeyword) {
                            $query->where(DB::raw("LOWER(crm_vehicles.plate_no)"), 'like', $likeKeyword)
                                  ->orWhere(DB::raw("LOWER(crm_vehicles.new_sticker_no)"), 'like', $likeKeyword)
                                  ->orWhere(DB::raw("LOWER(CONCAT(crm_vehicles.plate_no,'(',crm_vehicles.new_sticker_no,')'))"), 'like', $likeKeyword);
                        });
                        // commented since this request is denied
                        // ->whereRaw('crm_vehicles.sticker_date = ?', [date('Y')]);
                    })  
                    ->orWhere(function ($q) use ($likeKeyword) {
                        $q->where(function ($query) use ($likeKeyword) {
                            $query->whereRaw('LOWER(blk_lot) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(street) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(building_name) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(subdivision_village) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(hoa) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(city) LIKE ?', [$likeKeyword])
                                ->orWhereRaw('LOWER(zipcode) LIKE ?', [$likeKeyword]);
                        })->orWhere(function ($query) use ($likeKeyword) {
                            $query->where(function ($q) use ($likeKeyword) {
                                // block lot + street
                                $q->whereRaw('LOWER(CONCAT(blk_lot, ", ", street)) LIKE ?', [$likeKeyword])
                                    ->orWhereRaw('LOWER(CONCAT(blk_lot, " ", street)) LIKE ?', [$likeKeyword]);
                            })->orWhere(function ($q) use ($likeKeyword) {
                                // block lot + street + building name + subdivision village + hoa + city + zipcode
                                $q->whereRaw('LOWER(CONCAT(blk_lot, ", ", street, ", ", building_name, ", ", subdivision_village, ", ", hoa, ", ", city, ", ", zipcode)) LIKE ?', [$likeKeyword])
                                    ->orWhereRaw('LOWER(CONCAT(blk_lot, " ", street, " ", building_name, " ", subdivision_village, " ", hoa, " ", city, " ", zipcode)) LIKE ?', [$likeKeyword]);
                            });
                        });
                    });
            });
            
        } else {
            $crms->whereRaw('false');
        }

        return DataTables::eloquent($crms)
            ->editColumn('address', function (CrmMain $crm) {
                if ($crm->blk_lot && $crm->street) {
                    return $crm->blk_lot . ', ' . $crm->street . ($crm->building_name ? ', ' . $crm->building_name : '') . ($crm->subdivision_village ? ', ' . $crm->subdivision_village : '') . ($crm->hoa ? ', ' . $crm->hoa : '') . ($crm->city ? ', ' . $crm->city : '') . ($crm->zipcode ? ', ' . $crm->zipcode : '');
                } else {
                    return $crm->address;
                }
            })
            ->editColumn('status', function (CrmMain $crm) {
                if ($crm->status == 1) {
                    return '<span class="badge text-bg-success">Active</span>';
                } elseif ($crm->status == 2) {
                    return '<span class="badge text-bg-warning">Inactive</span>';
                } elseif ($crm->status == 3) {
                    return '<span class="badge text-bg-danger">Suspended</span>';
                } elseif ($crm->status == 4) {
                    return '<span class="badge text-bg-danger">Banned</span>';
                }
            })
            ->addColumn('cname', function (CrmMain $crm) {
                return $crm->firstname . ' ' . $crm->lastname;
            })
            ->addColumn('email', function (CrmMain $crm) {
                return $crm->email;
            })
            ->addColumn('vehicles', function (CrmMain $crm) {
                return $crm->vehicles->map(function ($vehicle) {
                    return ($vehicle->new_sticker_no) ? $vehicle->plate_no . '(' . $vehicle->new_sticker_no . ')' : $vehicle->plate_no . '(' . $vehicle->old_sticker_no . ')';
                })->implode('<br>');
            })
            ->addColumn('stickers', function (CrmMain $crm) {
                return $crm->vehicles->map(function ($vehicle) {
                    $stickerNo = '';
                    if ($vehicle->old_sticker_no) {
                        $stickerNo .= '(OSN) ' . $vehicle->old_sticker_no . PHP_EOL;
                    }
                    if ($vehicle->new_sticker_no) {
                        $stickerNo .= '(NSN) ' . $vehicle->new_sticker_no . PHP_EOL;
                    }
                    return $stickerNo;
                })->implode('');
            })
            ->addColumn('creator', function (CrmMain $crm) {
                return $crm->creator ? $crm->creator->name : '';
            })
            ->addColumn('crm_actions', function (CrmMain $crm) {
                $data = '';
                if ($crm->category_id == 0 && $crm->sub_category_id == 0) {
                    $data .= '<a href="/spc/view-scp-v2/' . $crm->crm_id . '/' . $crm->customer_id . '" class="me-3">
                                                <i class="fas fa-eye" style="color:#B2BEB5; font-size:20px"></i>
                                            </a>';
                } else {
                    $data .= '<a href="/crm/view-details-crm/' . $crm->crm_id . '/' . $crm->customer_id . '" class="me-3">
                                                <i class="fas fa-eye" style="color:#B2BEB5; font-size:20px"></i>
                                            </a>';
                }

                $data .= '<a href="/crm/edit-details-crm/' . $crm->crm_id . '">
                                            <i class="far fa-edit" style="color:#B2BEB5; font-size:20px"></i>
                                        </a>';

                $data .= '<a href="/crm_p2c/' . $crm->crm_id . '">
                    <i class="fa-solid fa-users" style="color:#B2BEB5; font-size:20px"></i>
                </a>
                ';
                
                return $data;
            })
            ->rawColumns(['status', 'crm_actions', 'vehicles'])
            ->make(true);
    }


    public function view_details_old(Request $request, $id, $crm_id)
    {
        $status_red = 0;
        if ($request->query('req') !== NULL) {
            $req = $request->query('req');
        } else {
            $req = 1;
        }

        $year = date('Y');
        $renewed = DB::select('SELECT * FROM `crm_vehicles` WHERE crm_id = "' . $crm_id . '" AND (sticker_date = ' . $year . ' AND sticker_date IS NOT NULL)');

        // $id = $req->crm_id;
        $srs = DB::select('SELECT * FROM `srs_requests` WHERE customer_id = "' . $id . '"');

        // dd($srs);

        $crms =  DB::select('SELECT * FROM `crm_mains` WHERE crm_id = "' . $id . '" LIMIT 1');
        $invoices = DB::select('SELECT a.invoice_no, a.id, a.or_number, b.name, a.created_at, a.crm_id, a.isCancel, a.reason_or_cancel FROM `crm_invoice` a INNER JOIN srs_users b ON a.action_by = b.id WHERE crm_id = ' . $id);

        if ($crms[0]->hoa) {
           $vehicles = DB::select('SELECT a.id as id_vehicle, a.*, b.*, c.id, c.name FROM crm_vehicles a INNER JOIN crm_mains b ON a.crm_id = b.customer_id INNER JOIN srs_hoas c ON b.hoa = c.name WHERE a.crm_id = ?', [$crm_id]);

        } else {
           $vehicles = DB::select('SELECT a.id as id_vehicle, a.*, b.* FROM crm_vehicles a INNER JOIN crm_mains b ON a.crm_id = b.customer_id WHERE a.crm_id = ?', [$crm_id]);

        }

        // if (auth()->user()->email == 'srsadmin@atomitsoln.com') {
              
        //         foreach($vehicles as $vehicle) {
        //              dd($vehicle->vehicle_id);

        //         }
        //     }

       

        $hoas = DB::select('SELECT `name` FROM srs_hoas');
        $categories = DB::select('select * from spc_categories');
        $tags = DB::table('crm_redtag')
            ->where('crm_id', $id)
            ->whereNotIn('status', [1])->orderBy('date_created', 'desc')
            ->get();
        $cities = DB::select('SELECT *FROM crmx_bl_city WHERE `status` = 1');
        $latestResultTag = DB::table('crm_redtag')
            ->where('crm_id', '=', $id)
            ->latest('date_created')
            ->first();
        if ($latestResultTag && $latestResultTag->status != 1) {
            $status_red++;
        }

        foreach ($crms as $category) {

            $category_id = $category->category_id;
            $sub_category_id = $category->sub_category_id;
        }


        // Attempt to fetch category from spc_categories
$cat = DB::select('select * from spc_categories WHERE id = ' . $category_id);

if (empty($cat)) {
    // If not found, switch to srs_categories
    $cat = DB::select('select * from srs_categories WHERE id = ' . $category_id);
}

foreach ($cat as $cate) {
    $cate_name = $cate->name;
    $cat_id = $cate->id;
}

// Attempt to fetch subcategory from spc_subcat
$sub_cat = DB::select('select * from spc_subcat WHERE id = ' . $sub_category_id);

if (empty($sub_cat)) {
    // If not found, switch to srs_sub_categories
    $sub_cat = DB::select('select * from srs_sub_categories WHERE id = ' . $sub_category_id);
}

foreach ($sub_cat as $sub) {
    $sub_name = $sub->name;
}


        if ($req != 1) {

            return view('crm.view-details-crm', ['crms' => $crms, 'vehicles' => $vehicles, 'hoas' => $hoas, 'categories' => $categories, 'cities' => $cities, 'crmid' => $id, 'tags' => $tags, 'customer_id' => $crm_id, 'renewed' => $renewed, 'invoices' => $invoices, 'cate_name' => $cate_name, 'sub_name' => $sub_name, 'req' => $req, 'cat_id' => $cat_id, 'latestResultTag' => $latestResultTag, 'srs' =>  $srs, 'status_red' => $status_red]);
        } else {
            return view('crm.view-details-crm', ['crms' => $crms, 'vehicles' => $vehicles, 'hoas' => $hoas, 'categories' => $categories, 'cities' => $cities, 'crmid' => $id, 'tags' => $tags, 'customer_id' => $crm_id, 'renewed' => $renewed, 'invoices' => $invoices, 'cate_name' => $cate_name, 'sub_name' => $sub_name, 'req' => false, 'cat_id' => $cat_id, 'latestResultTag' => $latestResultTag, 'srs' =>  $srs, 'status_red' => $status_red]);
        }
    }


    public function edit_details($id)
    {

        $crms =  DB::select('SELECT * FROM `crm_mains` WHERE crm_id = ' . $id . ' LIMIT 1');
        $hoas = DB::select('SELECT `name`, `id` FROM srs_hoas');
        $nrs = DB::select('SELECT `name` FROM srs_nr_hoa');

        // found the issue....

        // if (auth()->user()->role_id == '4' || auth()->user()->role_id == '3' || auth()->user()->email == 'miguel.lacupanto@bffhai.com' || auth()->user()->email == 'floyd.tabuzo@bffhai.com' || auth()->user()->email == 'tirso.sulanguit@bffhai.com' || auth()->user()->email == 'chazt.tanyag@bffhai.com' || auth()->user()->email == 'lawenko.max@bffhai.com' || auth()->user()->email == 'jhun.paculan@bffhai.com') {
        //     $categories = DB::select('SELECT  *FROM spc_categories');
        //     $subs = DB::select('SELECT * FROM spc_subcat');
        // } else {
        //     $categories = DB::select('SELECT  *FROM srs_categories');
        //     $subs = DB::select('SELECT * FROM srs_sub_categories');
        // }

        $categories = DB::select('SELECT  *FROM spc_categories');
        $subs = DB::select('SELECT * FROM spc_subcat');

        $cities = DB::select('SELECT *FROM crmx_bl_city WHERE `status` = 1');
        $vehicles =  DB::select('SELECT * FROM `crm_vehicles` WHERE crm_id = ' . $id);

        return view('crm.edit-details-crm', ['crms' => $crms, 'vehicles' => $vehicles, 'hoas' => $hoas, 'cities' => $cities, 'categories' => $categories, 'subs' => $subs, 'nrs'=> $nrs]);
    }

    public function getOldDetails(Request $req)
    {
        $crm = DB::select('select * from crm_mains WHERE crm_id = ' . $req->id);
        
        $cat = DB::select('select * from spc_categories');
        $sub = DB::select('select * from spc_subcat where category_id = ' . $crm[0]->category_id);
        
        if($crm[0]->category_id == 1) {
            $hoas = DB::select('SELECT `name` FROM srs_hoas');
        } else {
            $hoas = DB::select('SELECT `name` FROM srs_nr_hoa');
        }

        $selectedCat = "";
        foreach ($cat as $cate) {
           if($crm[0]->category_id == $cate->id) {
                $selectedCat = $cate->id;
           }
        }

        $selectedSub = "";
        foreach ($sub as $subs) {
            if($crm[0]->sub_category_id == $subs->id) {
                $selectedSub = $subs->id;
            }
        }

        $selectedHoa = "";
        foreach ($hoas as $hoa) {
            if($crm[0]->hoa == $hoa->name) {
                $selectedHoa = $hoa->name;
            }
        }

        return response()->json([
            'cat' => $cat,
            'sub' => $sub,
            'hoas' => $hoas,
            'selectedCat' => $selectedCat,
            'selectedSub' => $selectedSub,
            'selectedHoa' => $selectedHoa
        ]);
    }

    public function getCat(Request $req)
    {
        $cat = DB::select('SELECT * FROM spc_categories');

        return response()->json($cat);
    }

    public function getSubCat(Request $req)
    {
        $sub = DB::select('SELECT * FROM spc_subcat WHERE category_id = ' . $req->id);

        return response()->json($sub);
    }

    public function getHoa(Request $req)
    {
        if($req->id == 1) {
            $hoas = DB::select('SELECT `name` FROM srs_hoas');
        } else {
            $hoas = DB::select('SELECT `name` FROM srs_nr_hoa');
        }

        return response()->json($hoas);
    }

    public function insert_crm(Request $req)
    {

       try{
             // $customer_id = $req->customer_id;
        // if ($req->reason_of_tag == "") {
        //     $reason_of_tag = $req->reason_of_tag;
        // }

        // JOSH PATCH 20-06-2023 FOR .webp extension
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        // $hoa_name = "";

        // if ($req->hoa != 0) {
        //     $hoa_name = $req->hoa;
        // } else {
        //     $hoa_name = "";
        // }

        $first_name = $req->first_name;
        $middle_name = $req->middle_name;
        $last_name = $req->last_name;
        $owned_vehicles = $req->owned_vehicles;
        $blk_lot = $req->blk_lot;
        $street = $req->street;
        $building_name = $req->building_name;
        $subdivision = $req->subdivision;
        $hoa = $req->hoa;
        $city = $req->city;
        $zipcode = $req->zip_code;
        $civil_status = $req->civil_status;
        $nationality = $req->nationality;
        $email = $req->email;
        $main_contact = $req->main_contact;
        $secondary_contact = $req->secondary_contact;
        $tertiary_contact = $req->tertiary_contact;
        $or = $req->or;
        $status = 1;
        $cr = $req->cr;

        $this->validate($req, [
            'or' => 'nullable',
            'cr' => 'nullable',
            'vehicle_pic' => 'nullable'
        ]);

        $fileName2 = "";
        $fileName1 = "";

      // if ($req->hasFile('front_license')) {
      //       $fileName1 = time() . '.' . $req->file('front_license')->extension();
      //       try {
      //           $img = Image::make($req->file('front_license'));
      //           $img->resize(800, 800, function ($constraint) {
      //               $constraint->aspectRatio();
      //               $constraint->upsize();
      //           })->save(public_path('crm_model/drivers_license_front') . '/' . $fileName1);
      //       } catch (\Exception $e) {
      //           $req->file('front_license')->storeAs(public_path('crm_model/drivers_license_front'), $fileName1);
      //       }
      //   }

      //   if ($req->hasFile('back_license')) {
      //       $fileName2 = time() . '.' . $req->file('back_license')->extension();
      //       try {
      //           $img = Image::make($req->file('back_license'));
      //           $img->resize(800, 800, function ($constraint) {
      //               $constraint->aspectRatio();
      //               $constraint->upsize();
      //           })->save(public_path('crm_model/drivers_license_back') . '/' . $fileName2);
      //       } catch (\Exception $e) {
      //           $req->file('back_license')->storeAs(public_path('crm_model/drivers_license_back'), $fileName2);
      //       }
      //   }

        if ($req->hasFile('front_license')) {
            $file1 = $req->file('front_license');
            $fileName1 = time() . rand(1, 100) . '.webp';
            try {
                $img = Image::make($file1);
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode('webp')->save(public_path('crm_model/drivers_license_front') . '/' . $fileName1);
            } catch (\Exception $e) {
                $file1->storeAs(public_path('crm_model/drivers_license_front'), $fileName1);
            }
        }

        if ($req->hasFile('back_license')) {
            $file2 = $req->file('back_license');
            $fileName2 = time() . rand(1, 100) . '.webp';
            try {
                $img = Image::make($file2);
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode('webp')->save(public_path('crm_model/drivers_license_back') . '/' . $fileName2);
            } catch (\Exception $e) {
                $file2->storeAs(public_path('crm_model/drivers_license_back'), $fileName2);
            }
        }

        // $user = DB::table('crm_mains')->where('customer_id', $customer_id)->first();
        $customer_id = $this->getNextId($req->category_id, $req->sub_category_id, $hoa);

        try {
            $crm_id =   DB::table('crm_mains')->insertGetId([
                // 'customer_id' => $customer_id,
                'customer_id' =>  $customer_id,
                'firstname' => $first_name,
                'middlename' => $middle_name,
                'lastname' => $last_name,
                'blk_lot' => $blk_lot,
                'street' => $street,
                'building_name' => $building_name,
                'subdivision_village' => $subdivision,
                'category_id' => $req->category_id,
                'sub_category_id' => $req->sub_category_id,
                'hoa' => $hoa,
                'city' => $city,
                'zipcode' => $zipcode,
                'civil_status' => $civil_status,
                'owned_vehicles' => $owned_vehicles,
                'nationality' =>  $nationality,
                'email' => $email,
                'main_contact' => $main_contact,
                'secondary_contact' => $secondary_contact,
                'tertiary_contact' => $tertiary_contact,
                'status' =>  $status,
                'tin_no'=> $req->tin_no,
                'front_license' => $fileName1,
                'back_license' => $fileName2,
                'created_by' => Auth::id()
            ]);


            $last_insert_id = $crm_id;

            $cr_images = [];
            $or_images = [];
            $name_vehicle_pic_image = [];

            // if ($req->hasfile('cr')) {
            //     foreach ($req->file('cr') as $docu2) {
            //         $name_cr = time() . rand(1, 100) . '.' . $docu2->extension();
            //         try {
            //             $img = Image::make($docu2);
            //             $img->resize(800, 800, function ($constraint) {
            //                 $constraint->aspectRatio();
            //                 $constraint->upsize();
            //             })->save(public_path('crm_model/cr') . '/' . $name_cr);
            //         } catch (\Exception $e) {
            //             $docu2->storeAs(public_path('crm_model/cr'), $name_cr);
            //         }

            //         array_push($cr_images, $name_cr);
            //     }
            // }

            // if ($req->hasfile('or')) {
            //     foreach ($req->file('or') as $docu1) {
            //         $name_or = time() . rand(1, 100) . '.' . $docu1->extension();
            //         try {
            //             $img = Image::make($docu1);
            //             $img->resize(800, 800, function ($constraint) {
            //                 $constraint->aspectRatio();
            //                 $constraint->upsize();
            //             })->save(public_path('crm_model/or') . '/' . $name_or);
            //         } catch (\Exception $e) {
            //             $docu1->storeAs(public_path('crm_model/or'), $name_or);
            //         }

            //         array_push($or_images, $name_or);
            //     }
            // }

            // if ($req->hasfile('vehicle_pic')) {
            //     foreach ($req->file('vehicle_pic') as $docu3) {
            //         $name_vehicle_pic = time() . rand(1, 100) . '.' . $docu3->extension();
            //         try {
            //             $img = Image::make($docu3);
            //             $img->resize(800, 800, function ($constraint) {
            //                 $constraint->aspectRatio();
            //                 $constraint->upsize();
            //             })->save(public_path('crm_model/vehicle_picture') . '/' . $name_vehicle_pic);
            //         } catch (\Exception $e) {
            //             $docu3->storeAs(public_path('crm_model/vehicle_picture'), $name_vehicle_pic);
            //         }

            //         array_push($name_vehicle_pic_image, $name_vehicle_pic);
            //     }
            // }

            // JOSH PATCH 20-06-2023 FOR .webp extension

               if ($req->hasfile('cr')) {
                foreach ($req->file('cr') as $docu2) {
                    $name_cr = time() . rand(1, 100) . '.webp';
                    try {
                        $img = Image::make($docu2);
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('webp')->save(public_path('crm_model/cr') . '/' . $name_cr);
                    } catch (\Exception $e) {
                        $docu2->storeAs(public_path('crm_model/cr'), $name_cr);
                    }

                    array_push($cr_images, $name_cr);
                }
            }

            if ($req->hasfile('or')) {
                foreach ($req->file('or') as $docu2) {
                    $name_or = time() . rand(1, 100) . '.webp';
                    try {
                        $img = Image::make($docu2);
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('webp')->save(public_path('crm_model/or') . '/' . $name_or);
                    } catch (\Exception $e) {
                        $docu2->storeAs(public_path('crm_model/cr'), $name_or);
                    }

                    array_push($or_images, $name_or);
                }
            }


            if ($req->hasfile('vehicle_pic')) {
                foreach ($req->file('vehicle_pic') as $docu2) {
                    $name_vehicle_pic = time() . rand(1, 100) . '.webp';
                    try {
                        $img = Image::make($docu2);
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('webp')->save(public_path('crm_model/vehicle_picture') . '/' . $name_vehicle_pic);
                    } catch (\Exception $e) {
                        $docu2->storeAs(public_path('crm_model/vehicle_picture'), $name_vehicle_pic);
                    }

                    array_push($name_vehicle_pic_image, $name_vehicle_pic);
                }
            }



            for ($i = 0; $i < count($req->plate); $i++) {
                if($req->plate[$i] == null && $req->color[$i] == "---" && $req->brand[$i] == "---" && $req->orID[$i] == null && $req->crID[$i] == null && $req->vehicle_series[$i] == null && $req->year_model[$i] == null && $req->sticker_no[$i] == null && $req->sticker_year[$i] == null && $req->type[$i] == "---") {
                    continue;
                }

                $crm_id = DB::table('crm_vehicles')
                    ->insert(array(
                        'plate_no' => Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->plate[$i]))),
                        'color' =>  $req->color[$i],
                        'brand' => $req->brand[$i],
                        'orID' => $req->orID[$i],
                        'crID' => $req->crID[$i],
                        'series' => $req->vehicle_series[$i],
                        'year_model' => $req->year_model[$i],
                        'old_sticker_no' => $req->sticker_no[$i],
                        'old_sticker_year' => $req->sticker_year[$i],
                        'type' => $req->type[$i],
                        'req1' => (isset($or_images[$i])) ? $or_images[$i] : NULL,
                        'cr' => (isset($cr_images[$i])) ? $cr_images[$i] : NULL,
                        'vehicle_picture' => (isset($name_vehicle_pic_image[$i])) ? $name_vehicle_pic_image[$i] : NULL,
                        'crm_id' =>  $customer_id,
                        'assoc_crm' => 1
                    ));

                $this->insertLogVehicles('Inserted CRM Vehicles, CRM ID ' . $customer_id . ', plate_no: ' . $req->plate[$i]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Successfully Added');
       }catch(\Exception $e)
       {
              return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Duplicate Record Found.');
       }
    }

    public function insert_redtag(Request $req)
    {
        $red_tag = DB::table('crm_redtag')

            ->insert(array(
                'description' => $req->reason_of_tag,
                'action_by' => Auth::user()->name,
                'status' => 0,
                'crm_id' => $req->crm_id,
            ));

        $this->insertLogCrm('Inserted Red Tag, description ' . $req->reason_of_tag . ', CRMID ' . $req->crm_id);

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Successfully Added');
    }

    public function update_status_customer($status, $crm_id)
    {

        $crm =   $crm = DB::table('crm_mains')
            ->where('crm_id', $crm_id)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array('status' => $status));

        $this->insertLogCrm('Updated CRM, CRM status to ' . $status . ', CRMID ' . $crm_id);


        return response()->json($crm);
    }

    public function get_crm($id)
    {
        $crm = DB::select('select * from crm_mains WHERE crm_id = ' . $id);

        return response()->json($crm);
    }

    public function deleteTag(Request $req)
    {
        $delete = DB::table('crm_redtag')->where('id', $req->id)->update(['status' => 1, 'deleted_by' => Auth::user()->name]);

        $this->insertLogCrm('Deleted Red Tag, Red Tag ID ' . $req->id);

        return response()->json($delete);
    }

    // public function update_crm(Request $req)
    // {
    //     $count = 0;

    //     if ($req->reason_of_tag == NULL) {
    //         $count = 0;
    //     } else {
    //         $count = 1;
    //     }

    //     $crm_id = $req->crm_id;
    //     $reason_of_tag = $req->reason_of_tag;
    //     $fname = $req->fname;
    //     $mname = $req->mname;
    //     $lname = $req->lname;
    //     $owned_vehicles = $req->owned_vehicles;
    //     $address = $req->address;
    //     $blk_lot = $req->blk_lot;

    //     $crm = DB::table('crm_mains')
    //         ->where('crm_id', $crm_id)
    //         ->limit(1)
    //         ->update(array('firstname' => $fname, 'middlename' => $mname, 'lastname' => $lname, 'blk_lot' => $blk_lot, 'address' => $address, 'owned_vehicles' => $owned_vehicles, 'reason_of_tag' => $reason_of_tag, 'red_tag' => $count));

    //     return redirect()
    //         ->back()
    //         ->withInput()
    //         ->with('success', 'Updated Successfully');
    // }


    public function add_vehicle(Request $req)
    {
        try{
                // JOSH PATCH 20-06-2023 FOR .webp extension
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        $cr_images = [];
        $name_vehicle_pic_image = [];
        $or_images = [];

       // if ($req->hasfile('cr')) {
       //      foreach ($req->file('cr') as $docu2) {
       //          $name_cr = time() . rand(1, 100) . '.' . $docu2->extension();
       //          try {
       //              $img = Image::make($docu2);
       //              $img->resize(800, 800, function ($constraint) {
       //                  $constraint->aspectRatio();
       //                  $constraint->upsize();
       //              })->save(public_path('crm_model/cr') . '/' . $name_cr);
       //          } catch (\Exception $e) {
       //              $docu2->storeAs(public_path('crm_model/cr'), $name_cr);
       //          }

       //          array_push($cr_images, $name_cr);
       //      }
       //  }

       //  if ($req->hasfile('or')) {
       //      foreach ($req->file('or') as $docu1) {
       //          $name_or = time() . rand(1, 100) . '.' . $docu1->extension();
       //          try {
       //              $img = Image::make($docu1);
       //              $img->resize(800, 800, function ($constraint) {
       //                  $constraint->aspectRatio();
       //                  $constraint->upsize();
       //              })->save(public_path('crm_model/or') . '/' . $name_or);
       //          } catch (\Exception $e) {
       //              $docu1->storeAs(public_path('crm_model/or'), $name_or);
       //          }

       //          array_push($or_images, $name_or);
       //      }
       //  }

       //  if ($req->hasfile('vehicle_pic')) {
       //      foreach ($req->file('vehicle_pic') as $docu3) {
       //          $name_vehicle_pic = time() . rand(1, 100) . '.' . $docu3->extension();
       //          try {
       //              $img = Image::make($docu3);
       //              $img->resize(800, 800, function ($constraint) {
       //                  $constraint->aspectRatio();
       //                  $constraint->upsize();
       //              })->save(public_path('crm_model/vehicle_picture') . '/' . $name_vehicle_pic);
       //          } catch (\Exception $e) {
       //              $docu3->storeAs(public_path('crm_model/vehicle_picture'), $name_vehicle_pic);
       //          }

       //          array_push($name_vehicle_pic_image, $name_vehicle_pic);
       //      }
       //  }
            // JOSH PATCH 20-06-2023 FOR .webp extension
         if ($req->hasfile('cr')) {
                foreach ($req->file('cr') as $docu2) {
                    $name_cr = time() . rand(1, 100) . '.webp';
                    try {
                        $img = Image::make($docu2);
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('webp')->save(public_path('crm_model/cr') . '/' . $name_cr);
                    } catch (\Exception $e) {
                        $docu2->storeAs(public_path('crm_model/cr'), $name_cr);
                    }

                    array_push($cr_images, $name_cr);
                }
            }

            if ($req->hasfile('or')) {
                foreach ($req->file('or') as $docu2) {
                    $name_or = time() . rand(1, 100) . '.webp';
                    try {
                        $img = Image::make($docu2);
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('webp')->save(public_path('crm_model/or') . '/' . $name_or);
                    } catch (\Exception $e) {
                        $docu2->storeAs(public_path('crm_model/cr'), $name_or);
                    }

                    array_push($or_images, $name_or);
                }
            }



            if ($req->hasfile('vehicle_pic')) {
                foreach ($req->file('vehicle_pic') as $docu2) {
                    $name_vehicle_pic = time() . rand(1, 100) . '.webp';
                    try {
                        $img = Image::make($docu2);
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->encode('webp')->save(public_path('crm_model/vehicle_picture') . '/' . $name_vehicle_pic);
                    } catch (\Exception $e) {
                        $docu2->storeAs(public_path('crm_model/vehicle_picture'), $name_vehicle_pic);
                    }

                    array_push($name_vehicle_pic_image, $name_vehicle_pic);
                }
            }


        for ($i = 0; $i < count($req->plate); $i++) {

            $crm_id = DB::table('crm_vehicles')

                ->insert(array(
                    'plate_no' =>  Str::upper(trim(preg_replace('/\s+/', '', $req->plate[$i]))),
                    'color' =>  $req->color[$i],
                    'brand' => $req->brand[$i],
                    'series' => $req->vehicle_series[$i],
                    'year_model' => $req->year_model[$i],
                    // 'orID' => $req->orID[$i],
                    // 'crID' => $req->crID[$i],
                    'old_sticker_year' => $req->sticker_year[$i],
                    'old_sticker_no' => $req->sticker_no[$i],
                    'type' => $req->type[$i],
                    'req1' => (isset($or_images[$i])) ? $or_images[$i] : NULL,
                    'cr' => (isset($cr_images[$i])) ? $cr_images[$i] : NULL,
                    'vehicle_picture' => (isset($name_vehicle_pic_image[$i])) ? $name_vehicle_pic_image[$i] : NULL,
                    'crm_id' => $req->crm_id,
                    'assoc_crm' => 1
                ));

            $this->insertLogVehicles('Inserted CRM Vehicles, CRM ID ' . $req->crm_id . ', plate_no: ' . $req->plate[$i]);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Added Successfully');
        }catch(\Exception $e)
        {
            return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Duplicate Record found.');
        }
    }
    public function loadVehicles(Request $req)
    {
        $year = date('Y');

        if ($req->selectedVehicles) {
            $str = implode(',', $req->selectedVehicles);
            // return response()->json($str);
            return response()->json(DB::select('SELECT * FROM `crm_vehicles` WHERE crm_id = "' . $req->id . '"  AND id NOT IN (' . $str . ')' .  ' AND (sticker_date <  ' . $year . ' OR sticker_date IS NULL)'));
        } else {
            return response()->json(DB::select('SELECT * FROM `crm_vehicles` WHERE crm_id = "' . $req->id . '" AND (sticker_date < ' . $year . ' OR sticker_date IS NULL)'));
        }
    }

    public function fetchVehicleDetails(Request $req)
    {
        return response()->json(DB::select('SELECT * FROM `crm_vehicles` WHERE id = ' . $req->id . ''));
    }

    public function loadComputation(Request $req)
    {

        $user = DB::select('SELECT category_id, sub_category_id FROM crm_mains WHERE customer_id = "' . $req->id . '"');
        $cat_id = $user[0]->category_id;
        $sub_cat = $user[0]->sub_category_id;
        $count = $req->vehicleCount;
        // $j = "SELECT vehicle_type, car_price, motor_price, category_id FROM `srs_prices` WHERE category_id = ' . $cat_id . ' AND sub_category_id = ' . $sub_cat . ' AND vehicle_type = ' . $req->vehicle_type . ' AND min <= ' . $count . ' AND (max >= ' . $count . ' OR max IS NULL) LIMIT 1";
        // return response()->json($j);

        // dd($cat_id ." ". $sub_cat);

        // $vehicle = DB::select('SELECT `type` FROM crm_vehicles WHERE crm_id = "' . $req->id . '"');
        if ($cat_id == 1) {

            return response()->json(DB::select('SELECT  car_price, motor_price, category_id FROM `srs_prices` WHERE category_id = ' . $cat_id . ' AND sub_category_id = ' . $sub_cat . ' AND min <= ' . $count . ' AND (max >= ' . $count . ' OR max IS NULL) LIMIT 1'));
        } else {

            return response()->json(DB::select('SELECT vehicle_type, car_price, motor_price, category_id FROM `srs_prices` WHERE category_id = ' . $cat_id . ' AND sub_category_id = ' . $sub_cat . ' AND vehicle_type = "' . $req->vehicle_type . '" AND min <= ' . $count . ' AND (max >= ' . $count . ' OR max IS NULL) LIMIT 1'));
        }
    }

    public function editRedTag($id)
    {
        return response()->json(DB::select('SELECT * FROM `crm_redtag` WHERE id = ' . $id));
    }

    public function update_red_tag(Request $req)
    {
        $crm = DB::table('crm_redtag')
            ->where('id',  $req->red_tag_id)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array('description' => $req->red_tag_remarks));

        $this->insertLogCrm('Updated Red Tag, description to ' . $req->red_tag_remarks . ', Reg Tag ID ' . $req->red_tag_id);

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Edit Successfully');
    }

    public function update_crm_main(Request $req)
    {
        $url = explode('/', $req->previous_url);
        
        // look for "view-scp-v2" from the formatted url
        $isFromBilling = in_array('view-scp-v2', $url);

        $crm = DB::table('crm_mains')
            ->where('crm_id',  $req->crm_id)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array('firstname' => $req->first_name, 'middlename' => $req->middle_name, 'lastname' => $req->last_name, 'blk_lot' => $req->blk_lot, 'street' => $req->street, 'building_name' => $req->building_name, 'subdivision_village' => $req->subdivision_village, 'hoa' => $req->hoa, 'city' => $req->city, 'zipcode' => $req->zip_code, 'civil_status' => $req->civil_status, 'nationality' => $req->nationality, 'main_contact' => $req->main_contact, 'secondary_contact' => $req->secondary_contact, 'tertiary_contact' => $req->tertiary_contact, 'category_id' => $req->cate_id, 'sub_category_id' => $req->sub_id, 'email' => $req->email));

        $this->insertLogCrm('Updated CRM, CRMID ' . $req->customer_id . ', firstname: ' . $req->first_name . ', middlename: ' . $req->middle_name . ', lastname: ' . $req->last_name . ', blk_lot: ' . $req->blk_lot . ', street: ' . $req->street . ', building_name: ' . $req->building_name . ', subdivision_village: ' . $req->subdivision_village . ', hoa: ' . $req->hoa . ', city: ' . $req->city . ', zipcode: ' . $req->zip_code . ', civil_status: ' . $req->civil_status . ', nationality: ' . $req->nationality . ', main_contact: ' . $req->main_contact . ', secondary_contact: ' . $req->secondary_contact . ', tertiary_contact: ' . $req->tertiary_contact);

        if ($isFromBilling) {
            return redirect()->to('/spc/view-scp-v2/' . $url[5] . '/' . $url[6])
            ->with('success', 'Edit Successfully');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('success', 'Edit Successfully');
        }
    }

    public static function getAmount($crm_id, $count, $type)
    {

        $user = DB::select('SELECT category_id, sub_category_id FROM crm_mains WHERE customer_id = "' . $crm_id . '"');
        $cat_id = $user[0]->category_id;
        $sub_cat = $user[0]->sub_category_id;
        $count = $count;
        $price = 0;
        $vehicle = DB::select('SELECT `type` FROM crm_vehicles WHERE `type` = "' . $type . '"');
        // dd($vehicle[0]->type);
        if ($cat_id == 1) {

            $sub = DB::select('SELECT  car_price, motor_price, category_id FROM `srs_prices` WHERE category_id = ' . $cat_id . ' AND sub_category_id = ' . $sub_cat . ' AND min <= ' . $count . ' AND (max >= ' . $count . ' OR max IS NULL) LIMIT 1');
        } else {

            $sub = DB::select('SELECT vehicle_type, car_price, motor_price, category_id FROM `srs_prices` WHERE category_id = ' . $cat_id . ' AND sub_category_id = ' . $sub_cat . ' AND vehicle_type = "' . $vehicle[0]->type . '" AND min <= ' . $count . ' AND (max >= ' . $count . ' OR max IS NULL) LIMIT 1');
        }

        if ($cat_id == 1) {
            $price = $sub[0]->car_price;
        } else {
            $price = $sub[0]->motor_price;
        }


        switch ($vehicle[0]->type) {
            case "Motorcycle":
                $price = $sub[0]->motor_price;

                break;
            case "Car":
                $price = $sub[0]->car_price;

                break;
            default:
                $price = $sub[0]->car_price;
        }



        return $price;
    }

    public function get_vehicles($id)
    {
        $vehicles = DB::select('select * from crm_vehicles WHERE id = ' . $id);

        return response()->json($vehicles);
    }

    public function update_vehicles(Request $req)
    {
       try{
         $fileName1 = "";
        $fileName3 = "";
        $fileName2 = "";

        $v = DB::table('crm_vehicles')->where('id', $req->vehicle_id)->first();

        if ($fileName1 == NULL) {
            $fileName1 = $v->cr;
        }
        if ($fileName2 == NULL) {
            $fileName2 = $v->req1;
        }
        if ($fileName3 == NULL) {
            $fileName3 = $v->vehicle_picture;
        }

        if ($req->hasfile('cr')) {
            $fileName1 = time() . '.' . $req->cr->extension();

            $req->cr->move(public_path('crm_model/cr'), $fileName1);
        }
        if ($req->hasfile('or')) {
            $fileName2 = time() . '.' . $req->or->extension();

            $req->or->move(public_path('crm_model/or'), $fileName2);
        }
        if ($req->hasfile('vehicle_pic')) {
            $fileName3 = time() . '.' . $req->vehicle_pic->extension();

            $req->vehicle_pic->move(public_path('crm_model/vehicle_picture'), $fileName3);
        }

          $vehicles = DB::table('crm_vehicles')
            ->where('id',  $req->vehicle_id)->first();

        // $crm = DB::table('crm_vehicles')
        //     ->where('id',  $req->vehicle_id)  // find your user by their email
        //     ->limit(1)  // optional - to ensure only one record is updated.
        //     ->update(array('plate_no' => Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->plate))), 'brand' => $req->brand, 'series' => $req->vehicle_series, 'year_model' => $req->year_model, 'color' => $req->color, 'type' => $req->type, 'cr' => $fileName1, 'req1' => $fileName2, 'vehicle_picture' => $fileName3,'orID'=>Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->orID))),'crID'=> Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->crID)))));

        $crm = DB::table('crm_vehicles')
            ->where('id',  $req->vehicle_id)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array('plate_no' => Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->plate))), 'brand' => $req->brand, 'series' => $req->vehicle_series, 'year_model' => $req->year_model, 'color' => $req->color, 'type' => $req->type, 'cr' => $fileName1, 'req1' => $fileName2, 'vehicle_picture' => $fileName3));


             $this->insertLogVehicles('Updated VHID: ' .  $req->vehicle_id . ' CRM ID:' . $v->crm_id  . ', plate_no: Fr[' . $vehicles->plate_no . '] To[' . Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->plate))) . '], brand: Fr[' . $vehicles->brand . '] To[' . $req->brand . '], series: Fr[' .  $vehicles->series . '] To[' . $req->vehicle_series . '], year_model: Fr[' . $vehicles->year_model . '] To [' . $req->year_model . '], color: Fr[' . $vehicles->color . '] To[' . $req->color . '], type: Fr[' . $vehicles->type . '] To[' . $req->type . '], CRID: Fr[' . $vehicles->crID . '] To[' . Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->crID))) . '] , ORID: Fr[' . $vehicles->orID . '] To[' . Str::upper(trim(preg_replace('/[\s\-\(\)]+/', '', $req->orID))) . '] cr: ' . $fileName1 . ', req1: ' . $fileName2 . ', vehicle_picture: ' . $fileName3);
        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Edit Successfully');
       }catch(\Exception $e){
           return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Duplicate Data Found.');
       }
    }

    public function update_status_vehicle($status, $v_id)
    {

        $crm =   $crm = DB::table('crm_vehicles')
            ->where('id', $v_id)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array('status' => $status));

        $this->insertLogVehicles('Updated CRM Vehicles, CRM Vehicle ID ' . $v_id . ', status to ' . $status);

        return response()->json($crm);
    }
}
