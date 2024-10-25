<?php

namespace App\Http\Requests;

use App\Traits\WithCaptcha;
use Illuminate\Foundation\Http\FormRequest;

class SrsRequestRequest extends FormRequest
{
    use WithCaptcha;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'g-recaptcha-response' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         $response = $this->validateCaptcha($value);

            //         if (!$response) {
            //             $fail('We have detected unusual activity. Please try again.');
            //         }
            //     }
            // ],
            // 'category' => 'required|integer|exists:spc_categories,id',
            // 'sub_category' => 'required|integer|exists:spc_subcat,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'required|string',
            // 'house_no' => 'required|string',
            // 'street' => 'required|string',
            'building_name' => 'nullable|string',
            'subdivision_village' => 'nullable|string',
            'city' => 'nullable|string',
            'hoa' => 'string|exists:srs_hoas,id',
            'contact_no' => 'required|string|min:7|max:30|regex:/^[0-9+() -]*$/',
            'email' => 'required|email',
            // 'signature' => 'required|string',
            // 'plate_no' => 'required|array',
            // 'req_type' => 'required|array',
            // 'brand' => 'required|array',
            // 'series' => 'required|array',
            // 'year_model' => 'required|array',
            'sticker_no' => 'array',
            // 'v_color' => 'required|array',
            // 'v_type' => 'required|array',
            // 'or' => 'required|array',
            // 'cr' => 'required|array',
            'req_type.*' => 'boolean',
            'plate_no.*' => 'string',
            'brand.*' => 'string',
            'series.*' => 'string',
            'year_model.*' => 'string',
            'sticker_no.*' => 'nullable|string',
            'v_color.*' => 'string',
            'v_type.*' => 'string',
            'or.*' => 'file|mimes:jpg,png,jpeg|max:5120',
            'cr.*' => 'file|mimes:jpg,png,jpeg|max:5120',
            // 'hoa_endorsement' => 'required_if:sub_category,1|file|mimes:jpg,png,jpeg,pdf',
            'lease_contract' => 'required_if:sub_category,2|file|mimes:jpg,png,jpeg|max:5120',
            'tct' => 'required_if:sub_category,4|file|mimes:jpg,png,jpeg|max:5120',
            'business_clearance' => 'required_if:sub_category,3,6|file|mimes:jpg,png,jpeg|max:5120',
            'deed_of_assignment' => 'required_if:sub_category,5|file|mimes:jpg,png,jpeg|max:5120',
            'proof_of_ownership' => 'required_if:sub_category,6,49|file|mimes:jpg,png,jpeg|max:5120',
            'proof_of_residency' => 'required_if:sub_category,7,8,49|file|mimes:jpg,png,jpeg|max:5120',
            'bffhai_biz_clearance' => 'required_if:sub_category,16,17|file|mimes:jpg,png,jpeg|max:5120',
            'valid_id_other_requirement' => 'required_if:sub_category,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,35,36,37,38,40,44,45,46,47,48,49|file|mimes:jpg,png,jpeg|max:5120',
            'other_documents_2' => 'file|mimes:jpg,png,jpeg|max:5120',
            'other_documents_3' => 'file|mimes:jpg,png,jpeg|max:5120',
            'nbi_police_clearance' => 'file|mimes:jpg,png,jpeg|max:5120',
            'general_information_sheet' => 'file|mimes:jpg,png,jpeg|max:5120',
            // Validation 3.0

            
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email Address',
            'contact_no' => 'Contact Number',
            'or' => 'OR',
            'cr' => 'CR',
            'or.*' => 'OR',
            'cr.*' => 'CR'
        ];
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA',
            'hoa_endorsement.required_if' => 'Valid ID or Endorsement by local HOA is required',
            'lease_contract.required_if' => 'Lease Contract is required',
            'tct.required_if' => 'TCT is required',
            'business_clearance.required_if' => 'Business Clearance is required',
            'deed_of_assignment.required_if' => 'Deed of Assignment is required',
            'proof_of_ownership.required_if' => 'Proof of Ownership is required',
            'proof_of_residency.required_if' => 'Proof of Residency is required',
            'bffhai_biz_clearance.required_if' => 'BFFHAI Business Clearance is required',
            'valid_id_other_requirement.required_if' => 'Valid ID or Other Requirement is required',
            'or.*.mimes'    => 'The OR must have a file format of: .JPG, .JPEG, and .PNG',
            'cr.*mimes'     => 'The CR must have a file format of: .JPG, .JPEG, and .PNG',
            'lease_contract.mimes'  => 'The Lease Contract must have a file format of: .JPG, .JPEG, and .PNG',
            'tct.mimes' => 'The TCT must have a file format of: .JPG, .JPEG, and .PNG',
            'business_clearance.mimes' => 'The Business Clearance must have a file format of: .JPG, .JPEG, and .PNG',
            'deed_of_assignment.mimes' => 'The Deed of Assignment must have a file format of: .JPG, .JPEG, and .PNG',
            'proof_of_ownership.mimes' => 'The Proof of Ownership must have a file format of: .JPG, .JPEG, and .PNG',
            'proof_of_residency.mimes' => 'The Proof of Residency must have a file format of: .JPG, .JPEG, and .PNG',
            'bffhai_biz_clearance.mimes' => 'The BFFHAI Business Clearance must have a file format of: .JPG, .JPEG, and .PNG',
            'valid_id_other_requirement.mimes' => 'The Valid ID/Other Requirement must have a file format of: .JPG, .JPEG, and .PNG',
            'general_information_sheet.mimes' => 'The General Information Sheet must have a file format of: .JPG, .JPEG, and .PNG'
        ];
    }
}
