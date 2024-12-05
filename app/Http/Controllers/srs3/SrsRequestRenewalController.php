<?php

namespace App\Http\Controllers\srs3;

use \App\Jobs\srs3\SendHoaNotificationJob;
use \App\Jobs\srs3\SendRequestorNotificationJob;
use App\Http\Controllers\Controller;
use App\Http\Controllers\srs3\SrsRequestController;
use App\Mail\RequestRenewal;
use App\Models\CrmMain;
use App\Models\CRMXI3_Model\CRMXIAddress;
use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\CRMXI3_Model\CRMXIMain;
use App\Models\CRMXI3_Model\CRMXISubcat;
use App\Models\CRMXI3_Model\CRMXIVehicleOwnershipStatus;
use App\Models\CRMXI3_Model\CRXMIVehicle;
use App\Models\SRS3_Model\SrsRequest;
use App\Models\SrsCategories;
use App\Models\SrsHoa;
use App\Models\SrsRenewalRequest;
use App\Models\SrsRequirement;
use App\Models\SrsSubCategories;
use App\Traits\WithCaptcha;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Changed in SRS3
// use App\Models\SrsRequest;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SrsRequestRenewalController extends Controller
{
    use WithCaptcha;

    private function getNextId($category, $subCategory)
    {
        $today = now();
        $series = $today->format('y') . $category . $subCategory . '-' . $today->format('d') . $today->format('m') . '-';

        // Use a database transaction and lock
        return DB::transaction(function () use ($series) {
            // Acquire a lock on the table to prevent race conditions
            DB::table('srs3_requests')->lockForUpdate()->get();

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
        });
    }

    public function index()
    {
        return view('srs3.request.renewal');
    }
    public function vanz()
    {
        return view('srs3.request.vanz');
    }

    public function renewalCheck(Request $request)
    {

        $request->validate([
            'email' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        // It's a valid email format
                        $crm1 = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
                            ->where('main_email', $value)
                            ->orWhere('owner_email', $value)
                            ->first();
                        // dd($crm);
                        if (!$crm1) {
                            $fail('The ' . $attribute . ' does not exist in our records as an email.');
                        }
                        // Set a flag that the input is an email
                        $request->merge(['is_email' => true]);
                    } else {
                        // It's not an email, so check if it's an account_id
                        $crm1 = CRMXIMain::where('account_id', $value)->first();
                        if (!$crm1) {
                            $fail('The account ID does not exist in our records.');
                        } elseif (empty($crm1->email)) {
                            // If the account_id exists but has no associated email
                            $fail('This account ID has no email associated.');
                        }
                        // Set a flag that the input is an account ID
                        $request->merge(['is_email' => false]);
                    }
                },
            ],
        ]);
        
        
        // Now, you can check the flag after validation
        if ($request->is_email) {
            $checkCRMMainEmail = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
            ->where('main_email', $request->email)
            ->first();
            $crm = CRMXIMain::where('email', $request->email)->first();
            if (!$checkCRMMainEmail) {
                // If not found in main_email, check in owner_email
                $checkCRMOwnerEmail = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
                    ->where('owner_email', $request->email)
                    ->first();

                $crm = CRMXIMain::where('account_id', $checkCRMOwnerEmail->main_account_id)->first();
                
                if (!$checkCRMOwnerEmail) {
                    // If not found in either column, return an error
                    return response()->json(['errors' => ['email_not_found' => 'Invalid email address, please contact BFFHAI CLUBHOUSE']], 400);
                }

            }

           
        } else {
            // if the input is account id, we will alter the request->email and change it to email instead of account id
            $crm = CRMXIMain::where('account_id', $request->email)->first();
            $request->merge(['email' => $crm->email]);
        }

        
        // for logs
        if (!$crm) {
            $ip = $request->ip();
            $ipUrl = 'http://ip-api.com/json/';
            $ipLocation = json_decode(file_get_contents($ipUrl), true);
            DB::table('srs_invalid_renewal_logs')->insert([
                'datetime'      => now(),
                'email'         => $request->email,
                'ip_address'    => $ip,
                'location'      => $ipLocation['city']
            ]);

            return response()->json(['errors' => ['email_not_found' => 'Invalid email address, please contact BFFHAI CLUBHOUSE']], 400);
        }
        

        $mainEmailExists = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
        ->where('main_email', $request->email)
        ->exists();
        if ($mainEmailExists) {

            $crmVehicleCount = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
            ->where('main_account_id',  $crm->account_id)
            ->where('main_email',  $request->email) // Main Email 
            ->where('category_id', 1) // RESIDENT - 1
            ->whereIn('sub_category_id', [1, 4]) // HOMEOWNER - 1, PROPERTY OWNER - 4
            ->where('hoa_type', 0) // HOA-MEMBER - 0
            // ->where('vehicle_ownership_status_id', 1) // REGISTERED OWNER - 1
            ->where(function ($query){
                $query->where('vehicle_ownership_status', 1) 
                    ->orWhereNull('vehicle_ownership_status');
            })
            ->get();

        }else{
            $crmVehicleCount = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
            ->where('main_account_id',  $crm->account_id)
            ->where('owner_email',  $request->email) // Owner Email 
            ->where('category_id', 1) // RESIDENT - 1
            ->whereIn('sub_category_id', [1, 4]) // HOMEOWNER - 1, PROPERTY OWNER - 4
            ->where('hoa_type', 0) // HOA-MEMBER - 0
            // ->where('vehicle_ownership_status_id', 1) // REGISTERED OWNER - 1
            ->where(function ($query){
                $query->where('vehicle_ownership_status', 1) 
                    ->orWhereNull('vehicle_ownership_status');
            })
            ->get();
        }

        $count_valid_vehicle = $crmVehicleCount->count();
        if ($count_valid_vehicle == 0) {
            return response()->json(['errors' =>
                ['invalid_category' => 'You are not eligible to renew as of this period, please contact your enclave or bffhai clubhouse for assistance.']
            ], 400);
        } 
        $token = uniqid();

        if (is_null($crm->crm_id) || is_null($request->email)) {
            return response()->json(['errors' => ['message' => 'Something went wrong, Please refresh the page.']], 400);
        }
        
        $crmId = Crypt::encrypt($crm->crm_id);
        $email = Crypt::encrypt($request->email);
        $refToken = Crypt::encrypt($token);
        $url = URL::temporarySignedRoute('request.v3.user-renewal', now()->addDays(3), ['key' => $crmId, 'ref' => $email, 'tkn' => $refToken]);
        
        $renewalRequest = new SrsRenewalRequest();
        // $renewalRequest->crm_main_id = $crm->crm_id;
        // $renewalRequest->email = $crm->email;
        $renewalRequest->crm_main_id = $crm->crm_id;
        $renewalRequest->email = $request->email;
        $renewalRequest->token = $token;
        $renewalRequest->save();

        // if($request->email == "ivan.deposoy@atomitsoln.com") {
        // 	\Log::info('Entered here ivan email');
        // 	dispatch(new \App\Jobs\SendRequestRenewalJob($request->email, $url));
        // } else {
        // 	Mail::to($request->email)->send(new RequestRenewal($request->email, $url));
        // }

        dispatch(new \App\Jobs\srs3\SendRequestRenewalJob($request->email, $url));

        return response()->json(['status' => 1]);
    }

    public function userRenewal(Request $request)
    {
        

        if (!$request->hasValidSignature()) {
            abort(404, 'Link is already expired');
        }

        if (!$request->key) {
            abort(404);
        }
        
        try {
            $crmId = Crypt::decrypt($request->key);
            $email = Crypt::decrypt($request->ref);
            $token = Crypt::decrypt($request->tkn);
        } catch (DecryptException $e) {
            abort(404);
        }
       
        $crmGetEmailUsed = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
        ->where('main_email',  $email)
        ->orWhere('owner_email',  $email)
        ->first();
        $didWeUseMainEmail = false;
        
        if ($crmGetEmailUsed) {
            
            if ($crmGetEmailUsed->main_email === $email) {
                
                $personEmail = $crmGetEmailUsed->main_email;
                
                $crm = CRMXIMain::with(['CRMXIvehicles', 'CRMXIcategory', 'CRMXIsubCategory', 'CRMXIaddress'])
                ->where('crm_id', $crmId)
                ->where('email', $email)
                ->firstOrFail();

                $didWeUseMainEmail = true;
            } elseif ($crmGetEmailUsed->owner_email === $email) {
                $personEmail = $crmGetEmailUsed->owner_email;
                $crm = CRMXIMain::with(['CRMXIvehicles' => function ($query) use ($email) {
                    // Ensure we're checking the vehicleOwner relation properly
                    $query->whereHas('vehicleOwner', function ($ownerQuery) use ($email) {
                        $ownerQuery->where('email', $email);
                    });
                }, 'CRMXIcategory', 'CRMXIsubCategory', 'CRMXIaddress'])
                ->where('crm_id', $crmId) // Ensure CRM record matches the given ID
                ->firstOrFail();

            } else {
                abort(404);
            }

            
        } else {
            abort(404);
        }

        $crmxiCategories = CRMXICategory::all();
        $crmxiSubCategories = CRMXISubcat::all();
        $crmxiHoas = CRMXIHoa::all();

        $renewalRequest = SrsRenewalRequest::where('crm_main_id', $crm->crm_id)
            ->where('email', $personEmail)
            ->where('token', $token)
            ->where('status', 0)
            ->firstOrFail();

        // $srsCategories = SrsCategories::all();
        // $srsSubCategories = SrsSubCategories::all();

        // $requirements = SrsRequirement::with(['subCategories' => function ($query) {
        //                     $query->select('spc_subcat.id');
        //                 }])
        //                 ->whereHas('subCategories', function ($query) use ($crm) {
        //                     $query->where('spc_subcat.id', $crm->sub_category_id);
        //                 })
        //                 ->select('id', 'name', 'description', 'required')
        //                 ->get();

        // If all
        // $vehicleOwnershipTypes = CRMXIVehicleOwnershipStatus::all();
        $vehicleOwnershipTypes = CRMXIVehicleOwnershipStatus::where('id',1)->get();

        // Valid ID or Other Requirements no changes in 3.0
        $requirements = SrsRequirement::where('id', 10)
            ->select('id', 'name', 'description', 'required')
            ->get();

        session(['sr_rnw-cid' => $crmId, 'sr_rnw-eml' => $email]);

        // return view('srs.request.user_renewal', compact('crm', 'requirements', 'hoas', 'crmHoaId'));    
        return view('srs3.request.user_renewal', compact('crm', 'requirements', 'crmxiCategories', 'crmxiSubCategories', 'crmxiHoas', 'personEmail', 'didWeUseMainEmail','vehicleOwnershipTypes'));
    }

    public function processRenewal(Request $request)
    {
        // Get the renewal vehicles directly from the request
        $renewalVehicles = $request->input('renewalVehicles', []);

        // Merge only the renewal vehicles into the request
        $request->merge(['list_of_vehicles' => $renewalVehicles]);

        $request->validate([
            'list_of_vehicles' => 'required|array|min:1',
            'valid_id_other_requirement' => 'file|mimes:jpg,png,jpeg|max:5120',
            'new_plate_no.*' => 'string|nullable|max:100',
        ], [
            'list_of_vehicles.required' => 'There are no vehicles to renew.',
            'list_of_vehicles.min' => 'There must be at least one vehicle to renew.',
            'valid_id_other_requirement.mimes' => 'Valid ID or Other Requirement must be an image file (jpg, png, jpeg)',
            'valid_id_other_requirement.max' => 'Valid ID or Other Requirement must not exceed 5MB',
        ]);
 
        if (!$request->session()->has('sr_rnw-cid') || !$request->session()->get('sr_rnw-eml')) {
            return back()->withErrors(['error' => 'Please Refresh Page and Try Again']);
        }

        $parsedUrl = parse_url(url()->previous());
        parse_str($parsedUrl['query'], $prevUrlParam);

        if (isset($prevUrlParam['ref']) && $prevUrlParam['ref']) {
            try {
                $reqCrmId = Crypt::decrypt($prevUrlParam['key']);
                $reqEmail = Crypt::decrypt($prevUrlParam['ref']);
                $reqToken = Crypt::decrypt($prevUrlParam['tkn']);
            } catch (DecryptException $e) {
                return back()->withErrors(['error' => 'Error']);
            }
        } else {
            return back()->withErrors(['error' => 'Error L96']);
        }

        $crmId = $request->session()->get('sr_rnw-cid');
        $crmEmail = $request->session()->get('sr_rnw-eml');

        if ($reqCrmId != $crmId || $reqEmail != $crmEmail) {
            return back()->withErrors(['error' => 'Error L103']);
        }


        DB::beginTransaction(); // Start the transaction

        try {

            $crmGetEmailUsed = DB::table('v_crmxi3_mains_consolidated_vehicle_info')
            ->where(function($query) use ($crmEmail) {
                $query->where('main_email', $crmEmail)
                      ->orWhere('owner_email', $crmEmail);
            })
            ->where('address_id', $request->input('address_id'))
            ->first();
            
            if ($crmGetEmailUsed) {
                if ($crmGetEmailUsed->main_email === $crmEmail) {
                
                    
                    $personEmail = $crmGetEmailUsed->main_email;
    
                    $crm = CRMXIMain::with(['CRMXIvehicles', 'CRMXIcategory', 'CRMXIsubCategory', 'CRMXIaddress'])
                    ->where('crm_id', $crmId)
                    ->where('email', $crmEmail)
                    ->firstOrFail();

                } elseif ($crmGetEmailUsed->owner_email === $crmEmail) {
                    $personEmail = $crmGetEmailUsed->owner_email;
                    // dd($email);
                    $crm = CRMXIMain::with(['CRMXIvehicles' => function ($query) use ($crmEmail) {
                        // Ensure we're checking the vehicleOwner relation properly
                        $query->whereHas('vehicleOwner', function ($ownerQuery) use ($crmEmail) {
                            $ownerQuery->where('email', $crmEmail);
                        });
                    }, 'CRMXIcategory', 'CRMXIsubCategory', 'CRMXIaddress'])
                    ->where('crm_id', $crmId) // Ensure CRM record matches the given ID
                    ->firstOrFail();
    
                }
            } else {
                abort(404);
            }

            

            $renewalRequest = SrsRenewalRequest::where('crm_main_id', $crm->crm_id)
                // ->where('email', $crm->email)
                ->where('email', $personEmail)
                ->where('token', $reqToken)
                ->where('status', 0)
                ->firstOrFail();

            // Changed namespace to srs 3
            $srsRequestController = new SrsRequestController();

            $srsRequest = new SrsRequest();


            // Generate requests_id
            // account_type - (0 = Individual, 1 = Company)
            $srsRequest->account_type = $crm->account_type;
            $srsRequest->account_id = $crm->account_id;
            $srsRequest->customer_id = $crm->crm_id;

            

            // Old 2.0 is getting hoa from mains (in 3.0 were getting in in crmxi3_address)
            $srsRequest->request_id = $this->getNextId($crmGetEmailUsed->category_id, $crmGetEmailUsed->sub_category_id);
            // $srsRequest->request_id = $srsRequestController->getNextId($crm->category_id, $crm->sub_category_id);
            // $srsRequest->request_id = $srsRequestController->getNextId($crm->category_id, $crm->sub_category_id);

            $srsRequest->first_name = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->firstname))));
            $srsRequest->last_name = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->lastname))));
            $srsRequest->middle_name =  strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->middlename))));
            $srsRequest->civil_status = $crm->civil_status;
            $srsRequest->nationality = $crm->nationality;
            $srsRequest->tin_no = $crm->tin;
            $srsRequest->email = $crm->email;
            $srsRequest->contact_no = $crm->main_contact;
            $srsRequest->secondary_contact = $crm->secondary_contact;  
            $srsRequest->tertiary_contact = $crm->tertiary_contact;

            // SRS3 New Address Format (should get in view $crmGetEmailUsed) since were not using cmrxi3_mains anymore for the address
            // so basically all info  for address came from selected crmxi3_address
            $srsRequest->category_id = $crmGetEmailUsed->category_id;
            $srsRequest->sub_category_id = $crmGetEmailUsed->sub_category_id;
            $srsRequest->hoa_id = $crmGetEmailUsed->hoa;

            // can either use any of 2 columns
            $srsRequest->membership_type = $crmGetEmailUsed->hoa_type;
            $srsRequest->hoa_type = $crmGetEmailUsed->hoa_type;

            $srsRequest->block_no = $crmGetEmailUsed->block ? strip_tags($crmGetEmailUsed->block) : null;
            $srsRequest->lot_no = $crmGetEmailUsed->lot ? strip_tags($crmGetEmailUsed->lot) : null;
            $srsRequest->house_no = $crmGetEmailUsed->house_number ? strip_tags($crmGetEmailUsed->house_number) : null;
            $srsRequest->street = $crmGetEmailUsed->street ? strip_tags($crmGetEmailUsed->street) : null;
            $srsRequest->building_name = $crmGetEmailUsed->building_name ? strip_tags($crmGetEmailUsed->building_name) : null;
            $srsRequest->subdivision_village =  $crmGetEmailUsed->subdivision_village ? strip_tags($crmGetEmailUsed->subdivision_village) : null;
            $srsRequest->city = $crmGetEmailUsed->city ? strip_tags($crmGetEmailUsed->city) : null;
            $srsRequest->zipcode = $crmGetEmailUsed->zipcode ? strip_tags($crmGetEmailUsed->zipcode) : null;
            

            $srsRequest->created_at = now();
            $srsRequest->updated_at = now();

            $srsRequest->load(['category' => function ($query) {
                $query->select('id', 'name');
            }, 'hoa']);

            // OR / CR on vehicle is removed in renewal
            // Pending Patch VB
            $path = 'bffhai/' . $srsRequest->created_at->format('Y') . '/' . ($srsRequest->hoa_id ?: '0') . '/' . strtolower($srsRequest->category->name) . '/' . $srsRequest->created_at->format('m') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name));
            // $path = 'bffhai/' . $srsRequest->created_at->format('Y') . '/' . ($srsRequest->hoa_id ?: '0') . '/' . 'NA' . '/' . $srsRequest->created_at->format('m') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name));
            $filePath = $srsRequest->created_at->format('Y-m-d') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name)) . '/' . ($srsRequest->hoa_id ?: '0') . '/' . $srsRequest->category_id;

            // $renewVehicles = $crm->vehicles->whereIn('id', $request->list_of_vehicles);
            // dd($request->list_of_vehicles);

            $renewVehicles = CRXMIVehicle::whereIn('id', $request->list_of_vehicles)->get();

            // Prepare data for insertion
            $vehiclesDatas = [];
            // $processedVehicleIds = [];

            foreach ($renewVehicles as $renewVehicle) {
                // Only include vehicles that are in the renewal list
                // if (!in_array($renewVehicle->id, $request->renewalVehicles)) {
                //     continue; // Skip vehicles that should not be included
                // }
                // $processedVehicleIds[] = $renewVehicle->id;
                // Build the data array
                $vehiclesDatas[] = [
                    'srs_request_id' => $srsRequest->request_id,
                    'vehicle_id' => $renewVehicle->id,
                    'crm_id' =>  $crm->crm_id,
                    'req_type' => 1,
                    'plate_no' => strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $renewVehicle->plate_no)))),
                    'brand' => strip_tags($renewVehicle->brand),
                    'series' => strip_tags($renewVehicle->series),
                    'year_model' => strip_tags($renewVehicle->year_model),
                    'old_sticker_no' => strip_tags($renewVehicle->new_sticker_no),
                    'color' => strip_tags($renewVehicle->color),
                    'type' => strip_tags($renewVehicle->type),
                    'cr_from_crm' => !$renewVehicle->srs_request_id ? 1 : null,
                    'plate_no_remarks' => isset($request->new_plate_no_chk[$renewVehicle->id]) ? strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $request->new_plate_no[$renewVehicle->id])))) : null,
                    'color_remarks' => isset($request->new_color_chk[$renewVehicle->id]) ? strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $request->new_color[$renewVehicle->id])))) : null,
                    'account_id' => $renewVehicle->account_id,
                    'address_id' => strip_tags($renewVehicle->address_id),
                    'red_tag' => $renewVehicle->red_tag,
                    // 'vehicle_ownership_status_id' => $renewVehicle->vehicle_ownership_status_id,
                    'cr' => $renewVehicle->cr,
                    'req1' => $renewVehicle->req1,
                    'or_path' => $renewVehicle->or_path,
                    'cr_path' => $renewVehicle->cr_path,
                    'vot' => $renewVehicle->vot,
                    'vot_path' => $renewVehicle->vot_path,
                    'vehicle_ownership_status_id' => $request->vehicle_ownership_type[$renewVehicle->id]
                ];
                
            }

            CRXMIVehicle::insert($vehiclesDatas);

            // foreach ($vehiclesDatas as $vehicleData) {
            //     CRXMIVehicle::create($vehicleData);
            // }

            // Debugging output to check the number of inserted records
            // $test = CRXMIVehicle::where('srs_request_id', $srsRequest->request_id)->get();
            // DB::rollBack();
            // dd('Number of vehicles inserted:', $test->count(), $test);
           
            $files = [];

            if ($request->has('valid_id_other_requirement')) {

                $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['valid_id_other_requirement']), 10, $filePath);
            }

            $srn = Crypt::encrypt($srsRequest->request_id);

            $existingRequest = DB::table('srs3_requests')->where('request_id', $srsRequest->request_id)->exists();

            if ($existingRequest) {
                return response()->json(['errors' => ['request_id' => 'The request ID already exists.']], 400);
            }

            $srsRequest->save();

            // $crm->hoa = $request->hoa ? $srsRequest->hoa->name : '';
            // $crm->save();

            if ($files) {
                // if (count($files) > 1) {
                //     throw new Exception('More than one file detected. Only one file is allowed.');
                // }
                $savedFiles = $srsRequest->files()->saveMany($files);

                // Convert the iterable to a collection
                $savedCount = collect($savedFiles)->count();

                if ($savedCount > 1) {
                    throw new Exception('More than one file detected. Only one file is allowed.');
                }

            }

            $renewalRequest->status = 1;
            $renewalRequest->save();
            
            DB::commit();

            // For Requestor
            dispatch(new SendRequestorNotificationJob($srsRequest, $srsRequest->email, 'renewal'));

            if ($srsRequest->hoa && $srsRequest->hoa->type == 0) {

                if ($srsRequest->hoa->emailAdd1) {
                    $url = URL::temporarySignedRoute('request.v3.hoa.approval', now()->addDays(3), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

                    dispatch(new SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd1, $url))->delay(now()->addSeconds(10));
                }

                if ($srsRequest->hoa->emailAdd2) {
                    $url = URL::temporarySignedRoute('request.v3.hoa.approval', now()->addDays(3), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd2)]);

                    dispatch(new SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd2, $url))->delay(now()->addSeconds(12));
                }

                if ($srsRequest->hoa->emailAdd3) {
                    $url = URL::temporarySignedRoute('request.v3.hoa.approval', now()->addDays(3), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd3)]);

                    dispatch(new SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd3, $url))->delay(now()->addSeconds(14));
                }
            }

            return redirect('/v3/sticker/new')->with('requestAddSuccess', $srsRequest->request_id);
        } catch (Exception $e) {
            // If there's an error, rollback the transaction
            DB::rollBack();

            // Handle the error (e.g., log it or return an error response)
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function saveProgress(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            session([$key => $value]); // Store each input value in the session
        }

        return back(); // Redirect back to the form
    }
}
