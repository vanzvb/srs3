<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CRMXI3_Model\SPCV3;
use App\Models\LogPrice;
use Exception;

class SPCV3Controller extends Controller
{
    public function index()
    {
        $categories = DB::select('select * from crmxi3_categories');
        $subcats = DB::select('SELECT * FROM crmxi3_subcat');
        $hoas = DB::select('SELECT * FROM crmxi3_hoas');
        $hoatypes = DB::select('SELECT * FROM crmxi3_hoa_types');
        $vehicle_ownership_status = DB::select('SELECT * FROM crmxi3_vehicle_ownership_status');
        $item_master = DB::select('SELECT * FROM itemmaster');
        $groups = DB::table('srs3_sims_sticker_itemdatagrp')->get();
        $hoa_groups = DB::table('srs3_hoa_groups')
                    ->select('group_id','name') // Select the name column
                    ->distinct()     // Ensure the names are unique
                    ->get();

        $subcats2 = DB::select('SELECT * FROM get_subcat');
        $hoatypes2 = DB::select('SELECT * FROM get_hoa_types');
        $vehicle_ownership_status2 = DB::select('SELECT * FROM get_vehicle_ownership_status');

        $sticker_prices = SPCV3::leftJoin('crmxi3_categories','srs3_spc_price_matrix.category_id','=','crmxi3_categories.id')
        ->leftJoin('crmxi3_subcat','srs3_spc_price_matrix.sub_category_id','=','crmxi3_subcat.id')
        ->leftJoin('crmxi3_hoa_types','srs3_spc_price_matrix.hoa_type_id','=','crmxi3_hoa_types.id')
        ->leftJoin('crmxi3_vehicle_ownership_status','srs3_spc_price_matrix.vehicle_ownership_status_id','=','crmxi3_vehicle_ownership_status.id')
        ->leftJoin('crmxi3_hoas', 'srs3_spc_price_matrix.hoa', '=', 'crmxi3_hoas.id')
        ->leftJoin('srs3_hoa_groups', 'srs3_spc_price_matrix.hoa_group_id', '=', 'srs3_hoa_groups.group_id')
            ->select('srs3_spc_price_matrix.*',
            'crmxi3_categories.name AS category_name',
            'crmxi3_subcat.name AS subcat_name',
            'crmxi3_hoa_types.name AS hoatype_name',
            'crmxi3_vehicle_ownership_status.name AS vos_name',
            'crmxi3_hoas.name AS hoa_name',
            'srs3_hoa_groups.name AS hoagroup_name'
        )
        ->distinct() // Ensure distinct results
        // ->orderBy('hoagroup_name')
        // ->orderBy('crmxi3_categories.name')
        // ->orderBy('crmxi3_subcat.name')
        // ->orderBy('crmxi3_vehicle_ownership_status.name')
        // ->orderBy('srs3_spc_price_matrix.vehicleType')
        ->orderBy('crmxi3_subcat.name')
        ->orderBy('crmxi3_vehicle_ownership_status.name')
        ->orderBy('srs3_spc_price_matrix.vehicleType')
        ->get();

        return view('crmxi3.spc3_sticker_price',[
            'categories' => $categories,
            'subcats' => $subcats,
            'hoas' => $hoas,
            'hoatypes' => $hoatypes,
            'vehicle_ownership_status' => $vehicle_ownership_status,
            'item_master' => $item_master,
            'sticker_prices_matrix' => $sticker_prices,
            'groups' => $groups,
            'subcats2' => $subcats2,
            'hoatypes2' => $hoatypes2,
            'vehicle_ownership_status2' => $vehicle_ownership_status2,
            'hoa_groups' => $hoa_groups

        ]);
    }

    public function spc_insert(Request $req)
    {

        // Validate the request data
        $validatedData = $req->validate([
            'rowID' => 'nullable',
            'sellingPrice' => 'required|numeric',
            'stickerPrice' => 'required|numeric',
            'surcharge' => 'nullable|numeric',
            'vatSales' => 'nullable|numeric',
            'vatAmount' => 'nullable|numeric',
            'min' => 'required|numeric',
            'max' => 'nullable|numeric',
            'vehicleType' => 'required',
            'isOR' => 'required',
            'effDate' => 'nullable|date',
            'expDate' => 'nullable|date',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'hoa_type' => 'nullable|numeric',
            'hoa_group' => 'nullable|numeric',
            'vos_type' => 'nullable|numeric',
            'roadMaintFee' => 'nullable|numeric',
            'security' => 'nullable|numeric',
            'whTax' => 'nullable|numeric',
            'hoa' => 'nullable',
            'item_data' => 'nullable|numeric'

        ]);

        // dd($validatedData);

        if($validatedData['rowID']){
            // dd($validatedData);
            // $validatedData = $req->validate([
            //     'rowID' => 'required',
            //     'sellingPrice' => 'required|numeric',
            //     'stickerPrice' => 'required|numeric',
            //     'surcharge' => 'nullable|numeric',
            //     'vatSales' => 'nullable|numeric',
            //     'vatAmount' => 'nullable|numeric',
            //     'min' => 'required|numeric',
            //     'max' => 'nullable|numeric',
            //     'vehicleType' => 'required',
            //     'isOR' => 'required',
            //     'effDate' => 'nullable|date',
            //     'expDate' => 'nullable|date',
            //     'category_id' => 'required',
            //     'sub_category_id' => 'required',
            //     'hoa_type' => 'nullable|numeric',
            //     'vos_type' => 'nullable|numeric',
            //     'roadMaintFee' => 'nullable|numeric',
            //     'security' => 'nullable|numeric',
            //     'whTax' => 'nullable|numeric',
            //     'hoa' => 'nullable',
            //     'item_data' => 'nullable|numeric'
            // ]);


            $spcModel = SPCV3::find($validatedData['rowID']);
            if (!$spcModel) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Record not found');
            }

            // Calculate the new sellingPrice: if surcharge is not null, add it to sellingPrice; otherwise, keep the existing sellingPrice
            $newSellingPrice = ($validatedData['surcharge'] !== null) ? ($validatedData['sellingPrice'] + $validatedData['surcharge']) : $validatedData['sellingPrice'];

            $spcModel->sellingPrice = $newSellingPrice;
            $spcModel->basePrice = $validatedData['stickerPrice'];
            $spcModel->surcharge = $validatedData['surcharge'] ?? null;
            $spcModel->min = $validatedData['min'];
            $spcModel->max = $validatedData['max'] ?? null;
            $spcModel->vatSales = $validatedData['vatSales'] ?? null;
            $spcModel->vatAmount = $validatedData['vatAmount'] ?? null;
            $spcModel->vehicleType = $validatedData['vehicleType'];
            $spcModel->isOR = $validatedData['isOR'];
            $spcModel->surchargeEffDate = $validatedData['effDate'] ?? null;
            $spcModel->surchargeExpDate = $validatedData['expDate'] ?? null;
            $spcModel->category_id = $validatedData['category_id'];
            $spcModel->sub_category_id = $validatedData['sub_category_id'];
            $spcModel->hoa_type_id = $validatedData['hoa_type'];
            $spcModel->hoa_group_id = $validatedData['hoa_group'];
            $spcModel->vehicle_ownership_status_id	 = $validatedData['vos_type'];
            $spcModel->roadMaintFee = $validatedData['roadMaintFee'] ?? null;
            $spcModel->security = $validatedData['security'] ?? null;
            $spcModel->whTax = $validatedData['whTax'] ?? null;
            $spcModel->hoa = $validatedData['hoa'][0] ?? null;
            $spcModel->spc_item_id = $validatedData['item_data'] ?? null;

            $spcModel->save();
            // dd($spcModel);

            return redirect()
                ->back()
                ->withInput()
                ->with('success', 'Successfully Updated');
        }else{
            if (isset($validatedData['surcharge']) && $validatedData['surcharge'] > 300) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Surcharge cannot be greater than 300');
            }

            if ($validatedData['sellingPrice'] == 0 || $validatedData['stickerPrice'] == 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Selling price and sticker price cannot be zero');
            }

            $attributes = [
                'vehicleType' => $validatedData['vehicleType'],
                'category_id' => $validatedData['category_id'],
                'sub_category_id' => $validatedData['sub_category_id'],
                'hoa_type_id' => $validatedData['hoa_type'],
                'hoa_group_id' => $validatedData['hoa_group'],
                'vehicle_ownership_status_id' => $validatedData['vos_type'],
                'min' => $validatedData['min'],
                'max' => $validatedData['max'],
                'isOR' => $validatedData['isOR'],
                'surchargeEffDate' => $validatedData['effDate'],
                'surchargeExpDate' => $validatedData['expDate'],
                'hoa' => $validatedData['hoa'] ?? null,
                'spc_item_id' => $validatedData['item_data']
            ];

            $spcModel = SPCV3::where($attributes)->first();

            // Calculate the new sellingPrice: if surcharge is not null, add it to sellingPrice; otherwise, keep the existing sellingPrice
            $newSellingPrice = ($validatedData['surcharge'] !== null) ? ($validatedData['sellingPrice'] + $validatedData['surcharge']) : $validatedData['sellingPrice'];

            if ($spcModel) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Duplicate Data.');
            }

            $spcModel = new SPCV3();

            $withHoa = $validatedData['hoa'] ?? null;

            if ($withHoa == null) {
                $spcModel->category_id = $validatedData['category_id'];
                $spcModel->sub_category_id = $validatedData['sub_category_id'];
                $spcModel->hoa_type_id = $validatedData['hoa_type'];
                $spcModel->hoa_group_id = $validatedData['hoa_group'];
                $spcModel->vehicle_ownership_status_id	 = $validatedData['vos_type'];
                $spcModel->vehicleType = $validatedData['vehicleType'];
                // $spcModel->category_id = $validatedData['category_id'];
                $spcModel->sellingPrice = $newSellingPrice;
                $spcModel->basePrice = $validatedData['stickerPrice'];
                $spcModel->surcharge = $validatedData['surcharge'] ?? null;
                $spcModel->min = $validatedData['min'];
                $spcModel->max = $validatedData['max'] ?? null;
                $spcModel->hoa = $validatedData['hoa'] ?? null;

                $spcModel->vatSales = $validatedData['vatSales'] ?? null;
                $spcModel->vatAmount = $validatedData['vatAmount'] ?? null;

                // if ($validatedData['category_id'] == 1) {
                //     $spcModel->vatSales = $validatedData['vatSales'] ?? null;
                //     $spcModel->vatAmount = $validatedData['vatSales'] ?? null;
                // } else {
                //     $spcModel->vatSales = $validatedData['vatSales'] ?? null;
                //     $spcModel->vatAmount = $validatedData['vatAmount'] ?? null;
                // }

                $spcModel->isOR = $validatedData['isOR'];
                $spcModel->surchargeEffDate = $validatedData['effDate'] ?? null;
                $spcModel->surchargeExpDate = $validatedData['expDate'] ?? null;
                $spcModel->roadMaintFee = $validatedData['roadMaintFee'] ?? null;
                $spcModel->security = $validatedData['security'] ?? null;
                $spcModel->whTax = $validatedData['whTax'] ?? null;
                $spcModel->who = auth()->user()->name;
                $spcModel->spc_item_id = $validatedData['item_data'];

                $spcModel->save();



                // Create log entry with field details
                $log = new LogPrice();
                $log->action = 'Insertion';
                $log->action_by = auth()->user()->name;

                // Concatenate field details
                $fieldDetails = 'Inserted Data: ';
                foreach ($validatedData as $field => $value) {
                    if ($value !== null) {
                        $fieldDetails .= $field . ': ' . $value . ', ';
                    }
                }
                $fieldDetails = rtrim($fieldDetails, ', ');

                $log->action = $fieldDetails;

                $log->save();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('success', 'Successfully Added');
            } else {
                $hoas = $validatedData['hoa'];

                for ($x = 0; $x < count($hoas); $x++) {
                    $selectedHoa = $hoas[$x];
                    //dd($selectedHoa);
                    $spcModel = new SPCV3();

                    // Set common values
                    $spcModel->category_id = $validatedData['category_id'];
                    $spcModel->sub_category_id = $validatedData['sub_category_id'];
                    $spcModel->hoa_type_id = $validatedData['hoa_type'];
                    $spcModel->hoa_group_id = $validatedData['hoa_group'];
                    $spcModel->vehicle_ownership_status_id	 = $validatedData['vos_type'];
                    $spcModel->vehicleType = $validatedData['vehicleType'];
                    // $spcModel->category_id = $validatedData['category_id'];
                    $spcModel->sellingPrice = $newSellingPrice;
                    $spcModel->basePrice = $validatedData['stickerPrice'];
                    $spcModel->surcharge = $validatedData['surcharge'] ?? null;
                    $spcModel->min = $validatedData['min'];
                    $spcModel->max = $validatedData['max'] ?? null;
                    $spcModel->hoa = $selectedHoa ?? null;

                    if ($validatedData['category_id'] == 1) {
                        $spcModel->vatSales = 0.00;
                        $spcModel->vatAmount = 0.00;
                    } else {
                        $spcModel->vatSales = $validatedData['vatSales'] ?? null;
                        $spcModel->vatAmount = $validatedData['vatAmount'] ?? null;
                    }

                    $spcModel->isOR = $validatedData['isOR'];
                    $spcModel->surchargeEffDate = $validatedData['effDate'] ?? null;
                    $spcModel->surchargeExpDate = $validatedData['expDate'] ?? null;
                    $spcModel->roadMaintFee = $validatedData['roadMaintFee'] ?? null;
                    $spcModel->security = $validatedData['security'] ?? null;
                    $spcModel->whTax = $validatedData['whTax'] ?? null;
                    $spcModel->who = auth()->user()->name;
                    $spcModel->spc_item_id = $validatedData['item_data'];

                    $spcModel->save();

                    // Create log entry with field details
                    $log = new LogPrice();
                    $log->action = 'Insertion';
                    $log->action_by = auth()->user()->name;

                    // Concatenate field details
                    $fieldDetails = 'Inserted Data: ';
                    foreach ($validatedData as $field => $value) {

                        if ($value !== null) {
                            if (is_array($value)) {
                                foreach ($value as $item) {
                                    $fieldDetails .= $field . ': ' . $item . ', ';
                                }
                            } else {
                                $fieldDetails .= $field . ': ' . $value . ', ';
                            }
                        }
                    }

                    $fieldDetails = rtrim($fieldDetails, ', ');

                    $log->action = $fieldDetails;
                    $log->save();
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('success', 'Successfully Added');
            }
        }


    }
    public function spc_show($id)
    {

        $scp = DB::table('srs3_spc_price_matrix')
            ->leftJoin('crmxi3_categories', 'srs3_spc_price_matrix.category_id', '=', 'crmxi3_categories.id')
            ->leftJoin('crmxi3_subcat', 'srs3_spc_price_matrix.sub_category_id', '=', 'crmxi3_subcat.id')
            ->leftJoin('crmxi3_hoas', 'srs3_spc_price_matrix.hoa', '=', 'crmxi3_hoas.id')
            ->leftJoin('crmxi3_hoa_types', 'srs3_spc_price_matrix.hoa_type_id', '=', 'crmxi3_hoa_types.id')
            ->leftJoin('crmxi3_vehicle_ownership_status', 'srs3_spc_price_matrix.vehicle_ownership_status_id', '=', 'crmxi3_vehicle_ownership_status.id')
            ->select('srs3_spc_price_matrix.*',
            'crmxi3_categories.name AS category_name',
            'crmxi3_subcat.name AS sub_name',
            'crmxi3_hoas.name AS hoa_name',
            'crmxi3_hoa_types.name AS hoatype_name',
            'crmxi3_vehicle_ownership_status.name AS vos_name'
            )
            ->where('srs3_spc_price_matrix.id', '=', $id)
            ->get();

        $sixHoursPassed = false;

        if ($scp[0]->created_at) {
            $sixHoursPassed = now()->diffInHours($scp[0]->created_at) >= 6 ? true : false;
        }

        // $today = date('Y-m-d');

        // $isExpired = $today > $scp[0]->surchargeExpDate ? true : false;

        $newData = $scp->toArray();
        $newData['sixHoursPassed'] = $sixHoursPassed;
        // $newData['sixHoursPassed'] = false;

        return response()->json($newData);
    }
}
