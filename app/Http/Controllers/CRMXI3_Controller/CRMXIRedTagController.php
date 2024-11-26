<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CRMXI3_Model\CRMXIRedTag;

class CRMXIRedTagController extends Controller
{
    public function index()
    {
        $redtag_master = DB::table('crmxi3_redtag_reason')->get();
        return view('crmxi3.crmxi_redtag_master',['redtag_master' => $redtag_master]);
    }

    public function insert_redtag_item(Request $req)
    {
        DB::table('crmxi3_redtag_reason')->updateOrInsert(
            [
                'id' => $req->current_id
            ],
            [
                'reason' => $req->description,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->name,
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );

        return redirect()
        ->back()
        ->withInput()
        ->with(['success' => "Successfully Saved"]);

    }

    public function delete_redtag_item(Request $req){
        DB::table('crmxi3_redtag_reason')->where('id', $req->id)->delete();
        return redirect()
        ->back()
        ->withInput()
        ->with(['success' => "Item Deleted"]);
    }

    public function insert_redtag(Request $req){
        // dd($req->all());
        $data = $req->all();
        
        if($data['redtag_type'] == 1){
            // dd($data);

            // $redtagVehiclesJson = $data['redtag_vehicles'][0]; // Get the JSON string
            // $redtagVehiclesArray = json_decode($redtagVehiclesJson, true);
            // // dd($redtagVehiclesArray);

            // for ($i = 0 ; $i < count($redtagVehiclesArray) ; $i++ ) {
            //     // dd($redtagVehiclesArray);
            //     // $checkIfExist = DB::table('crmxi3_redtag')->where('description','like','%'.$redtagVehiclesArray[$i]['other_reason'].'%')->get();
            //     // if(count($checkIfExist) === 0){
            //         // dd(count($redtagVehiclesArray));
            //     // dd($redtagVehiclesArray[$i]['reason']);

            //     DB::table('crmxi3_redtag')->insert([
            //         'description' => $redtagVehiclesArray[$i]['other_reason'] ? $redtagVehiclesArray[$i]['other_reason'] : $redtagVehiclesArray[$i]['reason'],
            //         'type' => $data['redtag_type'],
            //         'account_id' => $data['account_id'],
            //         'vehicle_id' => $redtagVehiclesArray[$i]['vehicle'],
            //         'date_created' => date('Y-m-d H:i:s'),
            //         'status' => 1,
            //         'action_by' => auth()->user()->name
            //     ]);
                
            //     // }
            //     if($redtagVehiclesArray[$i]['other_reason']){
            //         $checkIfExistRedTagList = DB::table('crmxi3_redtag_reason')->whereRaw('LOWER(reason)= ?',strtolower($redtagVehiclesArray[$i]['other_reason']))->get();
            //         if(count($checkIfExistRedTagList) === 0){
            //             // dd($checkIfExist);
            //             DB::table('crmxi3_redtag_reason')->insert([
            //                 'reason' => $redtagVehiclesArray[$i]['other_reason'],
            //                 'created_at' => date('Y-m-d H:i:s'),
            //                 'created_by' => auth()->user()->name,
            //                 'updated_at' => date('Y-m-d H:i:s')
            //             ]);
            //         };
            //     };

            //     DB::table('crmxi3_vehicles')->where('id',$redtagVehiclesArray[$i]['vehicle'])
            //         ->update([
            //             'red_tag' => 1
            //     ]);
                
            // }
            // vehicle type redtag
            // dd($data['reason']);
        }else{
            // account type redtag
                // $checkIfExist = DB::table('crmxi3_redtag')->where('description','like','%'.$data['other_reason'].'%')->get();
                // if(count($checkIfExist) === 0){
                    DB::table('crmxi3_redtag')->insert([
                        'description' => $data['other_reason'] ? $data['other_reason'] : $data['reason'],
                        'type' => $data['redtag_type'],
                        'account_id' => $data['account_id'],
                        'date_created' => date('Y-m-d H:i:s'),
                        'status' => 1,
                        'action_by' => auth()->user()->name
                    ]);
                // }
                // else{
                //     dd('error');
                // }
            if($data['other_reason']){
                $checkIfExistRedTagList = DB::table('crmxi3_redtag_reason')->whereRaw('LOWER(reason) = ?',strtolower($data['other_reason']))->get();
                if(count($checkIfExistRedTagList) == 0){
                    // dd($checkIfExist);
                    DB::table('crmxi3_redtag_reason')->insert([
                        'reason' => $data['other_reason'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => auth()->user()->name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                };
            }
            DB::table('crmxi3_mains')->where('account_id',$data['account_id'])
            ->update([
                'red_tag' => 1
            ]);
            DB::table('crmxi3_vehicles')->where('account_id',$data['account_id'])
            ->update([
                'red_tag' => 1
            ]);
        }
    }
    public function remove_redtag (Request $req)
    {
        DB::table('crmxi3_redtag')->where('id',$req->id)
        ->update([
            'status' => 0
        ]);

        $getItem =  DB::table('crmxi3_redtag')->select('type','account_id','vehicle_id')
        ->where('id',$req->id)
        ->get();
            // dd($getItem);

        if($getItem[0]->type == 1){
            // dd($getItem[0]);
            DB::table('crmxi3_vehicles')->where('id',$getItem[0]->vehicle_id)
            ->update([
                'red_tag' => 0
            ]);
        }else{

            DB::table('crmxi3_mains')->where('account_id',$getItem[0]->account_id)
            ->update([
                'red_tag' => 0
            ]);
        }
    }
    
}

