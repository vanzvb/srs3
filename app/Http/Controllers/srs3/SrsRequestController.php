<?php

namespace App\Http\Controllers\srs3;

use App\Exports\CrmRedTagExport;
use App\Exports\SrsRequestsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\SrsRequestRequest;
use App\Mail\RequestApproved;
use App\Mail\RequestRejected;
use App\Mail\RequestSubmitted;
use App\Mail\RequestSubmittedRequestor;
use App\Models\CrmInvoice;
use App\Models\CrmMain;
use App\Models\CrmRedtag;
use App\Models\CrmVehicle;
use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXICivilStatus;
use App\Models\CRMXI3_Model\CRMXITempAddress;
use App\Models\LogSrsHist;
use App\Models\SPCCategory;
use App\Models\SPCSubCat;
use App\Models\SrsAppointment;
use App\Models\SrsApptTimeslot;
use App\Models\SrsCategory;
use App\Models\SrsHoa;
use App\Models\SrsNrHoa;
use App\Models\SrsRequestsArchive;
use App\Models\SrsRequirement;
use App\Models\SrsRequirementFile;
use App\Models\SrsSubCategory;
use App\Models\SrsUser;
use App\Models\SrsVehicle;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use PDF;
use Yajra\DataTables\Facades\DataTables;

// Removed
// use App\Models\SrsRequest;
// use App\Models\SrsRequestStatus;

// SRS 3 New Models
use App\Models\SRS3_Model\SrsRequest;
use App\Models\SRS3_Model\SrsRequestStatus;

class SrsRequestController extends Controller
{
    private function insertLogSrs($action)
    {
        $log = new LogSrsHist();
        $log->action_by = auth()->user()->name;
        $log->action = $action;
        $log->ip_address = request()->ip();
        $log->save();
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

    private function sendRequestApprovedEmail($srsRequest)
    {
        $srn = Crypt::encrypt($srsRequest->request_id);
        $url = URL::temporarySignedRoute('request.appointment', now()->addDays(3), ['key' => $srn]);

        // Mail::to($srsRequest->email)->send(new RequestApproved($srsRequest, $url));
        dispatch(new \App\Jobs\SendApprovedNotificationJob($srsRequest, $srsRequest->email, $url));
    }

    public function list()
    {

        // $this->authorize('access', SrsRequest::class);
        // $requests = SrsRequest::where('status', 0)
        //                         ->orWhere('status', 1)
        //                         ->whereDoesntHave('statuses', function ($query) {
        //                             $query->where('status_id', 1);
        //                         })
        //                         ->orderBy('created_at')
        //                         ->get();
        // $requests = SrsRequest::with('statuses')
        //     ->orderBy('created_at')
        //     ->get();
        // dd('test');

        // Generate a temporary ID with the format yyyy-mm-dd-hh:mm:ss:ms

        return view('srs3.admin.requests');
    }

    public function report()
    {
        $users = SrsUser::all()
            ->whereIn('role_id', ['1', '2']);

        //JOSH PATCH ADDING HOA QUERY MODEL 27-04-2023
        $hoas = SrsHoa::orderBy('name')->get();
        //END JOSH PATCH ADDING HOA QUERY MODEL
        $this->authorize('generateReport', SrsRequest::class);

        return view('srs.admin.report', ['users' => $users, 'hoas' => $hoas]);
    }

    public function dashboard()
    {
        // $customersCount = CrmMain::where('crm_id', '!=', 7)->count();
        // $invoicesCount = CrmInvoice::where('crm_id', '!=', 7)->count();
        // $openSrsCount = SrsRequest::where('status', '<', 4)->count();
        // $closedSrsCount = SrsRequest::where('status', 4)->count();

        // return view('dashboard', compact('customersCount', 'invoicesCount', 'openSrsCount', 'closedSrsCount'));
        // Check if a record exists for the current day in dashboard_table
        // $dashboard = DB::table('dashboard_table')
        //     ->whereDate('date', '=', now()->toDateString())
        //     ->first();

        // If no record exists for the current day, retrieve the record for yesterday's date
        // if (!$dashboard) {
        //     $dashboard = DB::table('dashboard_table')
        //         ->whereDate('date', '=', now()->subDay()->toDateString())
        //         ->first();
        // }

        // If still no record found, you may handle it as needed, maybe set default values
        // if (!$dashboard) {
        //     $dashboard = (object) [
        //         'customer_count' => 0,
        //         'invoices_count' => 0,
        //         'open_srs_count' => 0,
        //         'closed_srs_count' => 0
        //     ];
        // }

        return view('dashboard');
    }

    public function dashboardCron()
    {
        try {
            $customersCount = CrmMain::where('crm_id', '!=', 7)->count();
            $invoicesCount = CrmInvoice::where('crm_id', '!=', 7)->count();
            $openSrsCount = SrsRequest::where('status', '<', 4)->count();
            $closedSrsCount = SrsRequest::where('status', 4)->count();

            $dashboard = DB::table('dashboard_table')
                ->whereDate('date', '=', now()->toDateString())
                ->first();

            if (!$dashboard) {
                DB::transaction(function ()
                use ($customersCount, $invoicesCount, $openSrsCount, $closedSrsCount) {
                    DB::table('dashboard_table')
                        ->insert([
                            'date' => now(),
                            'customer_count' => $customersCount,
                            'invoices_count' => $invoicesCount,
                            'open_srs_count' => $openSrsCount,
                            'closed_srs_count' => $closedSrsCount
                        ]);
                });

                Log::info('Dashboard Count Inserted');
            } else {
                DB::transaction(function ()
                use ($customersCount, $invoicesCount, $openSrsCount, $closedSrsCount) {
                    DB::table('dashboard_table')
                        ->whereDate('date', '=', now()->toDateString())
                        ->update([
                            'date' => now(),
                            'customer_count' => $customersCount,
                            'invoices_count' => $invoicesCount,
                            'open_srs_count' => $openSrsCount,
                            'closed_srs_count' => $closedSrsCount
                        ]);
                });

                Log::info('Dashboard Count Updated');
            }

            return response()->json(['message' => 'Dashboard count updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Dashboard count not updated'], 500);
        }
    }

    public function index()
    {
        return view('srs.request.test');
    }

    public function create()
    {
        // $categories = SPCCategory::where('status', 1)->get();
        // Account Info
        $categories = CRMXICategory::select('id', 'name')->get();

        $hoas = DB::select('SELECT * FROM crmxi3_hoas');

        $subcats = DB::select('SELECT * FROM get_subcat');

        $hoatypes = DB::select('SELECT * FROM get_hoa_types');

        $cities = DB::table('crmx_bl_city')->where('status', 1)->get();

        $civilStatus = DB::table('crmxi3_civil_status')->get();

        $nationalities = DB::table('crmxi3_nationalities')->get();

        // Vehicle

        $currentYear = date('Y');
        $years = range($currentYear, 1975);

        $tempId = date('Y-m-d-H-i-s') . '-' . sprintf('%03d', (int)(microtime(true) * 1000) % 1000);

        return view('srs3.request.create', compact('categories', 'subcats', 'hoatypes', 'cities', 'hoas', 'civilStatus', 'nationalities', 'tempId','years'));
        // return view('srs.request.create', compact('cities'));
    }

    public function store(SrsRequestRequest $request)
    {

        $data = $request->validated();
        dd($request);
        // Start a transaction
        DB::beginTransaction();

        try {

            // (START) srs3_requests saving 

            $srsRequest = new SrsRequest();
            $srsRequest->request_id = $this->getNextId();
            $srsRequest->category_id = $data['category'];
            $srsRequest->sub_category_id = $data['sub_category'];
            $srsRequest->first_name = $data['first_name'];
            $srsRequest->last_name = $data['last_name'];
            $srsRequest->middle_name = $data['middle_name'];

            // Added in srs3
            $srsRequest->account_type = $request->account_type;
            $srsRequest->civil_status = $request->civil_status;
            $srsRequest->nationality = $request->nationality;
            $srsRequest->tin_no = $request->tin_no;
            $srsRequest->secondary_contact = $request->secondary_contact_no;
            $srsRequest->tertiary_contact = $request->tertiary_contact_no;
    
            if (isset($data['hoa'])) {
                if ($srsRequest->category_id == 1) {
                    $srsRequest->hoa_id = $data['hoa'];
                } else if ($srsRequest->category_id == 2) {
                    $srsRequest->nr_hoa_id = $data['hoa'];
                }
            }
            // $srsRequest->hoa_id = isset($data['hoa']) ? $data['hoa'] : NULL;
            $srsRequest->contact_no = $data['contact_no'];
            $srsRequest->email = $data['email'];
    
    
            $encoded_image = explode(",", $data['signature'])[1];
            $decoded_image = base64_decode($encoded_image);
            // $signatureFile = date('ymd') . '_' . uniqid() . '_' . date('His') . '.png';
            $signatureFile = date('ymd') . '_' . uniqid() . '_' . date('His') . '.webp';
    
            $srsRequest->signature = $signatureFile;
            $signatureImg = Image::make($decoded_image)->encode('webp');
    
    
            $srsRequest->created_at = now();
            $srsRequest->updated_at = now();
    
            // $path = 'bffhai/' . $srsRequest->created_at->format('Y') . '/' . ($srsRequest->hoa_id ?: '0') . '/' . $this->getCategoryName($srsRequest->category_id) . '/' . $srsRequest->created_at->format('m') . '/' . $srsRequest->first_name . '_' . $srsRequest->last_name;
            $path = 'bffhai/' . $srsRequest->created_at->format('Y') . '/' . ($srsRequest->hoa_id ?: '0') . '/' . $this->getCategoryName($srsRequest->category_id) . '/' . $srsRequest->created_at->format('m') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name));
            $filePath = $srsRequest->created_at->format('Y-m-d') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name)) . '/' . ($srsRequest->hoa_id ?: '0') . '/' . $srsRequest->category_id;
    
            Storage::put($path . '/' . $srsRequest->signature, $signatureImg);

            // (END) srs3_requests saving

            $generateRequestID = $srsRequest->request_id;
        
            // (START) crmxi3_temp_address saving
            
            $addresses = json_decode($request->input('addresses'), true);
            // For Tracking of Newly saved addresses
            $savedAddressIds = [];

            // Loop through each address and save it
            foreach ($addresses as $addressData) {
                // Assuming you have an Address model
                $address = new CRMXITempAddress();
                $address->request_id = $generateRequestID;
                $address->block = $addressData['block'];
                $address->lot = $addressData['lot'];
                $address->house_number = $addressData['houseNumber'];
                $address->street = $addressData['street_modal'];
                $address->building_name = $addressData['building_name_modal'];
                $address->subdivision_village = $addressData['subdivision_village_modal'];
                $address->city = $addressData['city_modal'];
                $address->zipcode = $addressData['zipcode_modal'];
                $address->category_id = $addressData['category_modal'];
                $address->sub_category_id = $addressData['sub_category_modal'];
                $address->hoa = $addressData['HOA_modal'];
                $address->hoa_type = $addressData['member_type_modal'];
                $address->save();

                // Store the saved address ID
                $savedAddressIds[] = $address->id;
            }
            
            // dd($savedAddressIds);

            DB::commit();
            // (END) crmxi3_temp_address saving

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }


        // $count = 0;
        // $vehicles = [];

        // foreach ($data['plate_no'] as $item1) {
        //     // $vehicle = new SrsVehicle();
        //     $vehicle = new CrmVehicle();
        //     $vehicle->srs_request_id = $srsRequest->request_id;
        //     $vehicle->req_type = $data['req_type'][$count];

        //     if ($item1) {
        //         $vehicle->plate_no = strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $item1))));
        //     }

        //     if ($data['brand'][$count]) {
        //         $vehicle->brand = strip_tags($data['brand'][$count]);
        //     }

        //     if ($data['series'][$count]) {
        //         $vehicle->series = strip_tags($data['series'][$count]);
        //     }

        //     if ($data['year_model'][$count]) {
        //         $vehicle->year_model = strip_tags($data['year_model'][$count]);
        //     }

        //     if (isset($data['sticker_no'])) {
        //         if ($data['sticker_no'][$count]) {
        //             $vehicle->old_sticker_no = strip_tags($data['sticker_no'][$count]);
        //         }
        //     }

        //     if ($data['v_color'][$count]) {
        //         $vehicle->color = strip_tags($data['v_color'][$count]);
        //     }

        //     if ($data['v_type'][$count]) {
        //         $vehicle->type = strip_tags($data['v_type'][$count]);
        //     }

        //     if (isset($data['or'])) {
        //         if (isset($data['or'][$count])) {
        //             $vehicle->req1 = $this->storeFile($path, $data['or'][$count]);
        //             $vehicle->or_path = $vehicle->req1 . '/' . $filePath;
        //         } else {
        //             $vehicle->req1 = '';
        //         }
        //     }

        //     if (isset($data['cr'])) {
        //         if (isset($data['cr'][$count])) {
        //             $vehicle->cr = $this->storeFile($path, $data['cr'][$count]);
        //             $vehicle->cr_path = $vehicle->cr . '/' . $filePath;
        //         } else {
        //             $vehicle->cr = '';
        //         }
        //     }


        //     $count++;
        //     $vehicles[] = $vehicle;
        // }

        

        // if ($vehicles) {
        //     $srsRequest->vehicles()->saveMany($vehicles);
        // }

        $files = [];

        if ($request->has('hoa_endorsement')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['hoa_endorsement']), 2, $filePath);
        }

        if ($request->has('lease_contract')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['lease_contract']), 3, $filePath);
        }

        if ($request->has('tct')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['tct']), 4, $filePath);
        }

        if ($request->has('business_clearance')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['business_clearance']), 5, $filePath);
        }

        if ($request->has('deed_of_assignment')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['deed_of_assignment']), 6, $filePath);
        }

        if ($request->has('proof_of_ownership')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['proof_of_ownership']), 7, $filePath);
        }

        if ($request->has('proof_of_residency')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['proof_of_residency']), 8, $filePath);
        }

        if ($request->has('bffhai_biz_clearance')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['bffhai_biz_clearance']), 9, $filePath);
        }

        if ($request->has('valid_id_other_requirement')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['valid_id_other_requirement']), 10, $filePath);
        }

        if ($request->has('other_documents_2')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['other_documents_2']), 12, $filePath);
        }

        if ($request->has('other_documents_3')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['other_documents_3']), 13, $filePath);
        }

        if ($request->has('nbi_police_clearance')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['nbi_police_clearance']), 14, $filePath);
        }

        if ($request->has('general_information_sheet')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $data['general_information_sheet']), 15, $filePath);
        }

        if ($files) {
            $srsRequest->files()->saveMany($files);
        }

        $srn = Crypt::encrypt($srsRequest->request_id);
        // $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(2), ['key' => $srn]);

        // Mail::to('reymark.cuevo@atomitsoln.com')
        //     ->bcc('lito.tampis@atomitsoln.com')
        //     ->bcc('reymark.cuevo@atomitsoln.com')
        //     ->send(new RequestSubmitted($srsRequest, $url));
        // Mail::to($hoa->email)->send(new RequestSubmitted($srsRequest, $url));

        // Mail::to($srsRequest->email)->queue(new RequestSubmittedRequestor($srsRequest));

        // VB Disabled notif for testing
        // dispatch(new \App\Jobs\SendRequestorNotificationJob($srsRequest, $srsRequest->email));

        $srsRequest->load('hoa');

        if ($srsRequest->hoa && $srsRequest->hoa->type == 0) {
            // $hoaEmails = [
            //     $srsRequest->hoa->emailAdd1
            // ];

            // if ($srsRequest->hoa->emailAdd2) {
            //     $hoaEmails[] = $srsRequest->hoa->emailAdd2;
            // }

            // if ($srsRequest->hoa->emailAdd3) {
            //     $hoaEmails[] = $srsRequest->hoa->emailAdd3;
            // }

            // if ($hoaEmails) {
            //     Mail::to($hoaEmails)->send(new RequestSubmitted($srsRequest, $url));
            // }

            // if ($srsRequest->hoa->emailAdd1) {
            //     $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

            //     dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd1, $url))->delay(now()->addSeconds(10));
            // }

            // if ($srsRequest->hoa->emailAdd2) {
            //     $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd2)]);

            //     dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd2, $url))->delay(now()->addSeconds(12));
            // }

            // if ($srsRequest->hoa->emailAdd3) {
            //     $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd3)]);

            //     dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd3, $url))->delay(now()->addSeconds(14));
            // }
        }


        return back()->with('requestAddSuccess', $srsRequest->request_id);
    }

    public function show(SrsRequest $srsRequest)
    {
        // $this->authorize('access', SrsRequest::class);

        $srsRequest->load(['vehicles', 'files', 'files.requirement']);
        $srsRequest->loadCount(['statuses' => function ($q) {
            $q->where('status_id', 1);
        }]);

        return view('srs.request.show', compact('srsRequest'));
    }

    public function showRequest($id)
    {
        // $this->authorize('access', SrsRequest::class);

        // $validator = Validator::make(['id' => $id], [
        //     'id' => 'required|string|exists:srs_requests,request_id'
        // ]);

        // if ($validator->fails()) {
        //     return back();
        // }

        return redirect()->route('requests')->with('srsNo', $id);
    }

    public function getRequest(Request $request)
    {
        // $this->authorize('access', SrsRequest::class);
        // dd('Hi');
        // $request->validate([
        //     'type' => 'required|integer|boolean'
        // ]);


        if (!$request->type) {
            // $request->validate(['archive_year' => 'integer|date_format:Y']);

            // $tableName = 'srs_requests_archive_'.$request->archive_year;
            $tableName = $request->archive_year;

            if (!Schema::hasTable($tableName)) {
                return abort(403);
            }

            $srsQuery = SrsRequestsArchive::fromTable($tableName);
        } else {
            // VB
            $srsQuery = SrsRequest::query();
            // $srsQuery = Srs3Request::query();
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
            ->findOrFail($request->srs);

        // $vehicles = [];
        // foreach($srsRequest->vehicles as $vehicle) {
        //     $vehicles[] = $vehicle->plate_no.', '.$vehicle->type.', '.$vehicle->brand.', '.$vehicle->series.', '.$vehicle->year_model.', '.$vehicle->color;
        // }

        $vehicles = [];
        foreach ($srsRequest->vehicles as $key => $vehicle) {
            $vehicles[] = '<tr>
                              <td>' . ($key + 1) . '</td>
                              <td>' . ($vehicle->req_type ? 'Renewal' : 'New') . '</td>
                              <td>' . htmlspecialchars($vehicle->old_sticker_no) . '</td>
                              <td>' . htmlspecialchars($vehicle->type) . '</td>
                              <td>' . htmlspecialchars($vehicle->plate_no) . ($vehicle->req_type == 1 && $vehicle->plate_no_remarks ? '<br> <b>[New: ' . $vehicle->plate_no_remarks . ']</b>' : '') . '</td>
                              <td>' . htmlspecialchars($vehicle->brand) . '</td>
                              <td>' . htmlspecialchars($vehicle->series) . '</td>
                              <td>' . htmlspecialchars($vehicle->year_model) . '</td>
                              <td>' . htmlspecialchars($vehicle->color) . ($vehicle->req_type == 1 && $vehicle->color_remarks ? '<br> <b>[New: ' . $vehicle->color_remarks . ']</b>' : '') . '</td>
                              <td align="center">
                                <a data-value="/srs/uploads/' . $vehicle->or_path . '" data-type="' . (explode('.', $vehicle->req1)[1] == 'pdf' ? 'pdf' : 'img') . '" href="#" class="modal_img">OR</a>
                                <br>
                                <a data-value="' . ($vehicle->cr_from_crm ? 'crm_model/cr/' . $vehicle->cr : '/srs/uploads/' . $vehicle->cr_path) . '" data-type="' . ($vehicle->cr ? (explode('.', $vehicle->cr)[1] == 'pdf' ? 'pdf' : 'img') : 'img') . '" href="#" class="modal_img">CR</a>
                              </td>
                          </tr>';
        }

        // $hoaApproval = $srsRequest->statuses->where('status_id', 2)->first();
        // $adminApproval = $srsRequest->statuses->where('pivot_status_id', 1)->first();

        $files = [];
        foreach ($srsRequest->files as $file) {
            $imgType = explode('.', $file->name)[1] == 'pdf' ? 'pdf' : 'img';
            $files[] = '<a data-value="/srs/uploads/' . $file->req_path . '" data-type="' . $imgType . '" href="#" class="modal_img">' . $file->requirement->description . '</a>';
        }

        $statuses = SrsRequestStatus::with(['requests' => function ($q) use ($srsRequest) {
            $q->withTrashed()
                // VB
                // ->where('srs_requests.request_id', $srsRequest->request_id);
                ->where('srs3_requests.request_id', $srsRequest->request_id);
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
                            ' . $srsRequest->appointment->date->format('M d, Y') . '<br>
                            ' . $srsRequest->appointment->timeslot->time->format('h:i A');

                    if (auth()->user()->can('reset', SrsAppointment::class) && !$srsRequest->invoice) {
                        $cell .= '<br><a data-value="' . $srsRequest->request_id . '" href="#" id="reset_appt_btn">Reset Appointment</a>';
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
        $paymentAction = '';
        $systemNotes = '';
        $genInvoiceAction = '';
        $redTagAction = '';
        $redTagNotes = '';
        $redTagTextarea = '';
        $redTagged = false;
        $cid = '';
        $refreshBtn = '';
        $rejected = $srsRequest->trashed();
        $resendApptBtn = '';
        $isOpen = (!$srsRequest->trashed() && $srsRequest->status != 4 && $srsRequest->status != 5);
        $resendHoaNotifBtn = '';
        // VB
        // if (auth()->user()->can('approve', SrsRequest::class)) {
        $requestAction = '<div class="mx-auto">
                                    <a data-value="' . $srsRequest->request_id . '" href="#" id="approve_btn" class="btn btn-sm btn-primary mx-2 px-3">APPROVE SRS</a>
                                    <a data-value="' . $srsRequest->request_id . '" href="#" id="reject_btn" class="btn btn-sm btn-danger mx-2 px-3">REJECT SRS</a>
                                </div>';
        // } else {
        // $requestAction = false;
        // }

        if ($srsRequest->status == 3 && $statuses->firstWhere('name', 'Closed')->requests->isEmpty() && $srsRequest->invoice && $srsRequest->customer) {
            $paymentAction = '<button type="button" data-value="' . $srsRequest->request_id . '" id="invoice_payment_btn" class="btn btn-sm btn-primary px-3" ' . ($srsRequest->trashed() ? 'disabled' : '') . '>Close Ticket</button>';
        }


        if ($srsRequest->status == 2) {
            if (!$srsRequest->appointment && auth()->user()->can('resend', SrsAppointment::class)) {

                $srsRequest->load(['appointmentResends']);

                if ($srsRequest->appointmentResends->count() < 2) {
                    if ($srsRequest->appointmentResends->count() == 0) {
                        $latest = $srsRequest->statuses->whereIn('pivot.status_id', [1, 2])->sortByDesc('pivot.created_at')->first();
                        $lastApprovalSent = $latest->pivot->created_at;
                    } else {
                        $latest = $srsRequest->appointmentResends->sortByDesc('created_at')->first();
                        $lastApprovalSent = $latest->created_at;
                    }

                    $srsRequest->load(['latestApptReset']);

                    if ($srsRequest->latestApptReset) {
                        $lastResetSent = $srsRequest->latestApptReset->created_at;
                        if ($lastResetSent > $lastApprovalSent) {
                            $lastApprovalSent = $lastResetSent;
                        }
                    }

                    // if (now()->diffInDays($lastApprovalSent) > 3) {
                    if (now()->diffInDays($lastApprovalSent) >= 0) {
                        $resendApptBtn = '<div class="my-2 text-end">
                                            <button type="button" data-value=' . $srsRequest->request_id . ' class="btn btn-sm btn-outline-info px-3" id="resend_approval_btn">Resend Appointment Email</button>
                                        </div>';
                    }
                }
            }
        }


        if (auth()->user()->can('resendHoaNotif', SrsRequest::class) && $srsRequest->status < 2 && $srsRequest->hoa && $srsRequest->statuses->where('name', 'Approval - Enclave President')->isEmpty()) {
            $resendHoaNotifBtn = '<div class="my-2 text-end">
                                    <button type="button" data-value=' . $srsRequest->request_id . ' class="btn btn-sm btn-outline-warning px-3" id="resend_hoa_notif_btn">Resend HOA Notification Email</button>
                                </div>';
        }

        if ($srsRequest->appointment) {
            if (Carbon::today() > $srsRequest->appointment->date) {
                $systemNotes .= '[' . $srsRequest->appointment->date->format('D, M d, Y') . ' ' . $srsRequest->appointment->timeslot->formattedTime . '] Non-payment / No Show';
            }
        }

        if ($srsRequest->customer) {
            // $crm = CrmMain::where('customer_id', $srsRequest->customer_id)
            //                 ->whereHas('requests', function ($q) use ($srsRequest) {
            //                     $q->where('request_id', $srsRequest->request_id)
            //                         ->select('customer_id', 'request_id');
            //                 })
            //                 ->select('crm_id')
            //                 ->first();

            // $crm = CrmMain::where('customer_id', $srsRequest->customer_id)->select('crm_id')->first();
            if (!$srsRequest->invoice && auth()->user()->can('access', CrmMain::class)) {
                $genInvoiceAction = '<div class="col-md-6 text-end">
                                        <form action="/crm_v3/view-spc/' . $srsRequest->customer->crm_id . '/' . $srsRequest->customer->customer_id . '/" id="genInvoiceForm" method="GET" target="_blank">
                                            <input type="hidden" name="ref" value="srs_inbox">
                                            <input type="hidden" name="req" value="' . $srsRequest->request_id . '">
                                            <button type="submit" class="btn btn-sm btn-primary px-3" id="gen_invoice_btn" ' . ($srsRequest->trashed() ? 'disabled' : '') . '>Generate Invoice</button>
                                        </form>
                                    </div>';
            }

            //<input type="hidden" name="crm_id" value="'.$srsRequest->customer_id.'">
            $cid = $srsRequest->customer->customer_id;

            if ($srsRequest->customer->redTags->isNotEmpty()) {
                $redTagged = true;
            }

            $redTagAction = '<input type="checkbox" name="" id="red_tag_btn"' . ($srsRequest->customer->red_tag || $srsRequest->customer->redTags->isNotEmpty() ? 'checked' : '') . ' ' . ($srsRequest->trashed() || ($srsRequest->customer->redTags->isNotEmpty() && Auth::user()->role_id < 3) ? 'disabled' : '') . '><label for="red_tag_btn" class="px-2" style="color: red;"> <b> RED TAG</b></label>';
            $redTags = '';
            if ($srsRequest->customer->red_tag || $srsRequest->customer->redTags->isNotEmpty()) {
                $redTags = $srsRequest->customer->redTags->map(function ($tag) {
                    return '[' . date('M d, Y', strtotime($tag->date_created)) . '] ' . $tag->description;
                    //
                })->implode("\r\n");
            }
            $redTagNotes = '<label class="text-muted" style="font-size: 14px;">Red Tag Notes</label><textarea name="" class="form-control px-md-3 text-muted rounded-0 mb-2" disabled>' . $redTags . '</textarea>';
            $redTagTextarea = '<textarea name="" id="red_tag_notes" class="form-control px-md-3 text-muted rounded-0 mb-2" placeholder="Reason of red tag" ' . ($srsRequest->trashed() ? 'disabled' : '') . ' style="' . ($srsRequest->customer->redTags->isNotEmpty() ? '' : 'display: none;') . '"></textarea>';
        }

        $refreshBtn = '<a data-id="' . $srsRequest->request_id . '" role="button" id="btn_refresh_request_details" title="Refresh" href="#">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>';

        if ($srsRequest->trashed()) {
            $requestAction = '';
            $routeReject = '<tr style="color: red;font-weight: bold;">
                                <td style="text-align: left;min-width: 150px !important;"><label>Rejected</label></td>
                                <td style="min-width: 150px !important;text-align: left;">' . $srsRequest->rejected_by . '</td>
                                <td style="max-width: 100px !important;">' . $srsRequest->deleted_at->format('m/d/Y h:i A') . '</td>
                                <td style="max-width: 200px !important;text-align: left;">' . $srsRequest->reject_reason . '</td>
                            </tr>
                            ';
            $routes[] = $routeReject;
        }

        if (!$isOpen) {
            $requestAction = '';
        }

        $hoa = '';

        if ($srsRequest->category_id == 1) {
            $hoa = $srsRequest->hoa->name ?? 'Not Applicable';
        } else if ($srsRequest->category_id == 2) {
            $hoa = $srsRequest->nrHoa->name ?? 'Not Applicable';
        }

        $subCatName = $srsRequest->subCategory ? $srsRequest->subCategory->name : 'Not Found (Might be outdated)';

        // SRS 3
        $srs3Category = $srsRequest->category3->name ?? null;
        $srs3SubCategory = $srsRequest->subCategory3->name ?? null;
        $srs3Service =  $srs3Category . ' / ' . $srs3SubCategory;

        $data = [
            'id' => $srsRequest->request_id,
            'cid' => $cid,
            'fn' => $srsRequest->first_name,
            'ln' => $srsRequest->last_name,
            'mid' => $srsRequest->middle_name,
            'email' => auth()->user()->can('access', CrmMain::class) ? $srsRequest->email : '',
            'contact_no' => auth()->user()->can('access', CrmMain::class) ? $srsRequest->contact_no : '',
            'blk_lot' => $srsRequest->house_no,
            'street' => $srsRequest->street,
            'address' => $srsRequest->house_no . ' ' . $srsRequest->street . ($srsRequest->building_name ? ', ' . $srsRequest->building_name : '') . ($srsRequest->subdivision_village ? ', ' . $srsRequest->subdivision_village : '') . ($srsRequest->city ? ', ' . $srsRequest->city : ''),
            'creationDate' => $srsRequest->created_at->format('F d, Y h:i A'),
            'status' => $srsRequest->trashed() ? 'Rejected' : $this->getStatus($srsRequest->status, $adminApproved),
            'service' => $srsRequest->category->name . ' / ' . $subCatName,
            'new_service' => $srs3Service,
            // 'hoa' => $srsRequest->hoa->name ?? 'Not Applicable',
            'hoa' => $hoa,
            'new_hoa_id' =>  $srsRequest->hoa3->name ?? 'Not Applicable',
            'routes' => $routes,
            'vehicles' => $vehicles,
            'files' => $files,
            // 'hoaApproval' => $hoaApproval ? $hoaApproval->created_at->format('M d, Y h:i A') : '',
            // 'adminApproval' => $adminApproval ? $adminApproval->pivot->created_at->format('M d, Y h:i A') : ''
            'adminApproved' => $adminApproved,
            'requestAction' => $requestAction,
            'paymentAction' => $paymentAction,
            'genInvoiceAction' => $genInvoiceAction,
            'systemNotes' => $systemNotes,
            'adminNotes' => htmlspecialchars($srsRequest->admin_notes),
            'redTagged' => $redTagged,
            'redTagAction' => $redTagAction,
            'redTagNotes' => $redTagNotes,
            'redTagTextarea' => $redTagTextarea,
            'refreshBtn' => $refreshBtn,
            'rejected' => $rejected,
            'resendApptBtn' => $resendApptBtn,
            'isOpen' => $isOpen,
            'resendHoaNotifBtn' => $resendHoaNotifBtn
        ];

        return response()->json(['srs' => $data]);
    }

    public function getNextId()
    {
        $today = now();
        $series = $today->format('y') . '-' . $today->format('d') . $today->format('m') . '-';
        // $series = $today->format('y') . $category . $subCategory . '-' . $today->format('d') . $today->format('m') . '-';

        $lastRequest = SrsRequest::where('request_id', 'like', $series . '%')->latest()->first();

        if ($lastRequest) {
            $lastSeriesNumber = (int)str_replace($series, '', $lastRequest->request_id);
        } else {
            $lastSeriesNumber = 0;
        }

        do {
            $lastSeriesNumber++;
            $srn = $series . str_pad((string)$lastSeriesNumber, 5, '0', STR_PAD_LEFT);
            $srsRequest = SrsRequest::where('request_id', $srn)->withTrashed()->exists();
        } while ($srsRequest);

        return $srn;
    }

    public function approve(Request $request)
    {
        // $this->authorize('approve', SrsRequest::class);

        // $srsRequest = SrsRequest::where('request_id', $request->req_id)
        //                         ->whereDoesntHave('statuses', function ($query) {
        //                             $query->where('status_id', 1);
        //                         })
        //                         ->firstOrFail();

        $srsRequest = SrsRequest::with(['statuses', 'hoa' => function ($query) {
            $query->select('id', 'type');
        }])
            ->where('request_id', $request->req_id)
            ->firstOrFail();

        $adminApproval = $srsRequest->statuses->firstWhere('name', 'Approval - Admin');

        if ($adminApproval) {

            return response()->json(['status' => 0, 'srs' => $srsRequest->request_id, 'approvedBy' => $adminApproval->pivot->created_at->diffForHumans() . ' by ' . $adminApproval->pivot->action_by]);
        }

        $status = 1;

        if ($srsRequest->status == 1 || !$srsRequest->hoa || ($srsRequest->hoa && $srsRequest->hoa->type != 0)) {
            $this->sendRequestApprovedEmail($srsRequest);
            $status = 2;
        }

        $srsRequest->admin_approved = 1;
        $srsRequest->status = $status;
        $srsRequest->save();

        $srsRequest->statuses()->attach(1, ['action_by' => Auth::user()->email]);


        return response()->json(['status' => 1, 'msg' => 'Request Approved!', 'srs' => $srsRequest->request_id]);
    }

    public function hoaApproved(Request $request)
    {
        $urls = [
            'https://bffhai.znergee.com/sticker/request/hoa_approval',
            'https://bffhai2.znergee.com/sticker/request/hoa_approval'
        ];

        $currentUrl = explode('?', url()->previous())[0];
        if (!in_array($currentUrl, $urls)) {
            abort(403);
        }

        $parsedUrl = parse_url(url()->previous());
        parse_str($parsedUrl['query'], $prevUrlParam);
        $actionBy = '';

        if (isset($prevUrlParam['ref']) && $prevUrlParam['ref']) {
            try {
                $actionBy = Crypt::decrypt($prevUrlParam['ref']);
            } catch (DecryptException $e) {
            }
        }


        $srsRequest = SrsRequest::where('request_id', $request->req_id)
            ->whereDoesntHave('statuses', function ($query) {
                $query->where('status_id', 2);
            })
            ->firstOrFail();
        $status = 1;

        if ($srsRequest->status == 1) {
            $this->sendRequestApprovedEmail($srsRequest);
            $status = 2;
        }

        $srsRequest->status = $status;
        $srsRequest->save();

        $srsRequest->statuses()->attach(2, ['action_by' => $actionBy]);

        return response()->json(['status' => 1, 'msg' => 'Request Approved!']);
    }

    public function adminDestroy(Request $request, $reqId)
    {
        // $this->authorize('approve', SrsRequest::class);

        if (!$request->ajax()) {
            return back();
        }

        $request->merge(['reqId' => $reqId]);

        $data = $request->validate([
            'reason' => 'required|string',
            'reqId' => 'required|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::with(['hoa' => function ($query) {
            $query->select('id');
        }])
            ->where('request_id', $request->reqId)
            ->first();

        if (!$srsRequest) {
            $rejected = SrsRequest::where('request_id', $request->reqId)
                ->onlyTrashed()
                ->select('rejected_by', 'reject_reason', 'deleted_at')
                ->first();

            return response()->json(['status' => 0, 'rejectedBy' => $rejected->deleted_at->diffForHumans() . ' by ' . $rejected->rejected_by, 'reason' => $rejected->reject_reason]);
        }

        $srsRequest->status = 61;
        $srsRequest->reject_role = auth()->id();
        $srsRequest->rejected_by = auth()->user()->email;
        $srsRequest->reject_reason = $data['reason'];
        $srsRequest->save();

        $srsRequest->delete();


        // Mail::to($srsRequest->email)
        //     ->queue(new RequestRejected($srsRequest->request_id, $data['reason']));

        dispatch(new \App\Jobs\SendRejectedNotificationJob($srsRequest, $srsRequest->email, $data['reason']));


        return response()->json(['status' => 1, 'msg' => 'Request Rejected!']);
    }

    public function destroy(Request $request, SrsRequest $srsRequest)
    {
        $urls = [
            'https://bffhai.znergee.com/sticker/request/hoa_approval',
            'https://bffhai2.znergee.com/sticker/request/hoa_approval'
        ];

        $currentUrl = explode('?', url()->previous())[0];
        if (!in_array($currentUrl, $urls)) {
            abort(403);
        }

        if (!$request->ajax()) {
            return back();
        }

        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        $parsedUrl = parse_url(url()->previous());
        parse_str($parsedUrl['query'], $prevUrlParam);
        $actionBy = NULL;

        if (isset($prevUrlParam['ref']) && $prevUrlParam['ref']) {
            try {
                $actionBy = Crypt::decrypt($prevUrlParam['ref']);
            } catch (DecryptException $e) {
            }
        }

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


        return response()->json(['status' => 1, 'msg' => 'Request Rejected!']);
    }

    public function resendHoaNotification(Request $request)
    {
        // $this->authorize('resendHoaNotif', SrsRequest::class);

        $data = $request->validate([
            'request_id' => 'required|string|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::with('hoa')
            ->where('request_id', $data['request_id'])
            ->first();

        if (!$srsRequest) {

            return response()->json(['status' => 0, 'error_msg' => 'SRS View: Resend Hoa Notification Email: L197 Error <br><br> Please try again']);
        }

        try {

            $srn = Crypt::encrypt($srsRequest->request_id);

            if ($srsRequest->hoa && $srsRequest->hoa->type == 0) {
                if ($srsRequest->hoa->emailAdd1) {
                    $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

                    dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd1, $url, 'resend'))->delay(now()->addSeconds(10));
                }

                if ($srsRequest->hoa->emailAdd2) {
                    $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd2)]);

                    dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd2, $url, 'resend'))->delay(now()->addSeconds(12));
                }

                if ($srsRequest->hoa->emailAdd3) {
                    $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd3)]);

                    dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd3, $url, 'resend'))->delay(now()->addSeconds(14));
                }
            }

            return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
        } catch (\Exception $e) {

            return response()->json(['status' => 0, 'error_msg' => 'SRS View: Resend Hoa Notifcation Email: L273 Error']);
        }
    }

    public function storeFile($path, $file)
    {
        $token = uniqid();
        $fileExt = $file->getClientOriginalExtension();
        $name =  date('His') . '_' . $token;
        $filename = $name . '.' . $fileExt;

        if ($fileExt == 'pdf') {
            $file->storeAs($path, $filename);
        } else {
            $filename =  $name . '.webp';
            try {
                $img = Image::make($file)->encode('webp', 90);
                $img->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(storage_path('app') . '/' . $path . '/' . $filename, 70);
            } catch (\Exception $e) {
                $file->storeAs($path, $filename);
            }
        }

        return $filename;
    }

    public function showFile($id, $date, $name, $hoa, $category)
    {
        // $this->authorize('access', SrsRequest::class);
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

    public function hoaShowFile($id, $date, $name, $hoa, $category)
    {
        $urls = [
            'https://bffhai.znergee.com/sticker/request/hoa_approval',
            'https://bffhai2.znergee.com/sticker/request/hoa_approval'
        ];

        $currentUrl = explode('?', url()->previous())[0];
        if (!in_array($currentUrl, $urls)) {
            abort(403);
        }


        $parsedUrl = parse_url(url()->previous());
        parse_str($parsedUrl['query'], $output);

        if (
            !isset($output['expires']) || !isset($output['signature']) || !isset($output['key'])
            || empty($output['expires']) || empty($output['signature']) || empty($output['key'])
        ) {
            abort(403);
        }

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

    public function getRequests(Request $request)
    {

        // $this->authorize('access', SrsRequest::class);

        // if (!$request->ajax()) {
        //     abort(404);
        // }
        // dd($request->year);

        $tableName = 'srs3_requests';

        // if type = 0 then 'Archive'
        if ($request->type == 4) {
            // $request->validate(['year' => 'integer|date_format:Y']);

            if ($request->year == 1) {
                // VB Error Archive Here

                $archiveTables = DB::table('information_schema.tables')->where('table_name', 'like', 'srs_requests_archive_%')->select('table_name')->get();
                $srsQuery = (object) [];
                // $a = DB::raw("(select `request_id`, `first_name`, `last_name`, `status`, `created_at`, `admin_approved` from `srs_requests_archive_2021` where (LOWER(`srs_requests_archive_2021`.`request_id`) LIKE ? or (CONCAT(srs_requests_archive_2021.first_name,' ',srs_requests_archive_2021.last_name)  like ?) or (DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?)) and `srs_requests_archive_2021`.`deleted_at` is null) union (select `request_id`, `first_name`, `last_name`, `status`, `created_at`, `admin_approved` from `srs_requests_archive_2022` where `srs_requests_archive_2022`.`deleted_at` is null) union (select `request_id`, `first_name`, `last_name`, `status`, `created_at`, `admin_approved` from `srs_requests_archive_2023` where `srs_requests_archive_2023`.`deleted_at` is null) order by `created_at` asc limit 15 offset 0");
                // dd($a);
                // dd($archiveTables);
                foreach ($archiveTables as $index => $archiveTable) {

                    // $q = SrsRequestsArchive::fromTable($archiveTable->table_name)->with('stats')->select('request_id', 'first_name', 'last_name', 'status', 'created_at', 'admin_approved');

                    // $q = DB::table($archiveTable->table_name)->select('request_id', 'first_name', 'last_name', 'status', 'created_at', 'admin_approved', 'deleted_at');

                    $srsRequestArchive = SrsRequestsArchive::fromTable($archiveTable->table_name)->select('request_id', 'first_name', 'last_name', 'status', 'created_at', 'admin_approved', 'deleted_at', DB::raw('"' . $archiveTable->table_name . '" as source'));

                    if ($request->search['value']) {
                        $keyword = $request->search['value'];
                        $srsRequestArchive->whereRaw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?",  ["%$keyword%"])
                            ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%" . $request->search['value'] . "%")
                            ->orWhere('request_id', 'LIKE', "%" . $request->search['value'] . "%");

                        //->orWhere(DB::raw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A')"), 'LIKE', "%".$request->search['value']."%");
                    }


                    if ($index == 0) {
                        $srsQuery = $srsRequestArchive;
                        $tableName = $archiveTable->table_name;
                    } else {

                        $srsQuery = $srsQuery->union($srsRequestArchive);
                    }
                }

                $requests = $srsQuery;

                $datatable = DataTables::of($requests)
                    // ->filterColumn('requestor', function ($query, $keyword) use ($tableName) {
                    //     $sql = "CONCAT(".$tableName.".first_name,' ',".$tableName.".last_name)  like ?";
                    //     $query->whereRaw($sql, ["%{$keyword}%"]);
                    //     // $query->where('first_name', 'LIKE', '%'.$keyword.'%')
                    //     //         ->orWhere('last_name', 'LIKE', '%'.$keyword.'%');
                    // })
                    // ->filterColumn('created_at', function ($query, $keyword) {
                    //     $query->whereRaw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?", ["%$keyword%"]);
                    // })
                    // ->filterColumn('status', function ($query, $keyword) use ($tableName) {
                    //     $query->join('srs_request_statuses', 'srs_request_statuses', '=', $tableName.'.status')
                    //             ->where('srs_request_statuses.name', 'LIKE', ["%$keyword%"]);
                    // })
                    ->editColumn('request_id', function ($request) {

                        return '<a data-id="' . $request->request_id . '" data-archive="' . $request->source . '" class="view_request" href="#">' . $request->request_id . '</a>';
                    })
                    ->addColumn('requestor', function ($request) {
                        return $request->first_name . ' ' . $request->last_name;
                    })
                    ->editColumn('created_at', function ($request) {
                        // $date = Carbon::parse($request->created_at);
                        // return $date->format('M d, Y h:i A');

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

                        // return $this->getStatus($request->status, $request->stats->where('name', 'Approval - Admin')->isNotEmpty());
                        return $this->getStatus($request->status, $request->admin_approved);
                    })
                    ->rawColumns(['request_id'])
                    ->make(true);
            } else {

                $tableName = 'srs_requests_archive_' . $request->year;

                if (!Schema::hasTable($tableName)) {
                    return [];
                }

                $srsQuery = SrsRequestsArchive::fromTable($tableName)->select('request_id', 'first_name', 'last_name', 'status', 'created_at', 'admin_approved', 'deleted_at', DB::raw('"' . $tableName . '" as source'));

                $requests = $srsQuery;

                $datatable = DataTables::of($requests)
                    ->filterColumn('requestor', function ($query, $keyword) use ($tableName) {
                        $sql = "CONCAT(" . $tableName . ".first_name,' '," . $tableName . ".last_name)  like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('created_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?", ["%$keyword%"]);
                    })
                    ->filterColumn('status', function ($query, $keyword) use ($tableName) {
                        $query->join('srs_request_statuses', 'srs_request_statuses', '=', $tableName . '.status')
                            ->where('srs_request_statuses.name', 'LIKE', ["%$keyword%"]);
                    })
                    ->editColumn('request_id', function ($request) {

                        return '<a data-id="' . $request->request_id . '" data-archive="' . $request->source . '" class="view_request" href="#">' . $request->request_id . '</a>';
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

                        // return $this->getStatus($request->status, $request->stats->where('name', 'Approval - Admin')->isNotEmpty());
                        return $this->getStatus($request->status, $request->admin_approved);
                    })
                    ->rawColumns(['request_id'])
                    ->make(true);
            }

            // $srsQuery = SrsRequestsArchive::query()->table('srs_requests_archive_2022');
        } else {
            // VB
            $srsQuery = SrsRequest::query();

            $requests = $srsQuery->with('stats')
                ->when($request->type == 1, function ($query) {
                    return $query->where('status', 4);
                })
                ->when($request->type == 0, function ($query) {
                    return $query->where('status', '<', 4);
                })
                ->when($request->type == 2, function ($query) {
                    return $query->onlyTrashed();
                })
                ->when($request->type == 3, function ($query) {
                    // return $query ->whereNotIn('srs_requests.request_id', function ($q) {
                    //     $q->select('srs_request_status_logs.request_id')
                    //             ->from('srs_request_status_logs')
                    //             ->join('srs_request_statuses', 'srs_request_statuses.id', 'srs_request_status_logs.status_id')
                    //             ->where('srs_request_statuses.name', 'Approval - Admin');
                    // });

                    // return $query->where('status', '<', 2)->orderBy('status');

                    return $query->where('status', '<', 2)
                        ->where('admin_approved', 0);
                })
                ->select('request_id', 'first_name', 'last_name', 'status', 'created_at');

            $datatable = DataTables::of($requests)
                ->filterColumn('requestor', function ($query, $keyword) use ($tableName) {
                    $sql = "CONCAT(" . $tableName . ".first_name,' '," . $tableName . ".last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                    // $query->where('first_name', 'LIKE', '%'.$keyword.'%')
                    //         ->orWhere('last_name', 'LIKE', '%'.$keyword.'%');
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at,'%M %d, %Y %h:%i %A') LIKE ?", ["%$keyword%"]);
                })
                // ->filterColumn('status', function ($query, $keyword) {
                //     $query->join('srs_request_statuses', 'srs_request_statuses', '=', 'srs_requests.status')
                //             ->where('srs_request_statuses.name', 'LIKE', ["%$keyword%"]);
                // })
                ->editColumn('request_id', function ($request) {
                    return '<a data-id="' . $request->request_id . '" class="view_request" href="#">' . $request->request_id . '</a>';
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
        }

        return $datatable;
    }

    public function checkStatus(Request $request)
    {
        $data = $request->validate([
            'reqId' => 'required|string|exists:srs_requests,request_id'
        ], [], [
            'reqId' => 'Request ID'
        ]);


        $srsRequest = SrsRequest::withTrashed()->with(['appointment', 'appointment.timeslot'])->where('request_id', $data['reqId'])->firstOrFail();

        // if ($srsRequest->status == 0) {
        //     return response()->json(['status' => 0, 'msg' => 'Your request is pending approval', 'request_id' => $srsRequest->request_id, 'request_date' => $srsRequest->created_at->format('M d, Y h:i A')]);
        // } elseif ($srsRequest->status == 1) {
        //     return response()->json(['status' => 1, 'msg' => 'Your request is approved', 'request_id' => $srsRequest->request_id, 'request_date' => $srsRequest->created_at->format('M d, Y h:i A')]);
        // } elseif ($srsRequest->status == 2) {

        //     if (!$srsRequest->appointment) {
        //         return response()->json(['status' => 2, 'msg' => 'Your request is approved']);
        //     }

        //     $dateTime = $srsRequest->appointment->date->format('M d, Y') .' '. $srsRequest->appointment->timeslot->time->format('h:i A');

        //     return response()->json(['status' => 2, 'msg' => 'Your request is approved', 'appt_datetime' => $dateTime, 'request_id' => $srsRequest->request_id, 'request_date' => $srsRequest->created_at->format('M d, Y h:i A')]);
        // }

        return response()->json([
            'status' => $srsRequest->status,
            'request_id' => $srsRequest->request_id,
            'request_date' => $srsRequest->created_at->format('M d, Y h:i A'),
            'appointment' => $srsRequest->appointment ? $srsRequest->appointment->date->format('M d, Y') . ' ' . $srsRequest->appointment->timeslot->time->format('h:i A') : '',
            'rejected' => $srsRequest->rejected_by ? [
                'rejectedBy' => $srsRequest->rejected_by ?? '',
                'rejectedReason' => $srsRequest->reject_reason ?? '',
                'rejectedAt' => $srsRequest->deleted_at ?? ''
            ] : ''
        ]);
    }

    public function getSubCategories(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        // $data = $request->validate([
        //     'category' => 'required|int|exists:srs_categories,id'
        // ]);

        $subCategories = SPCSubCat::where('status', 1)->select('id', 'category_id', 'name')->get();

        return $subCategories;
    }

    public function getSubCategoriesV3(Request $request)
    {
        $categoryId = $request->query('category_id');
        $subcategories = DB::table('get_subcat')->where('category_id', $categoryId)->get();
        
        return response()->json($subcategories);
    }

    public function getRequirements(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $data = $request->validate([
            'sub_category' => 'required|int|exists:spc_subcat,id'
        ]);

        $requirements = SrsRequirement::with(['subCategories' => function ($query) {
            $query->select('spc_subcat.id');
        }])
            ->whereHas('subCategories', function ($query) use ($data) {
                $query->where('spc_subcat.id', $data['sub_category']);
            })
            ->select('id', 'name', 'description', 'required')
            ->get();


        return $requirements;
    }

    public function getHoas(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $hoas = SrsHoa::where('name', '!=', 'TEST ATOMIT HOA')->select('id', 'name', 'type')->orderBy('name')->get();

        return $hoas;
    }

    public function getNRHoas(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $hoas = SrsNrHoa::select('id', 'name')->orderBy('name')->get();
        $hoaList = [];

        foreach ($hoas as $hoa) {
            $hoaList[] = [
                'id' => $hoa->id,
                'name' => $hoa->name
            ];
        }

        return response()->json($hoaList);
    }

    public function hoaApproval(Request $request)
    {
        if (!$request->hasValidSignature() || !$request->key) {
            abort(404, 'Link is already expired');
        }

        try {
            $srn = Crypt::decrypt($request->key);
        } catch (DecryptException $e) {
            abort(404);
        }

        $request->merge(['srn' => $srn]);

        $data = $request->validate([
            'expires' => 'required',
            'key' => 'required|string',
            'signature' => 'required|string',
            'srn' => 'required|string|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::withTrashed()
            ->with(['files.requirement' => function ($query) {
                $query->select('id', 'description');
            }, 'category' => function ($query) {
                $query->select('id', 'name');
            }, 'subCategory' => function ($query) {
                $query->select('id', 'name');
            }, 'statuses'])
            ->where('request_id', $data['srn'])
            ->where(function ($query) {
                $query->where('status', 0)
                    ->orWhere('status', 1)
                    ->orWhere('status', 61)
                    ->orWhere('status', 62);
            })
            // ->whereDoesntHave('statuses', function ($query) {
            //     $query->where('status_id', 2);
            // })
            ->whereDoesntHave('appointment')
            // ->firstOrFail();
            ->first();

        if (!$srsRequest) {
            abort(404);
        }

        if ($srsRequest->trashed()) {
            abort(404, '[CM404] Ticket is already rejected');
        }

        if ($srsRequest->statuses->firstWhere('name', 'Approval - Enclave President')) {
            abort(404, '[CM404] Ticket is already approved');
        }


        return view('srs.request.hoa_approval', compact('srsRequest'));
    }

    public function storeRequirementFile($name, $reqId, $path)
    {
        $requirementFile = new SrsRequirementFile();
        $requirementFile->srs_requirement_id = $reqId;
        $requirementFile->name = $name;
        $requirementFile->req_path = $name . '/' . $path;

        return $requirementFile;
    }

    public function updateInfo(Request $request)
    {
        $this->authorize('access', SrsRequest::class);

        $data = $request->validate([
            'req_id' => 'required|exists:srs_requests,request_id',
            'notes' => 'nullable|string',
            'red_tag' => 'nullable|boolean',
            'red_tag_notes' => 'nullable|string',
            'acc' => 'nullable|exists:crm_mains,customer_id'
        ]);

        $srsRequest = SrsRequest::findOrFail($data['req_id']);
        $srsRequest->admin_notes = $data['notes'];

        $this->insertLogSrs('Updated SRS Info, SRS ID ' . $srsRequest->request_id . ', admin_notes: ' . $data['notes']);

        if ($srsRequest->save()) {
            if ($data['acc']) {
                $srsRequest->load(['customer' => function ($query) {
                    $query->select('crm_id', 'customer_id', 'red_tag');
                }, 'customer.redTags' => function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', 0);
                }]);

                if ($srsRequest->customer->redTags->isNotEmpty()) {
                    if ($data['red_tag'] != 1) {
                        $srsRequest->customer()->update([
                            'red_tag' => $data['red_tag'],
                        ]);

                        $srsRequest->customer->redTags()->update([
                            'status' => 1,
                            'deleted_by' => Auth::user()->name
                        ]);

                        $this->insertLogSrs('Updated SRS Info, SRS ID ' . $srsRequest->request_id . ', red_tag: ' . $data['red_tag']);
                    }
                } else {
                    if ($data['red_tag'] == 1) {
                        $srsRequest->customer()->update([
                            'red_tag' => $data['red_tag'],
                        ]);

                        $this->insertLogSrs('Updated SRS Info, SRS ID ' . $srsRequest->request_id . ', red_tag: ' . $data['red_tag']);
                    }
                }



                if ($data['red_tag_notes'] && $data['red_tag'] == 1) {
                    $redTag = new CrmRedtag();
                    $redTag->description = $data['red_tag_notes'];
                    $redTag->action_by = Auth::user()->name;

                    $srsRequest->customer->redTags()->save($redTag);

                    $this->insertLogSrs('Updated SRS Info, SRS ID ' . $srsRequest->request_id . ', reason_of_tag: ' . $data['red_tag_notes']);
                }
            }

            return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
        }
    }

    public function updateCid(Request $request)
    {
        $this->authorize('access', SrsRequest::class);

        $data = $request->validate([
            'req_id' => 'required|exists:srs_requests,request_id',
            'acc' => 'required|string|exists:crm_mains,crm_id',
        ]);

        // $srsRequest = SrsRequest::findOrFail($data['req_id']);
        $srsRequest = SrsRequest::with(['vehicles' => function ($query) {
            $query->select('id', 'srs_request_id', 'plate_no', 'assoc_crm');
        }])
            ->findOrFail($data['req_id']);

        $srsRequest->customer_id = $data['acc'];
        if ($srsRequest->save()) {
            // $account = CrmMain::where('customer_id', $srsRequest->customer_id)->first();
            // $account = CrmMain::where('crm_id', $srsRequest->customer_id)->first();
            $account = CrmMain::with(['vehicles' => function ($query) {
                $query->select('id', 'crm_id', 'plate_no', 'created_at');
            }])
                ->where('crm_id', $srsRequest->customer_id)
                ->first();

            $vehicles = [];

            foreach ($srsRequest->vehicles as $reqVehicle) {
                $crmVehicle = $account->vehicles->where('plate_no', $reqVehicle->plate_no)->sortBy('created_at')->first();
                if ($crmVehicle) {
                    $vehicles[] = $crmVehicle->id;
                } else {
                    $vehicles[] = $reqVehicle->id;
                    $reqVehicle->assoc_crm = 1;
                    $reqVehicle->save();
                }
            }

            $srsRequest->crmVehicles()->sync($vehicles);

            $srsRequest->vehicles()->update([
                'crm_id' => $account->customer_id
            ]);

            $this->insertLogSrs('SRS Linked to Account, SRS ID ' . $srsRequest->request_id . ', Linked to Customer ID ' . $account->customer_id);

            // if (!$account->blk_lot) {
            //     $account->blk_lot = $srsRequest->house_no;
            // }

            // if (!$account->street) {
            //     $account->street = $srsRequest->street;
            // }

            // if ($srsRequest->hoa) {
            //     if (!$account->hoa) {
            //         $account->hoa = $srsRequest->hoa_id;
            //     }
            // }

            // if (!$account->email) {
            //     $account->email = $srsRequest->email;
            // }

            // if (!$account->main_contact) {
            //     $account->main_contact = $srsRequest->contact_no;
            // }

            // if (!$account->category_id) {
            //     $account->category_id = $srsRequest->category_id;
            // }

            // if (!$account->sub_category_id) {
            //     $account->sub_category_id = $srsRequest->sub_category_id;
            // }


            $account->save();

            return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
        }
    }

    public function storeCrm(Request $request)
    {
        $this->authorize('access', SrsRequest::class);

        $data = $request->validate([
            'req_id' => 'required|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::with(['hoa' => function ($query) {
            $query->select('id', 'name');
        }, 'vehicles' => function ($query) {
            $query->select('id', 'srs_request_id');
        }, 'nrHoa' => function ($query) {
            $query->select('id', 'name');
        }])
            ->withCount('vehicles')
            ->findOrFail($data['req_id']);

        // $account = CrmMain::firstOrNew([
        //     'category_id' => $srsRequest->category_id,
        //     'sub_category_id' => $srsRequest->sub_category_id,
        //     'firstname' => $srsRequest->first_name,
        //     'middlename' => $srsRequest->middle_name,
        //     'lastname' => $srsRequest->last_name,
        //     'blk_lot' => $srsRequest->blk_lot,
        //     'street' => $srsRequest->street,
        //     'hoa' => $srsRequest->hoa ? $srsRequest->hoa->name : '',
        //     'email' => $srsRequest->email,
        //     'main_contact' => $srsRequest->contact_no,
        // ], [
        //     'customer_id' => app('App\Http\Controllers\CRMController')->getNextId($srsRequest->category_id, $srsRequest->sub_category_id, $srsRequest->hoa_id, 'int')
        // ]);
        $crm = CrmMain::with(['creator' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where('category_id', $srsRequest->category_id)
            ->where('sub_category_id', $srsRequest->sub_category_id)
            ->where('firstname', $srsRequest->first_name)
            ->where('middlename', $srsRequest->middle_name)
            ->where('lastname', $srsRequest->last_name)
            ->where('blk_lot', $srsRequest->house_no)
            ->where('street', $srsRequest->street)
            ->where('email', $srsRequest->email)
            ->where('main_contact', $srsRequest->contact_no)
            ->select('created_at', 'created_by')
            ->first();

        if ($crm) {

            return response()->json(['status' => 0, 'srs' => $srsRequest->request_id, 'createdBy' => $crm->created_at->diffForHumans() . ' by ' . $crm->creator->name]);
        } else {
            $account = new CrmMain();
            $account->customer_id = app('App\Http\Controllers\CRMController')->getNextId($srsRequest->category_id, $srsRequest->sub_category_id, $srsRequest->hoa_id, 'int');
            $account->category_id = $srsRequest->category_id;
            $account->sub_category_id = $srsRequest->sub_category_id;
            $account->firstname = $srsRequest->first_name;
            $account->middlename = $srsRequest->middle_name;
            $account->lastname = $srsRequest->last_name;
            $account->blk_lot = $srsRequest->house_no;
            $account->street = $srsRequest->street;
            $account->building_name = $srsRequest->building_name;
            $account->subdivision_village = $srsRequest->subdivision_village;
            $account->city = $srsRequest->city;
            $account->owned_vehicles = $srsRequest->vehicles_count;
            // $account->hoa = $srsRequest->hoa ? $srsRequest->hoa->name : '';
            if ($srsRequest->category_id == 1) {
                $account->hoa = $srsRequest->hoa ? $srsRequest->hoa->name : NULL;
            } else if ($srsRequest->category_id == 2) {
                $account->hoa = $srsRequest->nrHoa ? $srsRequest->nrHoa->name : NULL;
            }

            $account->email = $srsRequest->email;
            $account->main_contact = $srsRequest->contact_no;
            $account->status = 1;
            $account->created_by = Auth::id();
            $account->save();

            $this->insertLogSrs('Inserted to CRM via SRS Create Customer button, SRS ID ' . $srsRequest->request_id . ', Customer ID ' . $account->customer_id);
            $srsRequest->customer()->associate($account);
            $srsRequest->vehicles()->update(['crm_id' => $account->customer_id, 'assoc_crm' => 1]);
            $srsRequest->crmVehicles()->sync($srsRequest->vehicles->pluck('id'));
            $srsRequest->save();

            return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
        }
    }

    public function searchCRM(Request $request)
    {
        $this->authorize('access', SrsRequest::class);

        $data = $request->validate([
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'blk_lot' => 'required|string',
            'street' => 'required|string'
        ]);

        // $accounts = CrmMain::where('firstname', 'LIKE', $data['fname'].'%')
        //                     // ->where('middlename', 'LIKE', $data['mname'].'%')
        //                     ->where('lastname', 'LIKE', $data['lname'].'%')
        //                     ->get();

        // $accounts = CrmMain::where('name', 'LIKE', $data['fname'].' '.$data['lname'].'%')
        //                     ->orWhere('name', 'LIKE', $data['lname'].', '. $data['fname'].'%')
        //                     ->orWhere('name', 'LIKE', '%'.$data['fname'].' '.$data['lname'].'%')
        //                     ->orWhere('name', 'LIKE', '%'.$data['lname'].', '. $data['fname'].'%')
        //                     ->get();

        // $accounts = CrmMain::query()
        // ->join('spc_categories', 'crm_mains.category_id', '=', 'spc_categories.id')
        // ->join('spc_subcat', 'crm_mains.sub_category_id', '=', 'spc_subcat.id')
        // ->where(function ($query) use ($data) {
        //     $query->where('firstname', $data['fname'])
        //         ->where('lastname', $data['lname']);
        // })
        // ->orWhere(function ($query) use ($data) {
        //     $query->where('firstname', $data['fname'])
        //             ->where('middlename', $data['lname']);
        // })
        // ->orWhere(function ($query) use ($data) {
        //     $sql = "CONCAT(crm_mains.firstname,' ',crm_mains.lastname)  like ?";
        //     $query->whereRaw($sql, ["%{$data['fname']} {$data['lname']}%"]);
        // })
        // ->orWhere(function ($query) use ($data) {
        //     $sql = "CONCAT(crm_mains.firstname,' ',crm_mains.middlename)  like ?";
        //     $query->whereRaw($sql, ["%{$data['fname']} {$data['lname']}%"]);
        // })
        // ->orWhere(function ($query) use ($data) {
        //     $query->where('blk_lot', $data['blk_lot'])
        //         ->where('street', $data['street']);
        // })
        // ->select('crm_mains.*', 'spc_categories.name as category_name', 'spc_subcat.name as sub_category_name')
        // ->get();

        $accounts = CrmMain::query()
            ->with(['spc_category' => function ($query) {
                $query->select('id', 'name');
            }, 'spc_subcat' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where(function ($query) use ($data) {
                $query->where('firstname', $data['fname'])
                    ->where('lastname', $data['lname']);
            })
            ->orWhere(function ($query) use ($data) {
                $query->where('firstname', $data['fname'])
                    ->where('middlename', $data['lname']);
            })
            ->orWhere(function ($query) use ($data) {
                $sql = "CONCAT(crm_mains.firstname,' ',crm_mains.lastname)  like ?";
                $query->whereRaw($sql, ["%{$data['fname']} {$data['lname']}%"]);
            })
            ->orWhere(function ($query) use ($data) {
                $sql = "CONCAT(crm_mains.firstname,' ',crm_mains.middlename)  like ?";
                $query->whereRaw($sql, ["%{$data['fname']} {$data['lname']}%"]);
            })
            ->orWhere(function ($query) use ($data) {
                $query->where('blk_lot', $data['blk_lot'])
                    ->where('street', $data['street']);
            })
            // ->select('crm_mains.*', 'spc_categories.name as category_name', 'spc_subcat.name as sub_category_name')
            ->get();

        // dd($accounts);

        $html = '';

        if ($accounts->isEmpty()) {
            $html .= '<div class="text-center">
                            <div>No Customer Record Found</div>
                            <div>
                                <button id="create_customer_btn" class="btn btn-sm btn-primary">Create Customer Record</button>
                            </div>
                      </div>';

            return response()->json(['status' => 0, 'html' => $html]);
        }

        $html .= '<div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive">
                        <thead>
                            <tr>
                                <th style="text-align: center;background: #b1b7b9;color: white;"></th>
                                <th style="text-align: center;background: #b1b7b9;color: white; width: 15%;">ACCOUNT ID</th>
                                <th style="text-align: center;background: #b1b7b9;color: white; width: 30%;">NAME</th>
                                <th style="text-align: center;background: #b1b7b9;color: white;">ADDRESS</th>
                                <th style="text-align: center;background: #b1b7b9;color: white;">CATEGORY</th>
                                <th style="text-align: center;background: #b1b7b9;color: white;">SUB-CATEGORY</th>
                                <th style="text-align: center;background: #b1b7b9;color: white;">TAG STATUS</th>
                            </tr>
                        </thead>
                        <tbody>';

        // foreach($accounts as $key => $account) {
        //     $html .= '<tr>
        //                     <td style="text-align: center;>
        //                         <div class="form-check">
        //                             <input class="form-check-input" type="radio" name="accountRadio" id="accountRadio'.$key.'" value="'.$account->customer_id.'">
        //                         </div>
        //                     </td>
        //                     <td>'.$account->customer_id.'</td>
        //                     <td>'.$account->lastname.', '.$account->firstname.' '.$account->middle_name.'</td>
        //                     <td>
        //                         '.$account->blk_lot.', '.$account->street.', '.($account->building_name ?? '').($account->subdivision_building ?? '').'
        //                     </td>
        //                     <td>'.($account->red_tag ? 'Red Tag' : '').'</td>
        //               </tr>';
        // }
        foreach ($accounts as $key => $account) {
            $html .= '<tr>
                            <td style="text-align: center;>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="accountRadio" id="accountRadio' . $key . '" value="' . $account->crm_id . '">
                                </div>
                            </td>
                            <td>' . htmlspecialchars($account->customer_id) . '</td>
                            <td>' . htmlspecialchars($account->lastname . ', ' . $account->firstname . ' ' . $account->middlename) . '</td>
                            <td>
                            ' . htmlspecialchars($account->blk_lot . ', ' . $account->street . ($account->building_name ? ', ' . $account->building_name : '') . ($account->subdivision_village ? ', ' . $account->subdivision_village : '') . ($account->city ? ', ' . $account->city : '')) . '
                            </td>
                            <td>' . htmlspecialchars($account->spc_category ? $account->spc_category->name : 'Not Found (Might be outdated)') . '</td>
                            <td>' . htmlspecialchars($account->spc_subcat ? $account->spc_subcat->name : 'Not Found (Might be outdated)') . '</td>
                            <td>' . ($account->red_tag ? 'Red Tag' : '') . '</td>
                      </tr>';
        }

        $html .= '</tbody>
                </table>
                </div>';

        return response()->json(['status' => 1, 'html' => $html]);
    }

    public function closeRequest(Request $request)
    {
        $this->authorize('access', SrsRequest::class);

        $data = $request->validate([
            'req_id' => 'required|string|exists:srs_requests,request_id'
        ]);

        $srsRequest = SrsRequest::where('request_id', $data['req_id'])->firstOrFail();

        $closeStatus = $srsRequest->statuses->firstWhere('name', 'Closed');

        if ($closeStatus) {
            return response()->json(['status' => 0, 'srs' => $srsRequest->request_id, 'closedBy' => $closeStatus->pivot->created_at->diffForHumans() . ' by ' . $closeStatus->pivot->action_by]);
        }

        $srsRequest->status = 4;
        $srsRequest->save();
        $srsRequest->statuses()->attach(4, ['action_by' => Auth::user()->name]);

        return response()->json(['status' => 1, 'srs' => $srsRequest->request_id]);
    }

    public function export(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);

        // return Excel::download(new SrsRequestsExport, 'srs_requests.xlsx');
        return (new SrsRequestsExport($data['year'], $data['period'], $data['status'], $data['sub_select']))->download('srs_requests.xlsx');
    }

    public function redTagExport(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date_format:Y-m-d'
        ]);

        return (new CrmRedTagExport($data['from'], $data['to']))->download('crm_redtag.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);


        $srsRequests = SrsRequest::with(['category' => function ($query) {
            $query->select('id', 'name');
        }, 'subCategory' => function ($query) {
            $query->select('id', 'name');
        }, 'hoa' => function ($query) {
            $query->select('id', 'name');
        }])
            ->when($data['period'] == 1, function ($q) {
                return $q->whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()]);
            })
            ->when($data['period'] == 2, function ($q) use ($data) {
                return $q->whereYear('created_at', $data['year'])
                    ->whereMonth('created_at', $data['sub_select']);
            })
            ->when($data['period'] == 3, function ($q) use ($data) {
                return $q->whereYear('created_at', $data['year'])
                    ->when($data['sub_select'] == 1, function ($q) {
                        return $q->whereMonth('created_at', '>=', 1)
                            ->whereMonth('created_at', '<=', 3);
                    })
                    ->when($data['sub_select'] == 2, function ($q) {
                        return $q->whereMonth('created_at', '>=', 4)
                            ->whereMonth('created_at', '<=', 6);
                    })
                    ->when($data['sub_select'] == 3, function ($q) {
                        return $q->whereMonth('created_at', '>=', 7)
                            ->whereMonth('created_at', '<=', 9);
                    })
                    ->when($data['sub_select'] == 4, function ($q) {
                        return $q->whereMonth('created_at', '>=', 10)
                            ->whereMonth('created_at', '<=', 12);
                    });
            })
            ->when($data['period'] == 4, function ($q) use ($data) {
                return $q->whereYear('created_at', $data['year']);
            })
            ->when($data['status'] == 1, function ($q) {
                return $q->withTrashed();
            })
            ->when($data['status'] == 2, function ($q) {
                return $q->where('status', '<', 4);
            })
            ->when($data['status'] == 3, function ($q) {
                return $q->where('status', 4);
            })
            ->when($data['status'] == 4, function ($q) {
                return $q->onlyTrashed();
            })
            ->select('request_id', 'first_name', 'middle_name', 'last_name', 'category_id', 'sub_category_id', 'hoa_id', 'created_at')
            ->orderBy('created_at')
            ->get();

        $categoryGroup = $srsRequests->groupBy('category.name');
        $residentSubGroup = collect([]);
        $nonResidentSubGroup = collect([]);
        $commercialSubGroup = collect([]);

        if (isset($categoryGroup['Resident'])) {
            $residentSubGroup = $categoryGroup['Resident']->groupBy('subCategory.name')->sortDesc();
        } else {
            $categoryGroup['Resident'] = collect([]);
        }

        if (isset($categoryGroup['Non-resident'])) {
            $nonResidentSubGroup = $categoryGroup['Non-resident']->groupBy('subCategory.name')->sortDesc();
        } else {
            $categoryGroup['Non-resident'] = collect([]);
        }

        if (isset($categoryGroup['Commercial'])) {
            $commercialSubGroup = $categoryGroup['Commercial']->groupBy('subCategory.name')->sortDesc();
        } else {
            $categoryGroup['Commercial'] = collect([]);
        }


        $period = '';
        $time = '';

        if ($data['period'] == 1) {
            $period = 'WEEKLY';
            $time = today()->startOfWeek()->format('M d, Y') . ' - ' . today()->endOfWeek()->format('M d, Y');
        } elseif ($data['period'] == 2) {
            $period = 'MONTHLY';
            $time = Carbon::createFromDate($data['year'], $data['sub_select'], 1)->format('F Y');
        } elseif ($data['period'] == 3) {
            $period = 'QUARTERLY';
            $time = 'Q' . $data['sub_select'] . ' ' . $data['year'];
        } elseif ($data['period'] == 4) {
            $period = 'ANNUAL';
            $time = $data['year'];
        }

        $pdf = PDF::loadView('exports.srs_tickets', compact('srsRequests', 'period', 'time', 'categoryGroup', 'residentSubGroup', 'nonResidentSubGroup', 'commercialSubGroup'))->setPaper('a4', 'portrait');
        //return $pdf->download('srs_tickets.pdf');
        return $pdf->stream();
    }

    public function destroyTest()
    {
        $srsRequests = SrsRequest::withTrashed()
            ->whereIn('request_id', ['2329-0802-00090'])
            ->get();


        foreach ($srsRequests as $srsRequest) {

            // $path = 'bffhai/'.$srsRequest->created_at->format('Y/m/d').'/'.$this->getCategoryName($srsRequest->category_id).'/'.$srsRequest->subCategory->name.'/'.($srsRequest->hoa_id ?: '0').'/'.$srsRequest->request_id;
            $path = 'bffhai/' . $srsRequest->created_at->format('Y') . '/' . ($srsRequest->hoa_id ?: '0') . '/' . $this->getCategoryName($srsRequest->category_id) . '/' . $srsRequest->created_at->format('m') . '/' . $srsRequest->first_name . '_' . $srsRequest->last_name;

            if (!$srsRequest->trashed()) {
                $srsRequest->delete();
            }


            if (is_dir(storage_path('app/' . $path))) {
                Storage::deleteDirectory($path);
            }
        }



        dd('deleted');
    }

    public function resendBulk(Request $request)
    {
        $auth_emails = [
            'test@test.com',
            'itqa@atomitsoln.com',
            'lito.tampis@atomitsoln.com',
            'srsadmin@atomitsoln.com'
        ];

        if (!in_array(auth()->user()->email, $auth_emails)) {
            abort(404);
        }

        try {
            $currentDate = date('Y-m-d');

            $srs_numbers = SrsRequest::query()
                ->whereBetween('created_at', ['2024-01-01', '2024-03-31'])
                ->where('status', 0)
                ->where('category_id', 1)
                ->whereNotIn('sub_category_id', [7, 8])
                ->where('hoa_notif_resend', 0)
                ->where('hoa_id', '!=', 73)
                ->where('hoa_renotif_at', null)
                ->select('request_id', 'email', 'created_at', 'hoa_id')
                ->whereNotIn('email', [
                    'test@test.com',
                    'ivan.deposoy@atomitsoln.com',
                    'srsadmin@atomitsoln.com',
                    'itqa@atomitsoln.com'
                ])
                ->orderBy('created_at', 'desc')
                ->limit($request->count);

            // dd($srs_numbers->get(), $srs_numbers->count());
        } catch (\Exception $e) {
            dd($e);
        }


        $sentEmails = [];

        // dd($srs_count, $srs_numbers->get());

        foreach ($srs_numbers->get() as $number) {
            $sent = $this->sendMail($number->request_id, $request->email, $request->pw);

            if ($sent != null) {
                $sentEmails[] = $sent;
            }

            sleep(12);
        }

        $updated_srs_numbers = SrsRequest::query()
            ->whereBetween('created_at', ['2024-01-01', '2024-03-31'])
            ->where('status', 0)
            ->where('category_id', 1)
            ->whereNotIn('sub_category_id', [7, 8])
            ->where('hoa_notif_resend', 0)
            ->where('hoa_id', '!=', 73)
            ->where('hoa_renotif_at', null)
            ->select('request_id', 'email', 'created_at', 'hoa_id')
            ->whereNotIn('email', [
                'test@test.com',
                'ivan.deposoy@atomitsoln.com',
                'srsadmin@atomitsoln.com',
                'itqa@atomitsoln.com'
            ])
            ->orderBy('created_at', 'desc')
            ->limit($request->count);


        $srs_count = $updated_srs_numbers->count();

        dd($sentEmails, $srs_count);
    }

    public function sendMail($request_id, $senderEmail, $senderPW)
    {
        $srsRequest = SrsRequest::with('hoa')
            ->where('request_id', $request_id)
            ->first();

        // Get the current date in 'Y-m-d' format (year-month-day)
        $currentDate = date('Y-m-d');

        // Extract the date part from $srsRequest->hoa_renotif_at
        $srsRequestDate = substr($srsRequest->hoa_renotif_at, 0, 10); // Assuming the date format is 'Y-m-d H:i:s'

        // Compare the dates
        if ($srsRequestDate === $currentDate) {
            // Dates match (same year, month, and day)
            return;
        }

        try {
            $mailFrom = "bffhai@zn.donotreply.notification.znergee.com";

            $sentToEmails = [];

            $srn = Crypt::encrypt($srsRequest->request_id);

            if ($srsRequest->hoa && $srsRequest->hoa->type == 0) {
                // \Log::info('Attempting to resend for approval. Email: ' + $srsRequest->request_id);
                $sentToEmails[] = 'SRS # ' . $srsRequest->request_id;

                if ($srsRequest->hoa->emailAdd1) {
                    $sentToEmails[] = 'Email is sent to ' . $srsRequest->hoa->emailAdd1;

                    $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

                    // \Log::info('Attempting to resend to SRS # ' . $srsRequest->request_id .'. Approvers: ' . $srsRequest->hoa->emailAdd1);

                    Mail::mailer('smtp_2')->to($srsRequest->hoa->emailAdd1)->send(new RequestSubmitted($srsRequest, $url, $mailFrom));
                }

                if ($srsRequest->hoa->emailAdd2) {
                    $sentToEmails[] = 'Email is sent to ' . $srsRequest->hoa->emailAdd2;

                    $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

                    // \Log::info('Attempting to resend to SRS # ' . $srsRequest->request_id .'. Approvers: ' . $srsRequest->hoa->emailAdd2);

                    Mail::mailer('smtp_2')->to($srsRequest->hoa->emailAdd2)->send(new RequestSubmitted($srsRequest, $url, $mailFrom));
                }

                if ($srsRequest->hoa->emailAdd3) {
                    $sentToEmails[] = 'Email is sent to ' . $srsRequest->hoa->emailAdd3;

                    $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(5), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

                    // \Log::info('Attempting to resend to SRS # ' . $srsRequest->request_id .'. Approvers: ' . $srsRequest->hoa->emailAdd3);

                    Mail::mailer('smtp_2')->to($srsRequest->hoa->emailAdd3)->send(new RequestSubmitted($srsRequest, $url, $mailFrom));
                }

                $srsRequest->update([
                    'hoa_notif_resend' => 1,
                    'hoa_renotif_at' => now(),
                ]);

                return $sentToEmails;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
