<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\SrsHoa;
use App\Models\CrmMain;
use App\Models\SrsUser;
use App\Models\LogReport;
use App\Exports\HoaExport;
use App\Exports\HoaExport2;
use App\Models\CrmInvoice;
use App\Models\CrmVehicles;
use App\Exports\PriceExport;
use Illuminate\Http\Request;
use App\Models\SrsCategories;
use App\Exports\RevenueExport;
use App\Exports\StickerReport;
use App\Exports\RevenueExport2;
use App\Models\CrmInvoiceItems;
use App\Exports\CrStickerExport;
use App\Exports\HoaMemberExport;
use App\Exports\HoaMemberExport2;
use App\Exports\OrStickerExport;
use App\Models\SrsSubCategories;
use Illuminate\Support\Facades\DB;
use App\Exports\StickerReportFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StickerReportFilter2;
use App\Exports\StickerReportFilter3;
use App\Exports\StickerReportFilter4;
use App\Exports\CrStickerReportFilterExport;
use App\Exports\OrStickerReportFilterExport;


class StickerController extends Controller
{
    public function exportPDFSticker(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);

        $totalAmount = 0;
        $name = "";
        $counter = 0;

        $men = [];
        $categories = SrsCategories::all();



        if ($data['status'] != 0) {
            $stickers = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                ->when($data['period'] == 1, function ($q) use ($data) {
                    return $q->whereDate('crm_invoice.created_at', Carbon::today())
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->when($data['period'] == 2, function ($q) use ($data) {
                    return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()])
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->when($data['period'] == 3, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->whereMonth('crm_invoice.created_at', $data['sub_select'])
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->when($data['period'] == 4, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->when($data['sub_select'] == 1, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                ->whereMonth('crm_invoice.created_at', '<=', 3)
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                ->whereMonth('crm_invoice.created_at', '<=', 6)
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['sub_select'] == 3, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                ->whereMonth('crm_invoice.created_at', '<=', 9)
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['sub_select'] == 4, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                ->whereMonth('crm_invoice.created_at', '<=', 12)
                                ->where('crm_invoice.action_by', $data['status']);
                        });
                })
                ->when($data['period'] == 5, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->orderBy('crm_invoice.created_at')
                ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_invoice.or_number', 'crm_invoice.discount', 'crm_invoice.isCancel', 'crm_invoice_items.id')
                ->groupBy('crm_invoice_items.id')
                ->get();

            $discount = CrmInvoice::join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->when($data['period'] == 1, function ($q) use ($data) {
                    return $q->whereDate('crm_invoice.created_at', Carbon::today())
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->when($data['period'] == 2, function ($q) use ($data) {
                    return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()])
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->when($data['period'] == 3, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->whereMonth('crm_invoice.created_at', $data['sub_select'])
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->when($data['period'] == 4, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->when($data['sub_select'] == 1, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                ->whereMonth('crm_invoice.created_at', '<=', 3)
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                ->whereMonth('crm_invoice.created_at', '<=', 6)
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['sub_select'] == 3, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                ->whereMonth('crm_invoice.created_at', '<=', 9)
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['sub_select'] == 4, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                ->whereMonth('crm_invoice.created_at', '<=', 12)
                                ->where('crm_invoice.action_by', $data['status']);
                        });
                })
                ->when($data['period'] == 5, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->where('crm_invoice.action_by', $data['status']);
                })
                ->groupBy('crm_invoice.invoice_no')
                ->select('crm_invoice.discount')
                ->get();

            foreach ($categories as $category) {
                $mains = CrmMain::where('category_id', '=', $category->id)->get();
                $cars = 0;
                $motor = 0;
                $vat_m = 0;
                $total_money_m = 0;
                $vat_c = 0;
                $total_money_c = 0;
                $t_v_c = 0;
                $t_v_m = 0;
                foreach ($mains as $main) {
                    $vehicles = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->when($data['period'] == 1, function ($q) use ($data) {
                            return $q->whereDate('crm_invoice.created_at', Carbon::today())
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['period'] == 2, function ($q) use ($data) {
                            return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()])
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['period'] == 3, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->whereMonth('crm_invoice.created_at', $data['sub_select'])
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['period'] == 4, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->when($data['sub_select'] == 1, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                        ->whereMonth('crm_invoice.created_at', '<=', 3)
                                        ->where('crm_invoice.action_by', $data['status']);
                                })
                                ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                        ->whereMonth('crm_invoice.created_at', '<=', 6)
                                        ->where('crm_invoice.action_by', $data['status']);
                                })
                                ->when($data['sub_select'] == 3, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                        ->whereMonth('crm_invoice.created_at', '<=', 9)
                                        ->where('crm_invoice.action_by', $data['status']);
                                })
                                ->when($data['sub_select'] == 4, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                        ->whereMonth('crm_invoice.created_at', '<=', 12)
                                        ->where('crm_invoice.action_by', $data['status']);
                                });
                        })
                        ->when($data['period'] == 5, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice.discount', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();


                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }

                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                array_push($men, [$category->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
            }

            $commercial = [];
            $subs = SrsSubCategories::where('category_id', '=', '3')->where('id', '!=', '23')->get();
            foreach ($subs as $sub) {
                $mains = CrmMain::where('category_id', '=', '3')->where('sub_category_id', '=', $sub->id)->get();
                $cars = 0;
                $motor = 0;
                $vat_m = 0;
                $total_money_m = 0;
                $vat_c = 0;
                $total_money_c = 0;
                $t_v_c = 0;
                $t_v_m = 0;
                foreach ($mains as $main) {
                    $vehicles = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->when($data['period'] == 1, function ($q) use ($data) {
                            return $q->whereDate('crm_invoice.created_at', Carbon::today())
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['period'] == 2, function ($q) use ($data) {
                            return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()])
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['period'] == 3, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->whereMonth('crm_invoice.created_at', $data['sub_select'])
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->when($data['period'] == 4, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->when($data['sub_select'] == 1, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                        ->whereMonth('crm_invoice.created_at', '<=', 3)
                                        ->where('crm_invoice.action_by', $data['status']);
                                })
                                ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                        ->whereMonth('crm_invoice.created_at', '<=', 6)
                                        ->where('crm_invoice.action_by', $data['status']);
                                })
                                ->when($data['sub_select'] == 3, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                        ->whereMonth('crm_invoice.created_at', '<=', 9)
                                        ->where('crm_invoice.action_by', $data['status']);
                                })
                                ->when($data['sub_select'] == 4, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                        ->whereMonth('crm_invoice.created_at', '<=', 12)
                                        ->where('crm_invoice.action_by', $data['status']);
                                });
                        })
                        ->when($data['period'] == 5, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->where('crm_invoice.action_by', $data['status']);
                        })
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();


                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                if ($sub->id == 18) {
                    array_push($commercial, [$sub->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                } else if ($sub->id == 19) {
                    array_push($commercial, ['Non-Resident', ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                }
            }
        } else {
            $stickers = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                ->when($data['period'] == 1, function ($q) use ($data) {
                    return $q->whereDate('crm_invoice.created_at', Carbon::today());
                })
                ->when($data['period'] == 2, function ($q) use ($data) {
                    return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()]);
                })
                ->when($data['period'] == 3, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->whereMonth('crm_invoice.created_at', $data['sub_select']);
                })
                ->when($data['period'] == 4, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->when($data['sub_select'] == 1, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                ->whereMonth('crm_invoice.created_at', '<=', 3);
                        })
                        ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                ->whereMonth('crm_invoice.created_at', '<=', 6);
                        })
                        ->when($data['sub_select'] == 3, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                ->whereMonth('crm_invoice.created_at', '<=', 9);
                        })
                        ->when($data['sub_select'] == 4, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                ->whereMonth('crm_invoice.created_at', '<=', 12);
                        });
                })
                ->when($data['period'] == 5, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year']);
                })
                ->orderBy('crm_invoice.created_at')
                ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_invoice.or_number', 'crm_invoice.total_amount', 'crm_invoice.discount', 'crm_invoice.isCancel', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                ->groupBy('crm_invoice_items.id')
                ->get();
            $discount = CrmInvoice::join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->when($data['period'] == 1, function ($q) use ($data) {
                    return $q->whereDate('crm_invoice.created_at', Carbon::today());
                })
                ->when($data['period'] == 2, function ($q) use ($data) {
                    return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()]);
                })
                ->when($data['period'] == 3, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->whereMonth('crm_invoice.created_at', $data['sub_select']);
                })
                ->when($data['period'] == 4, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year'])
                        ->when($data['sub_select'] == 1, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                ->whereMonth('crm_invoice.created_at', '<=', 3);
                        })
                        ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                ->whereMonth('crm_invoice.created_at', '<=', 6);
                        })
                        ->when($data['sub_select'] == 3, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                ->whereMonth('crm_invoice.created_at', '<=', 9);
                        })
                        ->when($data['sub_select'] == 4, function ($q) use ($data) {
                            return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                ->whereMonth('crm_invoice.created_at', '<=', 12);
                        });
                })
                ->when($data['period'] == 5, function ($q) use ($data) {
                    return $q->whereYear('crm_invoice.created_at', $data['year']);
                })
                ->groupBy('crm_invoice.invoice_no')
                ->select('crm_invoice.discount')
                ->get();
            $counter++;

            foreach ($categories as $category) {

                $mains = CrmMain::where('category_id', '=', $category->id)->get();
                $cars = 0;
                $motor = 0;
                $vat_m = 0;
                $total_money_m = 0;
                $t_v_c = 0;
                $t_v_m = 0;
                $vat_c = 0;
                $total_money_c = 0;
                foreach ($mains as $main) {
                    $vehicles =  CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->when($data['period'] == 1, function ($q) use ($data) {
                            return $q->whereDate('crm_invoice.created_at', Carbon::today());
                        })
                        ->when($data['period'] == 2, function ($q) use ($data) {
                            return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()]);
                        })
                        ->when($data['period'] == 3, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->whereMonth('crm_invoice.created_at', $data['sub_select']);
                        })
                        ->when($data['period'] == 4, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->when($data['sub_select'] == 1, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                        ->whereMonth('crm_invoice.created_at', '<=', 3);
                                })
                                ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                        ->whereMonth('crm_invoice.created_at', '<=', 6);
                                })
                                ->when($data['sub_select'] == 3, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                        ->whereMonth('crm_invoice.created_at', '<=', 9);
                                })
                                ->when($data['sub_select'] == 4, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                        ->whereMonth('crm_invoice.created_at', '<=', 12);
                                });
                        })
                        ->when($data['period'] == 5, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year']);
                        })
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();

                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                array_push($men, [$category->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
            }


            $commercial = [];
            $cars = 0;
            $motor = 0;
            $vat_m = 0;
            $total_money_m = 0;
            $t_v_c = 0;
            $t_v_m = 0;
            $vat_c = 0;
            $total_money_c = 0;
            $subs = SrsSubCategories::where('category_id', '=', '3')->where('id', '!=', '23')->get();
            foreach ($subs as $sub) {
                $mains = CrmMain::where('category_id', '=', '3')->where('sub_category_id', '=', $sub->id)->get();

                foreach ($mains as $main) {

                    $vehicles =  CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->when($data['period'] == 1, function ($q) use ($data) {
                            return $q->whereDate('crm_invoice.created_at', Carbon::today());
                        })
                        ->when($data['period'] == 2, function ($q) use ($data) {
                            return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()]);
                        })
                        ->when($data['period'] == 3, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->whereMonth('crm_invoice.created_at', $data['sub_select']);
                        })
                        ->when($data['period'] == 4, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year'])
                                ->when($data['sub_select'] == 1, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                        ->whereMonth('crm_invoice.created_at', '<=', 3);
                                })
                                ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                        ->whereMonth('crm_invoice.created_at', '<=', 6);
                                })
                                ->when($data['sub_select'] == 3, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                        ->whereMonth('crm_invoice.created_at', '<=', 9);
                                })
                                ->when($data['sub_select'] == 4, function ($q) use ($data) {
                                    return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                        ->whereMonth('crm_invoice.created_at', '<=', 12);
                                });
                        })
                        ->when($data['period'] == 5, function ($q) use ($data) {
                            return $q->whereYear('crm_invoice.created_at', $data['year']);
                        })
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();

                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                if ($sub->id == 18) {
                    array_push($commercial, [$sub->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                } else if ($sub->id == 19) {
                    array_push($commercial, ['Non-Resident', ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                }
            }
        }




        // $vatable_inc = 0;
        // $total_a = 0;
        // $discount_items = 0;
        // foreach ($stickers as $sticker) {
        //     $total_a += $sticker->price;
        //     $totalAmount += $sticker->price / 1.12;
        //     $vatable_inc = $totalAmount * 0.12;
        //     $name = $sticker->srs_name;
        // }

        // foreach ($discount as $item) {
        //     $discount_items += $item->discount;
        // }

        // $total_a -= $discount_items;
        // $totalAmount -= $discount_items;

        $vatable_inc = 0;
        $total_a = 0;
        $discount_amount = 0;
        $total_s = 0;
        // $total_s = 0;

        foreach ($stickers as $sticker) {
            $total_a += $sticker->price;
            $total_s += $sticker->price;
            // $total_s += $sticker->price;
            $name = $sticker->srs_name;
        }

        foreach ($discount as $item) {
            $discount_amount += $item->discount;
        }


        $total_a -= $discount_amount;
        $totalAmount = $total_a / 1.12;
        $vatable_inc = $totalAmount;
        $vatable_tax = $totalAmount * 0.12;

        // echo "Vatable Income: " . $vatable_inc . "\n";
        // echo "Vatable Tax: " . $vatable_tax . "\n";
        // echo "Discount: " . $discount_amount . "\n";

        // dd($total_a . ' - ' . $vatable_inc . ' - ' . $vatable_tax . ' -  ' . $discount_amount . ' - ' . $total_s);








        $categoryGroup =   $stickers->groupBy('category_name');
        $residentSubGroup = collect([]);
        $nonResidentSubGroup = collect([]);
        $commercialSubGroup = collect([]);

        $total_resident = 0;
        $total_non_res = 0;
        $total_commercial = 0;
        $temp = [];
        $total_amm_r = 0;
        $total_count_r = 0;

        if (isset($categoryGroup['Resident'])) {
            $residentSubGroup = $categoryGroup['Resident']->groupBy('sub_category_name')->sortDesc();

            foreach ($residentSubGroup as $sub => $item) {

                $total = 0;
                $count = 0;
                foreach ($stickers as  $sticker) {

                    if ($sticker->sub_category_name == $sub && $sticker->category_name == 'Resident') {
                        $total += $sticker->price;
                        $count++;
                    }
                }
                array_push($temp, [$sub, $count, $total]);
            }
        } else {
            $categoryGroup['Resident'] = collect([]);
        }
        $nonr = [];
        if (isset($categoryGroup['Non-resident'])) {
            $nonResidentSubGroup = $categoryGroup['Non-resident']->groupBy('sub_category_name')->sortDesc();


            foreach ($nonResidentSubGroup as $sub => $item) {

                $total = 0;
                $count = 0;
                foreach ($stickers as  $sticker) {

                    if ($sticker->sub_category_name == $sub && $sticker->category_name == 'Non-resident') {
                        $total += $sticker->price;
                        $count++;
                    }
                }
                array_push($nonr, [$sub, $count, $total]);
            }
        } else {
            $categoryGroup['Non-resident'] = collect([]);
        }
        $non = [];
        if (isset($categoryGroup['Commercial'])) {
            $commercialSubGroup = $categoryGroup['Commercial']->groupBy('sub_category_name')->sortDesc();


            foreach ($commercialSubGroup  as $sub => $item) {

                $total = 0;
                $count = 0;
                foreach ($stickers as  $sticker) {

                    if ($sticker->sub_category_name == $sub && $sticker->category_name == 'Commercial') {
                        $total += $sticker->price;
                        $count++;
                    }
                }
                array_push($non, [$sub, $count, $total]);
            }
        } else {
            $categoryGroup['Commercial'] = collect([]);
        }


        $period = '';
        $time = '';
        if ($data['period'] == 1) {
            $period = 'DAILY';
            $time =  date("F j, Y", strtotime(Carbon::today()));
        } elseif ($data['period'] == 2) {
            $period = 'WEEKLY';
            $time = today()->startOfWeek()->format('M d, Y') . ' - ' . today()->endOfWeek()->format('M d, Y');
        } elseif ($data['period'] == 3) {
            $period = 'MONTHLY';
            $time = Carbon::createFromDate($data['year'], $data['sub_select'], 1)->format('F Y');
        } elseif ($data['period'] == 4) {
            $period = 'QUARTERLY';
            $time = 'Q' . $data['sub_select'] . ' ' . $data['year'];
        } elseif ($data['period'] == 5) {
            $period = 'ANNUAL';
            $time = $data['year'];
        }

        $pdf = PDF::loadView('exports.stickers_list', compact('stickers', 'period', 'time', 'categoryGroup', 'residentSubGroup', 'nonResidentSubGroup', 'commercialSubGroup', 'totalAmount', 'name', 'temp', 'nonr', 'non', 'counter', 'men', 'vatable_inc', 'total_a', 'commercial', 'discount_amount', 'vatable_tax', 'total_s'))->setPaper('a4', 'portrait');
            $pdf_file_name = '' . date("n-j-y h_i_a") . '_' . auth()->user()->name . '_ sticker_reports_for_' . $period . '.pdf'; // set the desired file name
        $pdf->save(storage_path('app/public/generate_reports/' . $pdf_file_name));
        // return $pdf->stream();
        return $pdf->download('sticker_reports.pdf');
    }

    public function sticker_export(Request $req)
    {
        $to = $req->to;
        $from = $req->from;
        $status = $req->status;


        $totalAmount = 0;
        $name = "";
        $counter = 0;

        $men = [];
        $categories = SrsCategories::all();


        if ($status != 0) {
            $stickers = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                ->where('crm_invoice.action_by', $status)
                ->whereDate('crm_invoice.created_at', '>=', $from)
                ->whereDate('crm_invoice.created_at', '<=', $to)
                ->orderBy('crm_invoice.created_at')
                ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_invoice.discount', 'crm_invoice.isCancel', 'crm_invoice_items.id','crm_invoice.or_number')
                ->groupBy('crm_invoice_items.id')
                ->get();

            $discount = CrmInvoice::join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->where('crm_invoice.action_by', $status)
                ->whereDate('crm_invoice.created_at', '>=', $from)
                ->whereDate('crm_invoice.created_at', '<=', $to)
                ->groupBy('crm_invoice.invoice_no')
                ->select('crm_invoice.discount')
                ->get();

            foreach ($categories as $category) {
                $mains = CrmMain::where('category_id', '=', $category->id)->get();
                $cars = 0;
                $motor = 0;
                $vat_m = 0;
                $total_money_m = 0;
                $vat_c = 0;
                $total_money_c = 0;
                $t_v_c = 0;
                $t_v_m = 0;
                foreach ($mains as $main) {
                    $vehicles = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->where('crm_invoice.action_by', $status)
                        ->whereDate('crm_invoice.created_at', '>=', $from)
                        ->whereDate('crm_invoice.created_at', '<=', $to)
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice.discount', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();


                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                array_push($men, [$category->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
            }

            $commercial = [];
            $subs = SrsSubCategories::where('category_id', '=', '3')->where('id', '!=', '23')->get();
            foreach ($subs as $sub) {
                $mains = CrmMain::where('category_id', '=', '3')->where('sub_category_id', '=', $sub->id)->get();
                $cars = 0;
                $motor = 0;
                $vat_m = 0;
                $total_money_m = 0;
                $vat_c = 0;
                $total_money_c = 0;
                $t_v_c = 0;
                $t_v_m = 0;
                foreach ($mains as $main) {
                    $vehicles = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->where('crm_invoice.action_by', $status)
                        ->whereDate('crm_invoice.created_at', '>=', $from)
                        ->whereDate('crm_invoice.created_at', '<=', $to)
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice.discount', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();


                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                if ($sub->id == 18) {
                    array_push($commercial, [$sub->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                } else if ($sub->id == 19) {
                    array_push($commercial, ['Non-Resident', ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                }
            }
        } else {
            $stickers = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                ->whereDate('crm_invoice.created_at', '>=', $from)
                ->whereDate('crm_invoice.created_at', '<=', $to)
                ->orderBy('crm_invoice.created_at')
                ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_invoice.or_number', 'crm_invoice.total_amount', 'crm_invoice.discount', 'crm_invoice.isCancel', 'crm_invoice_items.id','crm_invoice.or_number')
                ->groupBy('crm_invoice_items.id')
                ->get();

            $discount = CrmInvoice::join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->whereDate('crm_invoice.created_at', '>=', $from)
                ->whereDate('crm_invoice.created_at', '<=', $to)
                ->groupBy('crm_invoice.invoice_no')
                ->select('crm_invoice.discount')
                ->get();

            $counter++;

            foreach ($categories as $category) {

                $mains = CrmMain::where('category_id', '=', $category->id)->get();
                $cars = 0;
                $motor = 0;
                $vat_m = 0;
                $total_money_m = 0;
                $t_v_c = 0;
                $t_v_m = 0;
                $vat_c = 0;
                $total_money_c = 0;
                foreach ($mains as $main) {
                    $vehicles =  CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->whereDate('crm_invoice.created_at', '>=', $from)
                        ->whereDate('crm_invoice.created_at', '<=', $to)
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice.discount', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();

                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                array_push($men, [$category->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
            }


            $commercial = [];
            $cars = 0;
            $motor = 0;
            $vat_m = 0;
            $total_money_m = 0;
            $t_v_c = 0;
            $t_v_m = 0;
            $vat_c = 0;
            $total_money_c = 0;
            $subs = SrsSubCategories::where('category_id', '=', '3')->where('id', '!=', '23')->get();
            foreach ($subs as $sub) {
                $mains = CrmMain::where('category_id', '=', '3')->where('sub_category_id', '=', $sub->id)->get();

                foreach ($mains as $main) {

                    $vehicles =  CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                        ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                        ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                        ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                        ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                        ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                        ->whereDate('crm_invoice.created_at', '>=', $from)
                        ->whereDate('crm_invoice.created_at', '<=', $to)
                        ->where('crm_vehicles.crm_id', '=', $main->customer_id)
                        ->orderBy('crm_invoice.created_at')
                        ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.or_number', 'crm_invoice.vat', 'crm_invoice.discount', 'crm_invoice_items.id', 'crm_invoice.isCancel')
                        ->groupBy('crm_invoice_items.id')
                        ->get();

                    foreach ($vehicles as $vehicle) {
                        if ($vehicle->isCancel != 1) {
                            if ($vehicle->type == 'Car' || $vehicle->type != 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_c += $vehicle->price / 1.12;
                                    $vat_c = $t_v_c * 0.12;
                                }
                                $cars++;
                                $total_money_c += $vehicle->price;
                            } elseif ($vehicle->type == 'Motorcycle') {
                                if ($vehicle->category_id != 1) {
                                    $t_v_m += $vehicle->price / 1.12;
                                    $vat_m = $t_v_m * 0.12;
                                }
                                $motor++;
                                $total_money_m += $vehicle->price;
                            }
                        }
                    }
                }
                if ($sub->id == 18) {
                    array_push($commercial, [$sub->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                } else if ($sub->id == 19) {
                    array_push($commercial, ['Non-Resident', ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_money_ms' => $total_money_m, 'total_money_cs' => $total_money_c, 'vat_cs' => $vat_c, 't_v_c' => $t_v_c, 't_v_m' => $t_v_m]]);
                    $cars = 0;
                    $motor = 0;
                    $vat_m = 0;
                    $total_money_m = 0;
                    $t_v_c = 0;
                    $t_v_m = 0;
                    $vat_c = 0;
                    $total_money_c = 0;
                }
            }
        }


        $vatable_inc = 0;
        $total_a = 0;
        $discount_amount = 0;
        $total_s = 0;
        // $total_s = 0;

        foreach ($stickers as $sticker) {
            $total_a += $sticker->price;
            $total_s += $sticker->price;
            $name = $sticker->srs_name;
            // $total_s += $sticker->price;
        }

        foreach ($discount as $item) {
            $discount_amount += $item->discount;
        }


        $total_a -= $discount_amount;
        $totalAmount = $total_a / 1.12;
        $vatable_inc = $totalAmount;
        $vatable_tax = $totalAmount * 0.12;

        $categoryGroup =   $stickers->groupBy('category_name');
        $residentSubGroup = collect([]);
        $nonResidentSubGroup = collect([]);
        $commercialSubGroup = collect([]);

        $total_resident = 0;
        $total_non_res = 0;
        $total_commercial = 0;
        $temp = [];
        $total_amm_r = 0;
        $total_count_r = 0;

        if (isset($categoryGroup['Resident'])) {
            $residentSubGroup = $categoryGroup['Resident']->groupBy('sub_category_name')->sortDesc();

            foreach ($residentSubGroup as $sub => $item) {

                $total = 0;
                $count = 0;
                foreach ($stickers as  $sticker) {

                    if ($sticker->sub_category_name == $sub && $sticker->category_name == 'Resident') {
                        $total += $sticker->price;
                        $count++;
                    }
                }
                array_push($temp, [$sub, $count, $total]);
            }
        } else {
            $categoryGroup['Resident'] = collect([]);
        }
        $nonr = [];
        if (isset($categoryGroup['Non-resident'])) {
            $nonResidentSubGroup = $categoryGroup['Non-resident']->groupBy('sub_category_name')->sortDesc();


            foreach ($nonResidentSubGroup as $sub => $item) {

                $total = 0;
                $count = 0;
                foreach ($stickers as  $sticker) {

                    if ($sticker->sub_category_name == $sub && $sticker->category_name == 'Non-resident') {
                        $total += $sticker->price;
                        $count++;
                    }
                }
                array_push($nonr, [$sub, $count, $total]);
            }
        } else {
            $categoryGroup['Non-resident'] = collect([]);
        }
        $non = [];
        if (isset($categoryGroup['Commercial'])) {
            $commercialSubGroup = $categoryGroup['Commercial']->groupBy('sub_category_name')->sortDesc();


            foreach ($commercialSubGroup  as $sub => $item) {

                $total = 0;
                $count = 0;
                foreach ($stickers as  $sticker) {

                    if ($sticker->sub_category_name == $sub && $sticker->category_name == 'Commercial') {
                        $total += $sticker->price;
                        $count++;
                    }
                }
                array_push($non, [$sub, $count, $total]);
            }
        } else {
            $categoryGroup['Commercial'] = collect([]);
        }




        $pdf = PDF::loadView('exports.date_sticker_filter', compact('stickers', 'to', 'from', 'categoryGroup', 'residentSubGroup', 'nonResidentSubGroup', 'commercialSubGroup', 'totalAmount', 'name', 'temp', 'nonr', 'non', 'counter', 'men', 'vatable_inc', 'total_a', 'commercial', 'discount_amount', 'vatable_tax', 'total_s'))->setPaper('a4', 'portrait');
        // return $pdf->stream();
         $pdf_file_name = '' . date("n-j-y h_i_a") . '_' . auth()->user()->name . '_ sticker_reports_for_' . $from . '-' . 'to_' . $to . '.pdf'; // set the desired file name
        $pdf->save(storage_path('app/public/generate_reports/' . $pdf_file_name));
        return $pdf->download('sticker_reports.pdf');
    }



    public function exportPDFHOA(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required',
            'sub_select' => 'required|integer'
        ]);



        $temp = [];
        $hoas = SrsHoa::all();

        $total_amount = 0;
        $sticker_count = 0;
        foreach ($hoas as $hoa) {
            $stickers = CrmMain::where('hoa', '=', $hoa->name)->get();
            $cars = 0;
            $motor = 0;
            $vat_m = 0;
            $total_money_m = 0;
            $vat_c = 0;
            $total_money_c = 0;

            foreach ($stickers as $sticker) {
                $vehicles = CrmInvoiceItems::join('crm_invoice', 'crm_invoice_items.invoice_no', '=', 'crm_invoice.id')
                    ->join('crm_vehicles', 'crm_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                    ->join('crm_mains', 'crm_mains.crm_id', '=', 'crm_invoice.crm_id')
                    ->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                    ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                    ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                    ->when($data['period'] == 1, function ($q) use ($data) {
                        return $q->whereDate('crm_invoice.created_at', Carbon::today());
                    })
                    ->when($data['period'] == 2, function ($q) use ($data) {
                        return $q->whereBetween('crm_invoice.created_at', [today()->startOfWeek(), today()->endOfWeek()]);
                    })
                    ->when($data['period'] == 3, function ($q) use ($data) {
                        return $q->whereYear('crm_invoice.created_at', $data['year'])
                            ->whereMonth('crm_invoice.created_at', $data['sub_select']);
                    })
                    ->when($data['period'] == 4, function ($q) use ($data) {
                        return $q->whereYear('crm_invoice.created_at', $data['year'])
                            ->when($data['sub_select'] == 1, function ($q) use ($data) {
                                return $q->whereMonth('crm_invoice.created_at', '>=', 1)
                                    ->whereMonth('crm_invoice.created_at', '<=', 3);
                            })
                            ->when($data['sub_select'] == 2, function ($q)  use ($data) {
                                return $q->whereMonth('crm_invoice.created_at', '>=', 4)
                                    ->whereMonth('crm_invoice.created_at', '<=', 6);
                            })
                            ->when($data['sub_select'] == 3, function ($q) use ($data) {
                                return $q->whereMonth('crm_invoice.created_at', '>=', 7)
                                    ->whereMonth('crm_invoice.created_at', '<=', 9);
                            })
                            ->when($data['sub_select'] == 4, function ($q) use ($data) {
                                return $q->whereMonth('crm_invoice.created_at', '>=', 10)
                                    ->whereMonth('crm_invoice.created_at', '<=', 12);
                            });
                    })
                    ->when($data['period'] == 5, function ($q) use ($data) {
                        return $q->whereYear('crm_invoice.created_at', $data['year']);
                    })
                    ->where('crm_vehicles.crm_id', '=', $sticker->customer_id)
                    ->orderBy('crm_invoice.created_at')
                    ->select('crm_vehicles.new_sticker_no', 'crm_vehicles.crm_id', 'crm_vehicles.plate_no', 'srs_users.name AS srs_name', 'crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'crm_invoice.invoice_no', 'crm_mains.hoa',  'srs_categories.name AS category_name', 'srs_sub_categories.name AS sub_category_name', 'crm_invoice_items.price', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'crm_invoice.created_at', 'crm_vehicles.type as type', 'crm_invoice.vat')
                    ->groupBy('crm_vehicles.plate_no')
                    ->get();

                foreach ($vehicles as $vehicle) {
                    if ($vehicle->type == 'Car') {
                        $cars++;
                        $vat_c += $vehicle->vat;
                        $total_money_c += $vehicle->price;
                    } elseif ($vehicle->type == 'Motorcycle') {
                        $motor++;
                        $vat_m += $vehicle->vat;
                        $total_money_m += $vehicle->price;
                    }
                    $sticker_count++;
                    $total_amount += $vehicle->price;
                }
            }

            array_push($temp, [$hoa->name, ['cars' => $cars, 'motor' => $motor, 'total' => $cars + $motor, 'vat_ms' => $vat_m, 'total_moneys_m' => $total_money_m, 'total_moneys_c' => $total_money_c, 'vat_cs' => $vat_c]]);
        }



        $pdf = PDF::loadView('exports.stickers_list_hoa', compact('temp', 'total_amount', 'sticker_count'))->setPaper('a4', 'portrait');
        // return $pdf->stream();
        return $pdf->download('sticker_reports.pdf');
    }
    public function export_sticker_cv(Request $req)
    {
        $data = $req->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);


        return Excel::download(new StickerReport($data['year'], $data['period'], $data['status'], $data['sub_select']), 'sticker_data.xlsx');
    }

    public function sticker_export_excel(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;
        $or_cr = $req->or_cr_select;
        
        return Excel::download(new StickerReportFilter($status, $to, $from, $or_cr),  'sticker_data.xlsx');
    }

    public function sticker_export_excel_2(Request $req)
    {   
        $status = $req->user_filter;
        $to = $req->to;
        $from = $req->from;
        $or_cr = $req->or_cr_select;

        if($to == null && $from == null){
            return redirect()->back()->with('danger', 'Please fill all the fields');
        }

        $cashier = SrsUser::find($status)->name ?? 'All';

        if($to == now()->format('Y-m-d') && $from == now()->format('Y-m-d')) {
            try {
                $report = auth()->user()->dailyReports
                    ->where('report_date', now()->format('Y-m-d'))
                    ->first();

                if(!$report) {
                    auth()->user()->dailyReports()->create([
                        'cashier_id' => auth()->id(),
                        'report_date' => now()->format('Y-m-d'),
                        'counter' => 1,
                    ]);
                } else {
                    $report->update([
                        'counter' => $report->counter + 1
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('danger', 'Something went wrong');
            }
        }
        
        return Excel::download(new StickerReportFilter3($status, $to, $from, $or_cr), $from . '_' . $to . '_' . $cashier . '.xlsx' );
        // return Excel::download(new StickerReportFilter2($status, $to, $from, $or_cr),  'sticker_data.xlsx');
    }

    public function sticker_export_pdf(Request $req)
    {   
        $status = $req->user_filter;
        $to = $req->to;
        $from = $req->from;
        $or_cr = $req->or_cr_select;

        if($to == null && $from == null){
            return redirect()->back()->with('danger', 'Please fill all the fields');
        }

        $vatable_inc = 0;
        $total_a = 0;
        $discount_amount = 0;
        $total_s = 0;
        $totalAmount = 0;
        $name = "";
        $counter = 0;
        $cr_discount = 0;
        $cr_gross = 0;
        $cr_total_amm = 0;

        if ($status != 0) {
            $name = SrsUser::find($status)->name;
        } else {
            $name = 'All Cashier';
        }

        if ($status != 0) {
            $stickers = DB::table('srs_invoice_reports')
                ->where('action_by', $name)
                ->where('isCancel', 0)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->orderBy('created_at')
                ->groupBy('id')
                ->get();

            dd($stickers);


            // $stickers = DB::table('spc_invoice_items')
            //     ->join('spc_invoice', 'spc_invoice_items.invoice_no', '=', 'spc_invoice.invoice_no')
            //     ->join('crm_vehicles', 'spc_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
            //     ->join('crm_mains', 'crm_mains.customer_id', '=', 'spc_invoice.crm_id')
            //     //->join('srs_users', 'spc_invoice.action_by', '=', 'srs_users.name')
            //     ->join('spc_categories', 'spc_categories.id', '=', 'crm_mains.category_id')
            //     ->join('spc_subcat', 'spc_subcat.id', '=', 'crm_mains.sub_category_id')
            //     ->where('spc_invoice.action_by', $name)
            //     ->where('spc_invoice.isCancel', 0)
            //     ->whereDate('spc_invoice.created_at', '>=', $from)
            //     ->whereDate('spc_invoice.created_at', '<=', $to)
            //     ->orderBy('spc_invoice.created_at')
            //     ->select('crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'spc_invoice.invoice_no', 'crm_mains.hoa',  'spc_categories.name AS category_name', 'spc_subcat.name AS sub_category_name', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'spc_invoice.created_at', 'spc_invoice.or_number', 'spc_invoice.totalDiscount', 'spc_invoice.isCancel', 'spc_invoice.totalAmount',  'spc_invoice.totalDue', 'crm_mains.customer_id', 'spc_invoice_items.id', 'spc_invoice_items.basePrice as basePrice', 'spc_invoice_items.sellingPrice as sellingPrice', 'spc_invoice_items.security', 'spc_invoice_items.epf', 'spc_invoice_items.vat', 'spc_invoice_items.vatableSales',  'spc_invoice_items.compAmmDue', 'spc_invoice_items.compDiscount', 'spc_invoice_items.compSecurity', 'spc_invoice_items.compEpf', 'spc_invoice_items.compWhTax', 'spc_invoice_items.compVatSales', 'spc_invoice_items.compVat', 'new_sticker_no as sticker_no', 'spc_invoice.action_by', 'crm_vehicles.plate_no')
            //     //->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
            //     //->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
            //     ->groupBy('spc_invoice_items.id')
            //     ->get();

            $discount = DB::table('spc_invoice')
                ->join('crm_mains', 'crm_mains.customer_id', '=', 'spc_invoice.crm_id')
                //->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->whereDate('spc_invoice.created_at', '>=', $from)
                ->whereDate('spc_invoice.created_at', '<=', $to)
                ->groupBy('spc_invoice.invoice_no')
                ->where('spc_invoice.action_by', $name)
                ->where('spc_invoice.isCancel', 0)
                // ->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                // ->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->select('spc_invoice.totalDiscount', 'crm_mains.category_id')
                ->get();
        } else {
            // dd(DB::table('spc_invoice_items')->where('invoice_no', '24035354')->get());

            $stickers = DB::table('spc_invoice_items')
                ->join('spc_invoice', 'spc_invoice_items.invoice_no', '=', 'spc_invoice.invoice_no')
                ->join('crm_vehicles', 'spc_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.customer_id', '=', 'spc_invoice.crm_id')
                //->join('srs_users', 'spc_invoice.action_by', '=', 'srs_users.name')
                // ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                // ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                ->join('spc_categories', 'spc_categories.id', '=', 'crm_mains.category_id')
                ->join('spc_subcat', 'spc_subcat.id', '=', 'crm_mains.sub_category_id')
                //->where('spc_invoice.action_by', $name->name)
                ->where('spc_invoice.isCancel', 0)
                ->whereDate('spc_invoice.created_at', '>=', $from)
                ->whereDate('spc_invoice.created_at', '<=', $to)
                ->orderBy('spc_invoice.created_at')
                ->select('crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'spc_invoice.invoice_no', 'crm_mains.hoa',  'spc_categories.name AS category_name', 'spc_subcat.name AS sub_category_name', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'spc_invoice.created_at', 'spc_invoice.or_number', 'spc_invoice.totalDiscount', 'spc_invoice.isCancel', 'spc_invoice.totalAmount',  'spc_invoice.totalDue', 'crm_mains.customer_id', 'spc_invoice_items.id', 'spc_invoice_items.basePrice as basePrice', 'spc_invoice_items.sellingPrice as sellingPrice', 'spc_invoice_items.security', 'spc_invoice_items.epf', 'spc_invoice_items.vat', 'spc_invoice_items.vatableSales',  'spc_invoice_items.compAmmDue', 'spc_invoice_items.compDiscount', 'spc_invoice_items.compSecurity', 'spc_invoice_items.compEpf', 'spc_invoice_items.compWhTax', 'spc_invoice_items.compVatSales', 'spc_invoice_items.compVat', 'new_sticker_no as sticker_no', 'spc_invoice.action_by', 'crm_vehicles.plate_no')
                //->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                //->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->groupBy('spc_invoice_items.id')
                ->get();

            $discount = DB::table('spc_invoice')
                ->join('crm_mains', 'crm_mains.customer_id', '=', 'spc_invoice.crm_id')
                //->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->whereDate('spc_invoice.created_at', '>=', $from)
                ->whereDate('spc_invoice.created_at', '<=', $to)
                ->groupBy('spc_invoice.invoice_no')
                //->where('spc_invoice.action_by', $name->name)
                ->where('spc_invoice.isCancel', 0)
                // ->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                // ->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->select('spc_invoice.totalDiscount', 'crm_mains.category_id')
                ->get();

            $counter++;
        }

        if ($or_cr == 'or') {
            $stickers = $stickers->where('category_id', 2);
            $discount = $discount->where('category_id', 2);
        }

        if ($or_cr == 'cr') {
            $stickers = $stickers->where('category_id', 1);
            $discount = $discount->where('category_id', 1);
        }

        foreach ($stickers as $sticker) {
            if ($sticker->category_id == 2 || $sticker->category_id == 3) {
                $total_a += $sticker->sellingPrice;
                $total_s += $sticker->sellingPrice;
            } else {
                $cr_total_amm += $sticker->sellingPrice;
                $cr_gross += $sticker->sellingPrice;
            }

            $name = $sticker->action_by;
        }

        foreach ($discount as $item) {
            if ($item->category_id == 2 || $item->category_id == 3) {
                $discount_amount += $item->totalDiscount;
            } else {
                $cr_discount += $item->totalDiscount;
            }
        }

        $total_a -= $discount_amount;
        $cr_gross -= $cr_discount;
        $totalAmount = $total_a / 1.12;
        $vatable_inc = $totalAmount;
        $vatable_tax = $totalAmount * 0.12;

        $total_whTax_1 = $stickers->whereIn('category_id', [2,3])->sum('compWhTax');
        $total_whTax_2 = $stickers->whereIn('category_id', [1])->sum('compWhTax');
        
        LogReport::create([
            'action_by' =>   auth()->user()->name,
            'action' =>  'Account Name: ['  . auth()->user()->name . ']  Downloaded the report for To:' . $to  . ' From: ' . $from . ' Date Downloaded: [ ' . date("n-j-y h_i_a") . ' ] ',
        ]);

        $pdf = PDF::loadView('exports.sticker_list_filter_cv_4', [
            'datas' => $stickers, 'name' => $name, 'total_s' => $total_s, 'discount_amount' => $discount_amount, 'total_a' => $total_a, 'totalAmount' => $totalAmount, 'vatable_tax' => $vatable_tax,  'cr_discount' => $cr_discount, 'cr_gross' =>  $cr_gross, 'cr_total_amm' => $cr_total_amm, 'counter' => $counter, 'total_whTax_1' => $total_whTax_1, 'total_whTax_2' => $total_whTax_2
        ])->setPaper('a4', 'portrait');

        return $pdf->download('sticker_data.pdf');
    }

// josh patch 18-04-2023
    public function revenue_report(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;

        return Excel::download(new RevenueExport($status, $to, $from),  'revenue_data.xlsx');
    }
    // end josh patch 18-04-2023


    public function revenue_report_2(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;
        
        return Excel::download(new RevenueExport2($status, $from, $to),  'revenue_data.xlsx');
    }

    // josh patch 26-04-2023


    public function hoa_report(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;

        return Excel::download(new HoaExport($status, $to, $from),  'hoa_data.xlsx');
    }

    public function hoa_report_2(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;

        return Excel::download(new HoaExport2($status, $to, $from),  'hoa_data.xlsx');
    }
    //end josh patch 26-04-2023

    //josh patch 27-04-2023
    public function hoa_member_report(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;
        $status = $req->status;

        return Excel::download(new HoaMemberExport2($status, $to, $from),  'hoa_member_report.xlsx');
    }
    //end josh patch 27-04-2023

// josh patch 24-05-2023    

    public function price_export()
    {

        return Excel::download(new PriceExport,  'pricing_report.xlsx');
    }

        public function export_or_sticker_cv(Request $req)
    {
        $data = $req->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);


        return Excel::download(new OrStickerExport($data['year'], $data['period'], $data['status'], $data['sub_select']), 'sticker_or_data.xlsx');
    }

    public function export_cr_sticker_cv(Request $req)
    {
        $data = $req->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);


        return Excel::download(new CrStickerExport($data['year'], $data['period'], $data['status'], $data['sub_select']), 'sticker_cr_data.xlsx');
    }

    public function sticker_filter_cv_or(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;

        return Excel::download(new OrStickerReportFilterExport($status, $to, $from),  'sticker_or_data.xlsx');
    }

    public function sticker_filter_cv_cr(Request $req)
    {
        $status = $req->status;
        $to = $req->to;
        $from = $req->from;

        return Excel::download(new CrStickerReportFilterExport($status, $to, $from),  'sticker_cr_data.xlsx');
    }
}
