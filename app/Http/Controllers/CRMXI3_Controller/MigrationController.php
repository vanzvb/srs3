<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
// use App\Models\CRMXI3_Model\CRMXIMain;
// use App\Models\CRMXI3_Model\CRXMIVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
    public function crm_migration ()
    {
        // set_time_limit(2700);
        // ini_set('memory_limit', '1536M');
        $old_crm = DB::table('crm_mains')->get();
        $old_crm_count = count($old_crm);
        $test = [];
        $get_hoa_list = DB::table('crmxi3_hoas')->get()->toArray();
        $get_cities = DB::table('crmx_bl_city')->get()->toArray();
        $get_zipcode = DB::table('crmxi3_zip_codes')->get()->toArray();
        $get_civil = DB::table('crmxi3_civil_status')->get()->toArray();
        $get_nationalities = DB::table('crmxi3_nationalities')->get()->toArray();
        $get_subcats = DB::table('crmxi3_subcat')->get()->toArray();
        $city_map = [];
        foreach ($get_cities as $city) {
            $city_map[$city->description] = $city->bl_id;
        };
        $zipcode_map = [];
        foreach ($get_zipcode as $zipcode) {
            $zipcode_map[$zipcode->city] = $zipcode->zip_code;
        };
        $civil_map = [];
        foreach ($get_civil as $civil) {
            $civil_map[$civil->name] = $civil->id;
        };
        $nationality_map = [];
        foreach ($get_nationalities as $nationality) {
            $nationality_map[$nationality->name] = $nationality->id;
        };
        $subcat_map = [];
        foreach ($get_subcats as $subcat) {
            $subcat_map[$subcat->id] = $subcat->id;
        };
        // $last_account = DB::table('crmxi3_mains')->select('account_id')->latest()->first();
        // $last_account_id = $last_account ? (int)explode('-', $last_account->account_id)[2] : 0;
        $account_counter = 0;
        DB::table('crm_mains')->orderBy('crm_id')->chunk(100, function ($old_crm) use (&$test, $get_hoa_list  ,$city_map ,$zipcode_map ,$civil_map ,$nationality_map, &$account_counter, $subcat_map ) {
            DB::transaction(function() use($old_crm, &$test, $get_hoa_list ,$city_map ,$zipcode_map ,$civil_map ,$nationality_map, &$account_counter, $subcat_map ) {
                foreach ($old_crm as $i => $crm) {
                    $account_counter++;
                    $hoa_code = '';
                    $city_code = '';
                    $zipcode = '';
                    $civil = '';
                    $nationality = '';
                    $subcat_code = '';
                    array_map(function ($hoa) use ($old_crm, $i, &$hoa_code) {
                        if(!$old_crm[$i]->hoa || $old_crm[$i]->hoa == ''){
                            $hoa_code = 0;
                        }
                        similar_text($hoa->name, $old_crm[$i]->hoa, $percent);
                        if ($percent > 90) {  // If the similarity is over 90%
                            $hoa_code = $hoa->id;
                            // dd($hoa->name,$old_crm[$i]->hoa);
    
                        }
                        if($old_crm[$i]->hoa == 'KK HOMEOWNERS ASSOCIATION INC'){
                            $hoa_code = 26;
                        }
                    }, $get_hoa_list);
                    $account_no = $this->createAccountNum(0,$old_crm[$i]->category_id,$old_crm[$i]->sub_category_id,$hoa_code, $account_counter);
                    $city_code = isset($city_map[$old_crm[$i]->city]) ? $city_map[$old_crm[$i]->city] : null;    
                    $zipcode = isset($zipcode_map[$city_code]) ? $zipcode_map[$city_code] : null;    
                    $civil = isset($civil_map[$old_crm[$i]->civil_status]) ? ($civil_map[$old_crm[$i]->civil_status] !== '---' ? $civil_map[$old_crm[$i]->civil_status] : '' ) : null;    
                    $nationality = isset($nationality_map[ucfirst($old_crm[$i]->nationality)]) ? $nationality_map[ucfirst($old_crm[$i]->nationality)] : null;   
                    $subcat_code = isset($subcat_map[$old_crm[$i]->sub_category_id]) ? $subcat_map[$old_crm[$i]->sub_category_id] : ($old_crm[$i]->category_id == 1 ? 1 : 40);    
                    // array_push($test,$account_no);
                    DB::table('crmxi3_mains')->insert([
                        'account_id' => $account_no,
                        'account_type' => 0,
                        'hoa_type' => null,
                        'customer_id' => $old_crm[$i]->customer_id,
                        'firstname' => $old_crm[$i]->firstname,
                        'name' => $old_crm[$i]->name,
                        'middlename' => $old_crm[$i]->middlename,
                        'lastname' => $old_crm[$i]->lastname,
                        'street' => $old_crm[$i]->street,
                        'building_name' => $old_crm[$i]->building_name,
                        'address' => $old_crm[$i]->address,
                        'subdivision_village' => $old_crm[$i]->subdivision_village,
                        'blk_lot' => $old_crm[$i]->blk_lot,
                        'hoa' => $hoa_code,
                        'city' => $city_code,
                        'zipcode' => $zipcode,
                        'owned_vehicles' => $old_crm[$i]->owned_vehicles,
                        'civil_status' => $civil,
                        'nationality' => $nationality,
                        'red_tag' => $old_crm[$i]->red_tag,
                        'email' => $old_crm[$i]->email,
                        'main_contact' => $old_crm[$i]->main_contact,
                        'secondary_contact' => $old_crm[$i]->secondary_contact,
                        'tertiary_contact' => $old_crm[$i]->tertiary_contact,
                        'front_license' => $old_crm[$i]->front_license,
                        'category_id' => $old_crm[$i]->category_id,
                        'sub_category_id' => $subcat_code,
                        'vehicle_ownership_status_id' => null,
                        'back_license' => $old_crm[$i]->back_license,
                        'status' => $old_crm[$i]->status,
                        'telno' => $old_crm[$i]->telno,
                        'tin' => $old_crm[$i]->tin,
                        'agentid' => $old_crm[$i]->agentid,
                        'crlimit' => $old_crm[$i]->crlimit,
                        'billid' => $old_crm[$i]->billid,
                        'associd' => $old_crm[$i]->associd,
                        'reason_of_tag' => $old_crm[$i]->reason_of_tag,
                        'is_parent' => $old_crm[$i]->is_parent,
                        'tin_no' => $old_crm[$i]->tin_no
                    ]);

                    // for migration of vehicle

                    DB::table('crm_vehicles')->where('crm_id','LIKE','%' . $old_crm[$i]->customer_id . '%')->orderBy('id')->chunk(100, function($old_vehicle) use($account_no, $old_crm, $i,  $hoa_code ,$city_code ,$zipcode , $subcat_code ){
                        foreach($old_vehicle as $v => $vehicle){
                            $vehicleId = DB::table('crmxi3_vehicles')->insertGetId([
                                'account_id' => $account_no,
                                'srs_request_id' => $vehicle->srs_request_id,
                                'req_type' => $vehicle->req_type,
                                'crm_id' => $vehicle->crm_id,
                                'name' => $vehicle->name,
                                'vehicle_id' => $vehicle->vehicle_id,
                                'plate_no' => $vehicle->plate_no,
                                'orID' => $vehicle->orID,
                                'crID' => $vehicle->crID,
                                'brand' => $vehicle->brand,
                                'series' => $vehicle->series,
                                'year_model' => $vehicle->year_model,
                                'color' => $vehicle->color,
                                'type' => $vehicle->type,
                                'cr' => $vehicle->cr,
                                'req1' => $vehicle->req1,
                                'stypeid' => $vehicle->stypeid,
                                'vehicle_picture' => $vehicle->vehicle_picture,
                                'sno' => $vehicle->sno,
                                'rate' => $vehicle->rate,
                                'sticker_date' => $vehicle->sticker_date,
                                'old_sticker_year' => $vehicle->old_sticker_year,
                                'status' => $vehicle->status,
                                'old_sticker_no' => $vehicle->old_sticker_no,
                                'new_sticker_no' => $vehicle->new_sticker_no,
                                'assoc_crm' => $vehicle->assoc_crm,
                                'or_path' => $vehicle->or_path,
                                'cr_path' => $vehicle->cr_path,
                                'plate_no_remarks' => $vehicle->plate_no_remarks,
                                'color_remarks' => $vehicle->color_remarks,
                                'cr_from_crm' => $vehicle->cr_from_crm,
                                'red_tag' => null
                            ]);
                            // dd(
                            //     $vehicleId,
                            //     $old_crm[$i]->firstname,
                            //     $old_crm[$i]->middlename,
                            //     $old_crm[$i]->lastname,
                            //     $old_crm[$i]->street,
                            //     $old_crm[$i]->building_name,
                            //     $old_crm[$i]->address,
                            //     $old_crm[$i]->subdivision_village,
                            //     $old_crm[$i]->blk_lot,
                            //     $city_code,
                            //     $zipcode,
                            //     $old_crm[$i]->category_id,
                            //     $subcat_code,
                            //     NULL,
                            //     $hoa_code,
                            //     NULL,
                            //     $old_crm[$i]->status,
                            //     $old_crm[$i]->email,
                            //     $old_crm[$i]->main_contact,
                            //     $old_crm[$i]->secondary_contact,
                            //     $old_crm[$i]->tertiary_contact,
                            //     $old_crm[$i]->front_license,
                            //     $old_crm[$i]->back_license,
                            //     $old_crm[$i]->red_tag
                            // );
                            DB::table('crmxi3_vehicle_owners')->insert([
                                'vehicle_id' => $vehicleId,
                                'firstname' => $old_crm[$i]->firstname,
                                'middlename' => $old_crm[$i]->middlename,
                                'lastname' => $old_crm[$i]->lastname,
                                'block' => NULL,
                                'lot' => NULL,
                                'house_number' => NULL,
                                'street' => $old_crm[$i]->street,
                                'building_name' => $old_crm[$i]->building_name,
                                'address' => $old_crm[$i]->address,
                                'subdivision_village' => $old_crm[$i]->subdivision_village,
                                'blk_lot' => $old_crm[$i]->blk_lot,
                                'city' => $city_code,
                                'zipcode' => $zipcode,
                                'category_id' => $old_crm[$i]->category_id,
                                'sub_category_id' => $subcat_code,
                                'vehicle_ownership_status_id'=> NULL,
                                'hoa' => $hoa_code,
                                'hoa_type' => NULL,
                                'status' =>  $old_crm[$i]->status,
                                'email' =>  $old_crm[$i]->email,
                                'main_contact' => $old_crm[$i]->main_contact,
                                'secondary_contact' => $old_crm[$i]->secondary_contact,
                                'tertiary_contact'  => $old_crm[$i]->tertiary_contact,
                                'front_license'  =>  $old_crm[$i]->front_license,
                                'back_license' => $old_crm[$i]->back_license,
                                'red_tag'  => $old_crm[$i]->red_tag
                            ]);
                        };
                    });
                };
            });           
        });
        return 'success';
    }

    public function createAccountNum($account_type, $category, $subcat, $hoa, $last_account_id)
    {
        // $crmxi_list = DB::table('crmxi3_mains')->get()->toArray();
        // $count = count($crmxi_list);
        $hoa_code = str_pad((string)$hoa, 3, '0', STR_PAD_LEFT);
        $subcat_code = str_pad((string)$subcat, 2, '0', STR_PAD_LEFT);
        $series = $account_type . $category . $subcat_code . '-' . $hoa_code . '-';
    
        // $last_account_id++;  // Increment account ID manually
        $srn = str_pad((string)$last_account_id, 6, '0', STR_PAD_LEFT);
    
        return $series . $srn;
    }
}
