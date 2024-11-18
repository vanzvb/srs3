<?php

namespace App\Exports;

use App\Models\CrmInvoice;
use App\Models\CrmInvoiceItems;
use App\Models\CrmMain;
use App\Models\SrsHoa;
use App\Models\SrsUser;
use App\Models\CrmVehicles;
use App\Models\SrsCategories;
use App\Models\SrsSubCategories;
use App\Models\LogReport;
use App\Exports\SrsRequestsPerMonthSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StickerReportFilter3 implements
    FromView,
    ShouldAutoSize,
    WithStyles

{
    use Exportable;

    private $status;
    private $to;
    private $from;
    private $or_cr;

    function __construct(int $status, String $to, String $from, $or_cr = null)
    {

        $this->status = $status;
        $this->to = $to;
        $this->from = $from;
        $this->or_cr = $or_cr;
    }


    public function  view(): View
    {
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

        // dd(DB::table('spc_invoice')->latest()->first());
        //dd(DB::table('spc_invoice')->latest()->get(), DB::table('spc_invoice_items')->latest()->get());

        if ($this->status != 0) {
            $name = SrsUser::find($this->status)->name;
        } else {
            $name = 'All Cashier';
        }

        if ($this->status != 0) {
            $stickers = DB::table('spc_invoice_items')->join('spc_invoice', 'spc_invoice_items.invoice_no', '=', 'spc_invoice.invoice_no')
                ->join('crm_vehicles', 'spc_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'spc_invoice.c_id')
                //->join('srs_users', 'spc_invoice.action_by', '=', 'srs_users.name')
                ->join('spc_categories', 'spc_categories.id', '=', 'crm_mains.category_id')
                ->join('spc_subcat', 'spc_subcat.id', '=', 'crm_mains.sub_category_id')
                ->where('spc_invoice.action_by', $name)
                ->where('spc_invoice.isCancel', 0)
                ->whereDate('spc_invoice.created_at', '>=', $this->from)
                ->whereDate('spc_invoice.created_at', '<=', $this->to)
                ->orderBy('spc_invoice.created_at')
                ->select('crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'spc_invoice.invoice_no', 'crm_mains.hoa',  'spc_categories.name AS category_name', 'spc_subcat.name AS sub_category_name', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'spc_invoice.created_at', 'spc_invoice.or_number', 'spc_invoice.totalDiscount', 'spc_invoice.isCancel', 'spc_invoice.totalAmount',  'spc_invoice.totalDue', 'crm_mains.customer_id', 'spc_invoice_items.id', 'spc_invoice_items.basePrice as basePrice', 'spc_invoice_items.sellingPrice as sellingPrice', 'spc_invoice_items.security', 'spc_invoice_items.epf', 'spc_invoice_items.vat', 'spc_invoice_items.vatableSales',  'spc_invoice_items.compAmmDue', 'spc_invoice_items.compDiscount', 'spc_invoice_items.compSecurity', 'spc_invoice_items.compEpf', 'spc_invoice_items.compWhTax', 'spc_invoice_items.compVatSales', 'spc_invoice_items.compVat', 'new_sticker_no as sticker_no', 'spc_invoice.action_by', 'crm_vehicles.plate_no')
                //->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                //->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->groupBy('spc_invoice_items.id')
                ->get();

            $discount = DB::table('spc_invoice')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'spc_invoice.c_id')
                //->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->whereDate('spc_invoice.created_at', '>=', $this->from)
                ->whereDate('spc_invoice.created_at', '<=', $this->to)
                ->groupBy('spc_invoice.invoice_no')
                ->where('spc_invoice.action_by', $name)
                ->where('spc_invoice.isCancel', 0)
                // ->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                // ->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->select('spc_invoice.totalDiscount', 'crm_mains.category_id')
                ->get();
        } else {
            $stickers = DB::table('spc_invoice_items')->join('spc_invoice', 'spc_invoice_items.invoice_no', '=', 'spc_invoice.invoice_no')
                ->join('crm_vehicles', 'spc_invoice_items.crm_vehicle_id', '=', 'crm_vehicles.id')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'spc_invoice.c_id')
                //->join('srs_users', 'spc_invoice.action_by', '=', 'srs_users.name')
                // ->join('srs_categories', 'srs_categories.id', '=', 'crm_mains.category_id')
                // ->join('srs_sub_categories', 'crm_mains.sub_category_id', '=', 'crm_mains.sub_category_id')
                ->join('spc_categories', 'spc_categories.id', '=', 'crm_mains.category_id')
                ->join('spc_subcat', 'spc_subcat.id', '=', 'crm_mains.sub_category_id')
                //->where('spc_invoice.action_by', $name->name)
                ->where('spc_invoice.isCancel', 0)
                ->whereDate('spc_invoice.created_at', '>=', $this->from)
                ->whereDate('spc_invoice.created_at', '<=', $this->to)
                ->orderBy('spc_invoice.created_at')
                ->select('crm_mains.firstname', 'crm_mains.middlename', 'crm_mains.lastname', 'spc_invoice.invoice_no', 'crm_mains.hoa',  'spc_categories.name AS category_name', 'spc_subcat.name AS sub_category_name', 'crm_mains.category_id', 'crm_mains.sub_category_id', 'crm_mains.hoa', 'spc_invoice.created_at', 'spc_invoice.or_number', 'spc_invoice.totalDiscount', 'spc_invoice.isCancel', 'spc_invoice.totalAmount',  'spc_invoice.totalDue', 'crm_mains.customer_id', 'spc_invoice_items.id', 'spc_invoice_items.basePrice as basePrice', 'spc_invoice_items.sellingPrice as sellingPrice', 'spc_invoice_items.security', 'spc_invoice_items.epf', 'spc_invoice_items.vat', 'spc_invoice_items.vatableSales',  'spc_invoice_items.compAmmDue', 'spc_invoice_items.compDiscount', 'spc_invoice_items.compSecurity', 'spc_invoice_items.compEpf', 'spc_invoice_items.compWhTax', 'spc_invoice_items.compVatSales', 'spc_invoice_items.compVat', 'new_sticker_no as sticker_no', 'spc_invoice.action_by', 'crm_vehicles.plate_no')
                //->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                //->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->groupBy('spc_invoice_items.id')
                ->get();

            $discount = DB::table('spc_invoice')
                ->join('crm_mains', 'crm_mains.crm_id', '=', 'spc_invoice.c_id')
                //->join('srs_users', 'crm_invoice.action_by', '=', 'srs_users.id')
                ->whereDate('spc_invoice.created_at', '>=', $this->from)
                ->whereDate('spc_invoice.created_at', '<=', $this->to)
                ->groupBy('spc_invoice.invoice_no')
                //->where('spc_invoice.action_by', $name->name)
                ->where('spc_invoice.isCancel', 0)
                // ->where('crm_mains.firstname', 'NOT LIKE', '%Test%')
                // ->where('crm_mains.firstname', 'NOT LIKE', '%TEST%')
                ->select('spc_invoice.totalDiscount', 'crm_mains.category_id')
                ->get();

            $counter++;
        }

        if ($this->or_cr == 'or') {
            $stickers = $stickers->where('category_id', 2);
            $discount = $discount->where('category_id', 2);
        }

        if ($this->or_cr == 'cr') {
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
        
        // LogReport::create([
        //     'action_by' =>   auth()->user()->name,
        //     'action' =>  'Account Name: ['  . auth()->user()->name . ']  Downloaded the report for To:' . $this->to  . ' From: ' . $this->from . ' Date Downloaded: [ ' . date("n-j-y h_i_a") . ' ] ',
        // ]);

        return view('exports.sticker_list_filter_cv_3', [
            'datas' => $stickers, 'name' => $name, 'total_s' => $total_s, 'discount_amount' => $discount_amount, 'total_a' => $total_a, 'totalAmount' => $totalAmount, 'vatable_tax' => $vatable_tax,  'cr_discount' => $cr_discount, 'cr_gross' =>  $cr_gross, 'cr_total_amm' => $cr_total_amm, 'counter' => $counter, 'total_whTax_1' => $total_whTax_1, 'total_whTax_2' => $total_whTax_2
        ]);
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
