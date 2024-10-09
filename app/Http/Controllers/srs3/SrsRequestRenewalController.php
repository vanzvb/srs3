<?php

namespace App\Http\Controllers\srs3;

use App\Models\SrsHoa;
use App\Models\CrmMain;
use App\Models\CrmVehicle;
use App\Models\SrsRequest;
use Illuminate\Support\Str;
use App\Mail\RequestRenewal;
use App\Models\SrsCategories;
use Illuminate\Http\Request;
use App\Models\SrsRequirement;
use App\Models\SrsRenewalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Traits\WithCaptcha;
use App\Http\Controllers\Controller;
use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\CRMXI3_Model\CRMXIMain;
use App\Models\CRMXI3_Model\CRMXISubcat;
use App\Models\SrsSubCategories;

class SrsRequestRenewalController extends Controller
{
    use WithCaptcha;

    private function getNextId($category, $subCategory)
    {
        $today = now();
        $series = $today->format('y') . $category . $subCategory . '-' . $today->format('d') . $today->format('m') . '-';

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

    public function index()
    {
        return view('srs3.request.renewal');
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
                        $crm = CRMXIMain::where('email', $value)->first();
                        if (!$crm) {
                            $fail('The ' . $attribute . ' does not exist in our records as an email.');
                        }
                        // Set a flag that the input is an email
                        $request->merge(['is_email' => true]);
                    } else {
                        // It's not an email, so check if it's an account_id
                        $crm = CRMXIMain::where('account_id', $value)->first();
                        if (!$crm) {
                            $fail('The account ID does not exist in our records.');
                        }
                        // Set a flag that the input is an account ID
                        $request->merge(['is_email' => false]);
                    }
                },
            ],
        ]);

        // Now, you can check the flag after validation
        if ($request->is_email) {
            $crm = CRMXIMain::where('email', $request->email)->first();
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

        // category_id 2 = Resident
        if ($crm->category_id == 2) {
            return response()->json(['errors' => ['invalid_category' => 'Renewal is only available to Residents']], 400);
        }

        // sub_category_id 45, 46, 47 = Commercial Tricycle BFTODA (TRIBF)/Tricycle DASATA (TRID)/Tricycle LTSODA (TRIL)

        if ($crm->sub_category_id == 45 || $crm->sub_category_id == 46 || $crm->sub_category_id == 47) {
            return response()->json(['errors' => ['invalid_sub_category' => 'Renewal is not available for this account']], 400);
        }

        $token = uniqid();

        $crmId = Crypt::encrypt($crm->crm_id);
        $email = Crypt::encrypt($request->email);
        $refToken = Crypt::encrypt($token);
        $url = URL::temporarySignedRoute('request.v3.user-renewal', now()->addDays(3), ['key' => $crmId, 'ref' => $email, 'tkn' => $refToken]);

        $renewalRequest = new SrsRenewalRequest();
        $renewalRequest->crm_main_id = $crm->crm_id;
        $renewalRequest->email = $crm->email;
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

        // CRMXI

        $crm = CRMXIMain::with(['CRMXIvehicles', 'CRMXIcategory', 'CRMXIsubCategory'])
            ->where('crm_id', $crmId)
            ->where('email', $email)
            ->firstOrFail();

        $crmxiCategories = CRMXICategory::all();
        $crmxiSubCategories = CRMXISubcat::all();
        $crmxiHoas = CRMXIHoa::all();


        // TEST
        // $crm = CrmMain::with(['vehicles', 'category', 'subCategory'])
        //                 ->where('crm_id', $crmId)
        //                 ->where('email', $email)
        //                 ->firstOrFail();


        // no changes in 3.0
        $renewalRequest = SrsRenewalRequest::where('crm_main_id', $crm->crm_id)
            ->where('email', $crm->email)
            ->where('token', $token)
            ->where('status', 0)
            ->firstOrFail();

        $srsCategories = SrsCategories::all();
        $srsSubCategories = SrsSubCategories::all();

        // $requirements = SrsRequirement::with(['subCategories' => function ($query) {
        //                     $query->select('spc_subcat.id');
        //                 }])
        //                 ->whereHas('subCategories', function ($query) use ($crm) {
        //                     $query->where('spc_subcat.id', $crm->sub_category_id);
        //                 })
        //                 ->select('id', 'name', 'description', 'required')
        //                 ->get();

        // Valid ID or Other Requirements no changes in 3.0
        $requirements = SrsRequirement::where('id', 10)
            ->select('id', 'name', 'description', 'required')
            ->get();


        session(['sr_rnw-cid' => $crmId, 'sr_rnw-eml' => $email]);

        // return view('srs.request.user_renewal', compact('crm', 'requirements', 'hoas', 'crmHoaId'));    
        // return view('srs3.request.user_renewal', compact('crm', 'requirements', 'hoas', 'crmHoaId','srsCategories','srsSubCategories', 'crmxiCategories', 'crmxiSubCategories'));   
        return view('srs3.request.user_renewal', compact('crm', 'requirements', 'crmxiCategories', 'crmxiSubCategories', 'crmxiHoas'));
        // return view('srs3.request.user_renewal_backup', compact('crm', 'requirements', 'hoas', 'crmHoaId','srsCategories'));  
    }

    public function processRenewal(Request $request)
    {
        dd($request);
        // $request->validate([
        //     'vref' => 'required|array',
        //     'v_or' => 'required|array',
        //     'v_or' => 'file|mimes:jpg,png,jpeg,pdf',
        //     'or.*' => 'file|mimes:jpg,png,jpeg,pdf',
        //     'cr.*' => 'file|mimes:jpg,png,jpeg,pdf',
        // ], [], ['or' => 'OR']);


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

        // $request->validate([
        //     'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
        //         $response = $this->validateCaptcha($value);

        //         if (!$response) {
        //             $fail('We have detected unusual activity. Please try again.');
        //         }
        //     }],
        //     'hoa'       => 'nullable|integer|exists:srs_hoas,id',
        //     'vref'      => 'required|array',
        //     'v_or'      => 'required|array',
        //     'v_or.*'    => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'or.*'      => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'cr.*'      => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'new_plate_no.*' => 'string|nullable|max:100',
        //     // 'hoa_endorsement' => 'file|mimes:jpg,png,jpeg,pdf',
        //     'lease_contract' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'tct' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'business_clearance' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'deed_of_assignment' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'proof_of_ownership' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'proof_of_residency' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'bffhai_biz_clearance' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'valid_id_other_requirement' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'other_documents_2' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'other_documents_3' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'nbi_police_clearance' => 'file|mimes:jpg,png,jpeg|max:5120',
        //     'general_information_sheet' => 'file|mimes:jpg,png,jpeg|max:5120',
        // ], [
        //     'g-recaptcha-response.required' => 'Please complete the reCAPTCHA',
        //     'vref.required' => 'Vehicle for renewal is required',
        //     'v_or.required' => 'Vehicle for renewal OR is required',
        //     'v_or.*.mimes' => 'Vehicle for renewal OR must be an image file (jpg, png, jpeg)',
        //     'v_or.*.max' => 'Vehicle for renewal OR must not exceed 5MB',
        //     'or.*.mimes' => 'Vehicle for renewal OR must be an image file (jpg, png, jpeg)',
        //     'or.*.max' => 'Vehicle for renewal OR must not exceed 5MB',
        //     'cr.*.mimes' => 'Vehicle for renewal CR must be an image file (jpg, png, jpeg)',
        //     'cr.*.max' => 'Vehicle for renewal CR must not exceed 5MB',
        //     'lease_contract.mimes' => 'Lease Contract must be an image file (jpg, png, jpeg)',
        //     'lease_contract.max' => 'Lease Contract must not exceed 5MB',
        //     'tct.mimes' => 'TCT must be an image file (jpg, png, jpeg)',
        //     'tct.max' => 'TCT must not exceed 5MB',
        //     'business_clearance.mimes' => 'Business Clearance must be an image file (jpg, png, jpeg)',
        //     'business_clearance.max' => 'Business Clearance must not exceed 5MB',
        //     'deed_of_assignment.mimes' => 'Deed of Assignment must be an image file (jpg, png, jpeg)',
        //     'deed_of_assignment.max' => 'Deed of Assignment must not exceed 5MB',
        //     'proof_of_ownership.mimes' => 'Proof of Ownership must be an image file (jpg, png, jpeg)',
        //     'proof_of_ownership.max' => 'Proof of Ownership must not exceed 5MB',
        //     'proof_of_residency.mimes' => 'Proof of Residency must be an image file (jpg, png, jpeg)',
        //     'proof_of_residency.max' => 'Proof of Residency must not exceed 5MB',
        //     'bffhai_biz_clearance.mimes' => 'BFFHAI Business Clearance must be an image file (jpg, png, jpeg)',
        //     'bffhai_biz_clearance.max' => 'BFFHAI Business Clearance must not exceed 5MB',
        //     'valid_id_other_requirement.mimes' => 'Valid ID or Other Requirement must be an image file (jpg, png, jpeg)',
        //     'valid_id_other_requirement.max' => 'Valid ID or Other Requirement must not exceed 5MB',
        //     'other_documents_2.mimes' => 'Other Documents 2 must be an image file (jpg, png, jpeg)',
        //     'other_documents_2.max' => 'Other Documents 2 must not exceed 5MB',
        //     'other_documents_3.mimes' => 'Other Documents 3 must be an image file (jpg, png, jpeg)',
        //     'other_documents_3.max' => 'Other Documents 3 must not exceed 5MB',
        //     'nbi_police_clearance.mimes' => 'NBI/Police Clearance must be an image file (jpg, png, jpeg)',
        //     'nbi_police_clearance.max' => 'NBI/Police Clearance must not exceed 5MB',
        //     'general_information_sheet.mimes' => 'General Information Sheet must be an image file (jpg, png, jpeg)',
        //     'general_information_sheet.max' => 'General Information Sheet must not exceed 5MB',
        // ], [
        //     'vref' => 'Renewal Vehicle',
        //     'or' => 'OR',
        //     'new_plate_no.*' => 'New Plate No.',
        //     'v_or' => 'Vehicle OR',
        //     'cr' => 'CR',
        //     'lease_contract' => 'Lease Contract',
        //     'tct' => 'TCT',
        //     'business_clearance' => 'Business Clearance',
        //     'deed_of_assignment' => 'Deed of Assignment',
        //     'proof_of_ownership' => 'Proof of Ownership',
        //     'proof_of_residency' => 'Proof of Residency',
        //     'bffhai_biz_clearance' => 'BFFHAI Business Clearance',
        //     'valid_id_other_requirement' => 'Valid ID or Other Requirement',
        //     'other_documents_2' => 'Other Documents 2',
        //     'other_documents_3' => 'Other Documents 3',
        //     'nbi_police_clearance' => 'NBI/Police Clearance',
        //     'general_information_sheet' => 'General Information Sheet',
        // ]);

        $crm = CrmMain::with(['vehicles'])
            ->where('crm_id', $crmId)
            ->where('email', $crmEmail)
            ->firstOrFail();

        $renewalRequest = SrsRenewalRequest::where('crm_main_id', $crm->crm_id)
            ->where('email', $crm->email)
            ->where('token', $reqToken)
            ->where('status', 0)
            ->firstOrFail();

        // $hoa = SrsHoa::where('name', $crm->hoa)->first();

        $srsRequestController = new SrsRequestController();

        $srsRequest = new SrsRequest();
        $srsRequest->request_id = $srsRequestController->getNextId($crm->category_id, $crm->sub_category_id);
        $srsRequest->category_id = $crm->category_id;
        $srsRequest->sub_category_id = $crm->sub_category_id;
        $srsRequest->first_name = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->firstname))));
        $srsRequest->last_name = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->lastname))));
        $srsRequest->middle_name =  strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->middlename))));
        $srsRequest->house_no = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->blk_lot))));
        $srsRequest->street = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->street))));
        $srsRequest->building_name = $crm->building_name ? strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->building_name)))) : NULL;
        $srsRequest->subdivision_village = $crm->subdivision_village ? strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->subdivision_village)))) : NULL;
        $srsRequest->city = $crm->city ? strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $crm->city)))) : NULL;
        // $srsRequest->hoa_id = ($crm->hoa && $hoa) ? $hoa->id : NULL;
        $srsRequest->hoa_id = $request->hoa;
        $srsRequest->contact_no = $crm->main_contact;
        $srsRequest->email = $crm->email;

        $srsRequest->created_at = now();
        $srsRequest->updated_at = now();

        $srsRequest->load(['category' => function ($query) {
            $query->select('id', 'name');
        }, 'hoa']);

        $path = 'bffhai/' . $srsRequest->created_at->format('Y') . '/' . ($srsRequest->hoa_id ?: '0') . '/' . strtolower($srsRequest->category->name) . '/' . $srsRequest->created_at->format('m') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name));
        $filePath = $srsRequest->created_at->format('Y-m-d') . '/' . stripslashes(str_replace('/', '', $srsRequest->first_name . '_' . $srsRequest->last_name)) . '/' . ($srsRequest->hoa_id ?: '0') . '/' . $srsRequest->category_id;

        $renewVehicles = $crm->vehicles->whereIn('id', $request->vref);
        $vehicles = [];

        foreach ($renewVehicles as $renewVehicle) {
            $vehicle = new CrmVehicle();
            $vehicle->srs_request_id = $srsRequest->request_id;
            $vehicle->req_type = 1;
            $vehicle->plate_no = strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $renewVehicle->plate_no))));
            $vehicle->brand = strip_tags($renewVehicle->brand);
            $vehicle->series = strip_tags($renewVehicle->series);
            $vehicle->year_model = strip_tags($renewVehicle->year_model);
            // $vehicle->old_sticker_no = strip_tags($request->sticker_no[$renewVehicle->id]);
            $vehicle->old_sticker_no = strip_tags($renewVehicle->new_sticker_no);
            $vehicle->color = strip_tags($renewVehicle->color);
            $vehicle->type = strip_tags($renewVehicle->type);
            $vehicle->req1 = $srsRequestController->storeFile($path, $request->v_or[$renewVehicle->id]);
            $vehicle->or_path = $vehicle->req1 . '/' . $filePath;
            $vehicle->cr = $renewVehicle->cr;
            $vehicle->cr_path = $renewVehicle->cr_path;

            if (!$renewVehicle->srs_request_id) {
                $vehicle->cr_from_crm = 1;
            }

            if (isset($request->new_plate_no_chk[$renewVehicle->id])) {
                $vehicle->plate_no_remarks = strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $request->new_plate_no[$renewVehicle->id]))));
            }

            if (isset($request->new_color_chk[$renewVehicle->id])) {
                $vehicle->color_remarks = strip_tags(Str::title(trim(preg_replace('/\s+/', ' ', $request->new_color[$renewVehicle->id]))));
            }

            $vehicles[] = $vehicle;
        }

        $newVehicleCounter = 0;
        if ($request['plate_no']) {
            foreach ($request['plate_no'] as $item1) {
                $vehicle = new CrmVehicle();
                $vehicle->srs_request_id = $srsRequest->request_id;

                $existingVehicle = $crm->vehicles->where('plate_no', $item1)->first();

                if ($existingVehicle) {
                    $vehicle->req_type = 1;
                    $vehicle->plate_no = strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $existingVehicle->plate_no))));
                    $vehicle->brand = strip_tags($existingVehicle->brand);
                    $vehicle->series = strip_tags($existingVehicle->series);
                    $vehicle->year_model = strip_tags($existingVehicle->year_model);
                    $vehicle->old_sticker_no = strip_tags($existingVehicle->new_sticker_no);
                    $vehicle->color = strip_tags($existingVehicle->color);
                    $vehicle->type = strip_tags($existingVehicle->type);
                    $vehicle->req1 = $srsRequestController->storeFile($path, $request->or[$newVehicleCounter]);
                    $vehicle->or_path = $vehicle->req1 . '/' . $filePath;
                    $vehicle->cr = $existingVehicle->cr;
                    $vehicle->cr_path = $existingVehicle->cr_path;
                } else {
                    $vehicle->req_type = 0;
                    $vehicle->plate_no = strip_tags(Str::upper(trim(preg_replace('/\s+/', '', $item1))));
                    $vehicle->brand = strip_tags($request->brand[$newVehicleCounter]);
                    $vehicle->series = strip_tags($request->series[$newVehicleCounter]);
                    $vehicle->year_model = strip_tags($request->year_model[$newVehicleCounter]);
                    $vehicle->color = strip_tags($request->color[$newVehicleCounter]);
                    $vehicle->type = strip_tags($request->type[$newVehicleCounter]);
                    $vehicle->req1 = $srsRequestController->storeFile($path, $request->or[$newVehicleCounter]);
                    $vehicle->or_path = $vehicle->req1 . '/' . $filePath;
                    $vehicle->cr = $srsRequestController->storeFile($path, $request->cr[$newVehicleCounter]);
                    $vehicle->cr_path = $vehicle->cr . '/' . $filePath;
                }

                $newVehicleCounter++;
                $vehicles[] = $vehicle;
            }
        }

        $files = [];

        if ($request->has('hoa_endorsement')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['hoa_endorsement']), 2, $filePath);
        }

        if ($request->has('lease_contract')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['lease_contract']), 3, $filePath);
        }

        if ($request->has('tct')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['tct']), 4, $filePath);
        }

        if ($request->has('business_clearance')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['business_clearance']), 5, $filePath);
        }

        if ($request->has('deed_of_assignment')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['deed_of_assignment']), 6, $filePath);
        }

        if ($request->has('proof_of_ownership')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['proof_of_ownership']), 7, $filePath);
        }

        if ($request->has('proof_of_residency')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['proof_of_residency']), 8, $filePath);
        }

        if ($request->has('bffhai_biz_clearance')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['bffhai_biz_clearance']), 9, $filePath);
        }

        if ($request->has('valid_id_other_requirement')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['valid_id_other_requirement']), 10, $filePath);
        }

        if ($request->has('other_documents_2')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['other_documents_2']), 12, $filePath);
        }

        if ($request->has('other_documents_3')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['other_documents_3']), 13, $filePath);
        }

        if ($request->has('nbi_police_clearance')) {
            $files[] = $srsRequestController->storeRequirementFile($srsRequestController->storeFile($path, $request['nbi_police_clearance']), 14, $filePath);
        }

        if ($request->has('general_information_sheet')) {
            $files[] = $this->storeRequirementFile($this->storeFile($path, $request['general_information_sheet']), 15, $filePath);
        }

        $srn = Crypt::encrypt($srsRequest->request_id);

        $srsRequest->save();

        $crm->hoa = $request->hoa ? $srsRequest->hoa->name : '';
        $crm->save();

        if ($vehicles) {
            $srsRequest->vehicles()->saveMany($vehicles);
        }

        if ($files) {
            $srsRequest->files()->saveMany($files);
        }

        $renewalRequest->status = 1;
        $renewalRequest->save();

        dispatch(new \App\Jobs\SendRequestorNotificationJob($srsRequest, $srsRequest->email, 'renewal'));

        if ($srsRequest->hoa && $srsRequest->hoa->type == 0) {

            if ($srsRequest->hoa->emailAdd1) {
                $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(3), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd1)]);

                dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd1, $url))->delay(now()->addSeconds(10));
            }

            if ($srsRequest->hoa->emailAdd2) {
                $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(3), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd2)]);

                dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd2, $url))->delay(now()->addSeconds(12));
            }

            if ($srsRequest->hoa->emailAdd3) {
                $url = URL::temporarySignedRoute('request.hoa.approval', now()->addDays(3), ['key' => $srn, 'ref' => Crypt::encrypt($srsRequest->hoa->emailAdd3)]);

                dispatch(new \App\Jobs\SendHoaNotificationJob($srsRequest, $srsRequest->hoa->emailAdd3, $url))->delay(now()->addSeconds(14));
            }
        }

        return redirect('/sticker/new')->with('requestAddSuccess', $srsRequest->request_id);
    }

    public function saveProgress(Request $request)
    {
        dd('Here Submit');
        foreach ($request->except('_token') as $key => $value) {
            session([$key => $value]); // Store each input value in the session
        }

        return back(); // Redirect back to the form
    }
}
