<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
use App\Models\CRMXI3_Model\CRMXIMain;
use Illuminate\Http\Request;

class Crmxi2pcController extends Controller
{
    public function index()
    {
        return view('crmxi3_p2child.crmx_p2c');
    }

    public function get_plate_numbers(Request $request)
    {
        $plateNumbers = CRMXIMain::query()
            ->join('crmxi3_vehicles', 'crmxi3_mains.account_id', '=', 'crmxi3_vehicles.account_id')
            ->join('crmxi3_vehicle_ownership_status', 'crmxi3_vehicles.vehicle_ownership_status_id', '=', 'crmxi3_vehicle_ownership_status.id')
            ->join('crmxi3_address', 'crmxi3_vehicles.address_id', '=', 'crmxi3_address.id')
            ->leftjoin('crmxi3_categories', 'crmxi3_address.category_id', '=', 'crmxi3_categories.id')
            ->leftjoin('crmxi3_subcat', 'crmxi3_address.sub_category_id', '=', 'crmxi3_subcat.id')
            ->leftjoin('crmxi3_hoas', 'crmxi3_address.hoa', '=', 'crmxi3_hoas.id')
            ->select(
                'crmxi3_categories.name as category_name',
                'crmxi3_subcat.name as sub_category_name',
                'crmxi3_vehicles.type',
                'crmxi3_vehicles.plate_no',
                'crmxi3_vehicles.sticker_date',
                'crmxi3_mains.firstname',
                'crmxi3_mains.middlename',
                'crmxi3_mains.lastname',
                'crmxi3_hoas.name as hoa_name',
                'crmxi3_vehicle_ownership_status.name as vot_name'
            )
            ->selectRaw('
                COUNT(DISTINCT crmxi3_vehicles.plate_no) as vehicle_count,
                SUM(CASE WHEN YEAR(crmxi3_vehicles.sticker_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as current_year_vehicles
            ')
            ->where('crmxi3_mains.account_id', $request->parent_id)
            ->where('crmxi3_vehicles.type', 'like', "%{$request->vehicle_type}%")
            ->where('crmxi3_categories.name', 'like', "%{$request->category}%")
            ->where('crmxi3_subcat.name', 'like', "%{$request->sub_category}%")
            ->where('crmxi3_vehicle_ownership_status.name', 'like', "%{$request->vot}%")
            ->when($request->filled("hoa"), function ($query) use ($request) {
                return $query->where('crmxi3_hoas.name', 'like', "%{$request->hoa}%");
            })
            ->groupBy('category_name', 'sub_category_name', 'crmxi3_vehicles.type', 'crmxi3_vehicles.plate_no')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $plateNumbers
        ], 200);
    }
}
