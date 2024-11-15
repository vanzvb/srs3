<?php

namespace App\Http\Controllers;

use App\Exports\CrmExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CrmMain;
use App\Models\SrsHoa;
use App\Models\CrmInvoice;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceExport;
use App\Exports\InvoieExportFilter;
use App\Models\SrsUser;
use App\Exports\StickerExport;
use App\Exports\StickerCashierExport;
use App\Models\CrmVehicles;
use App\Models\CrmInvoiceItems;
use App\Models\logs_invoice_hist;
use Carbon\Carbon;
use Exception;

class InvoiceController extends Controller
{


    private function getNextId($category, $subCategory, $hoa, $hoaType = 'string')
    {
        try {
            $today = now();
            $series = $today->format('y');
            $lastInvoice = CrmInvoice::select('invoice_no')->latest()->first();

            if ($lastInvoice) {
                $lastSeriesNumber = (int)substr($lastInvoice->invoice_no, 2);
            } else {
                $lastSeriesNumber = 0;
            }

            do {
                $lastSeriesNumber++;
                $srn = str_pad((string)$lastSeriesNumber, 6, '0', STR_PAD_LEFT);
                $crm = CrmInvoice::where('invoice_no', '=', $series . '' . $srn)->exists();
            } while ($crm);

            return $series . $srn;
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Something fucking wrong');
        }
    }



    public function index($crm_id, $invoice_no)
    {
        $crms =  DB::select("SELECT * FROM `crm_mains` WHERE crm_id =  $crm_id LIMIT 1");


        $items = DB::select("SELECT a.price, a.id as item_id, a.invoice_no as invoice_id, b.id AS vehicle_id, b.plate_no, b.new_sticker_no FROM `crm_invoice_items` a INNER JOIN `crm_vehicles` b ON a.crm_vehicle_id = b.id WHERE a.invoice_no = '$invoice_no'");

        $ins = DB::select("SELECT * FROM `crm_invoice` WHERE id= '$invoice_no'");



        return view('crm.invoice_edit', ['crms' => $crms, 'items' => $items, 'ins' => $ins]);
    }


    public function vat($crm_id, $invoice_no)
    {
        $crms =  DB::select("SELECT * FROM `crm_mains` WHERE crm_id =  $crm_id LIMIT 1");

        $invoices = DB::select('SELECT * FROM `crm_invoice` a INNER JOIN srs_users b ON a.action_by = b.id WHERE invoice_no = "' . $invoice_no . '" LIMIT 1');

        $ins = DB::select('SELECT * FROM `crm_invoice` WHERE invoice_no = "' . $invoice_no . '" LIMIT 1');

        $items = DB::select("SELECT * FROM `crm_invoice_items` a INNER JOIN crm_vehicles b ON a.crm_vehicle_id = b.id WHERE invoice_no = '" . $ins[0]->id . "'");



        return view('crm.print_invoice', ['crms' => $crms, 'invoices' => $invoices, 'items' => $items]);
    }

    public function edit_billing(Request $request)
    {

        $sub = $request->sub;

        $total = $sub - $request->discount;

        $vehicle_id = $request->input('vehicle_id');
        $sticker_no = $request->input('sticker_no');

        $invoice = CrmInvoice::find($request->input('id'));
        $crm = CrmMain::where('crm_id', $invoice->crm_id)->first();
        $customer_id = $crm->customer_id;
        // $invoice->or_number = $request->input('or_number');
        $invoice->or_number = $request->input('or_cr');
        $invoice->sub_total = $total;
        $invoice->discount = $request->discount;
        $invoice->save();

        for ($i = 0; $i < count($vehicle_id); $i++) {
            $vehicle = CrmVehicles::find($vehicle_id[$i]);
            $vehicle->new_sticker_no = $sticker_no[$i];
            $vehicle->save();
        }

        logs_invoice_hist::create([
            'action_by' =>   auth()->user()->name,
            'action' =>  'Edit Invoice number: [' . $invoice->invoice_no . '] and Customer ID: [ ' . $customer_id . '] Reason of: [' . $request->reeason_of . '] From amount: [' . $invoice->total_amount . '] To amount: [' . $total . '] Applied discount of: [' .  $request->discount . ' ] ',
            'customer_id' => $customer_id,
            'invoice_id' => $invoice->invoice_no,
        ]);
        // redirect to the billing details page or display a success message
        return redirect()
            ->back()
            ->withInput()->with('success', 'Billing details updated successfully');
    }

    public function update_isprint_novat($invoice_no)
    {
        $printed_date = Carbon::now();
        $invoice = CrmInvoice::where('invoice_no', $invoice_no)->first();

        if (!$invoice) {
            return response()->json([
                'error' => 'Invoice not found'
            ], 404);
        }

        $invoice->isPrint = '1';
        $invoice->printed_date = $printed_date;
        $invoice->save();

        return response()->json($invoice);
    }

    public function update_isprint_withvat($invoice_no)
    {
        $printed_date = Carbon::now();
        $invoice = CrmInvoice::where('invoice_no', $invoice_no)->first();

        if (!$invoice) {
            return response()->json([
                'error' => 'Invoice not found'
            ], 404);
        }

        $invoice->isPrint = '1';
        $invoice->printed_date = $printed_date;
        $invoice->save();

        return response()->json($invoice);
    }


    public function with_vat_index($crm_id, $invoice_no)
    {
        $total = 0;
        $vat = 0;
        $vs = 0;
        $four = 400;
        $crms =  DB::select("SELECT * FROM `crm_mains` WHERE crm_id =  $crm_id LIMIT 1");

        $invoices = DB::select('SELECT * FROM `crm_invoice` a INNER JOIN srs_users b ON a.action_by = b.id WHERE invoice_no = "' . $invoice_no . '" LIMIT 1');

        $ins = DB::select('SELECT * FROM `crm_invoice` WHERE invoice_no = "' . $invoice_no . '" LIMIT 1');

        $items = DB::select("SELECT * FROM `crm_invoice_items` a INNER JOIN crm_vehicles b ON a.crm_vehicle_id = b.id WHERE invoice_no = '" . $ins[0]->id . "'");

        $total = $invoices[0]->total_amount / 1.12;
        $vs = $total - 400;
        $vat = $total * 0.12;

        $total = round($total, 2);
        $vs = round($vs, 2);
        $vat = round($vat, 2);
        $four = round($four, 2);



        return view('crm.print_invoice_withv', ['crms' => $crms, 'invoices' => $invoices, 'items' => $items, 'total' => $total, 'vat' => $vat, 'vs' => $vs, 'four' => $four]);
    }




    public function invoice_process(Request $req)
    {


        try {
            $crm = DB::table('crm_mains')->where('crm_id', $req->crm_id)->first();
            $customer_id = $crm->customer_id;
            // dd($req->dis);
            if ($req->discount > $req->totalSub) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Discount cannot be greater than total amount.');
            }
            $invoice_no = $this->getNextId($crm->category_id, $crm->sub_category_id, $crm->hoa);
            $new_sticker = $req->new_sticker;
            $year = date('Y');
            $vehicle = $req->vehicle_id;
            $price = $req->price;

            if ($req->category_id == 2) {
                $total_a = $req->totalAmount * 0.12;
                $invoice_id =   DB::table('crm_invoice')->insertGetId([
                    'invoice_no' => $invoice_no,
                    'total_amount' =>  $req->totalSub,
                    'sub_total' => $req->totalAmount,
                    'vat' => $total_a,
                    'or_number' => $req->or_number,
                    'remarks' => $req->remarks,
                    'crm_id' => $req->crm_id,
                    'discount' => $req->discount,
                    'action_by' => Auth::id(),
                    'customer_id' => '1'
                ]);
            } else {
                $invoice_id =   DB::table('crm_invoice')->insertGetId([
                    'invoice_no' => $invoice_no,
                    'total_amount' =>  $req->totalSub,
                    'sub_total' => $req->totalAmount,
                    'or_number' => $req->or_number,
                    'remarks' => $req->remarks,
                    'crm_id' => $req->crm_id,
                    'discount' => $req->discount,
                    'action_by' => Auth::id(),
                    'customer_id' => '1'
                ]);
            }


            $last_inserted_id = $invoice_id;
            // dd($req->totalCount);
            for ($i = 0; $i < $req->totalCount; $i++) {
                DB::table('crm_invoice_items')->insertGetId([
                    'invoice_no' =>  $last_inserted_id,
                    'crm_vehicle_id' => $vehicle[$i],
                    'price' => $price[$i],
                ]);

                DB::table('crm_vehicles')
                    ->where('id',  $vehicle[$i])  // find your user by their email
                    ->limit(1)  // optional - to ensure only one record is updated.
                    ->update(array('sticker_date' => $year, 'new_sticker_no' =>  $new_sticker[$i]));  // update the record in the DB. 
            }

            if ($req->srs_request_id != NULL) {
                DB::table('srs_requests')
                    ->where('request_id', $req->srs_request_id)  // find your user by their email
                    ->limit(1)  // optional - to ensure only one record is updated.
                    ->update(array('invoice_no' => $invoice_no));  // update the record in the DB. 
            }
            logs_invoice_hist::create([
                'action_by' =>   auth()->user()->name,
                'action' =>  'Created Inv No [ ' . $invoice_no . '] OR Number: ' . $req->or_number . ' Total Amount [ ' . $req->totalAmount . ' ]  discount of [' . $req->discount . ']',
                'customer_id' => $customer_id,
                'invoice_id' => $invoice_no,

            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with('success', 'Success');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'CRMX: Billing: Submit Invoice L257 ERROR DETECTED. Please Contact SRS Administrator, Invoice No: ' .  $invoice_no . 'Account Name: ' . $crm->firstname . ' ' . $crm->middlename . ' ' . $crm->lastname . 'Atempted by: ' . auth()->user()->name);
        }
    }

    public function crm_export(Request $request)
    {


        // dd('sdsd');
        $data = $request->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);


        return (new CrmExport($data['year'], $data['period'], $data['status'], $data['sub_select']))->download('crmx_list.xlsx');
    }
    public function invoice_export(Request $req)
    {
        $category_id = $req->category_id;
        $to = $req->to;
        $from = $req->from;

        return Excel::download(new InvoiceExport($category_id, $to, $from),  'invoice.xlsx');
    }

    public function filter_export_invoice(Request $request)
    {
        // dd('sdsd');
        $data = $request->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);


        return (new InvoieExportFilter($data['year'], $data['period'], $data['status'], $data['sub_select']))->download('invoice.xlsx');
    }


    public function sticker_report(Request $req)
    {

        return (new StickerExport($req->agent_id, $req->period))->download('sticker_report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        // return Excel::download(new StickerExport($req->agent_id, $req->period),  'sticker_report.xlsx');
    }


    public function cancelOr(Request $req)
    {
        $invoice_no = $req->invoice_no;
        $reason_or_cancel = $req->reason_or_cancel;
         $customer_id = "";
        $amount = 0;
        $discount = 0;
        $or = "";
        // dd($invoice_no . " " . $reason_or_cancel);
        // $id = $req->id;
        $message = "Cancelled";

        $invoice = CrmInvoice::where('invoice_no', $invoice_no)->first();
        if ($invoice) {
            $crm = CrmMain::where('crm_id', $invoice->crm_id)->first();
            $customer_id = $crm->customer_id;
            $discount = $invoice->discount;
            $amount = $invoice->sub_total;
            $or = $invoice->or_number;
            $invoice->or_number = '' . $message . ' ( ' . $invoice->or_number . ' ) ';
            $invoice->total_amount = 0;
            $invoice->sub_total = 0;
            $invoice->vat = 0;
            $invoice->discount = 0;
            $invoice->remarks = '';
            $invoice->isCancel = 1;
            $invoice->reason_or_cancel = $reason_or_cancel;
            $invoice->save();
            $invoice_items = CrmInvoiceItems::where('invoice_no', $invoice->id)->get();
            if ($invoice_items->count()) {
                foreach ($invoice_items as $item) {
                    $item->price = 0;
                    $item->save();
                    $vehicle = CrmVehicles::where('id', $item->crm_vehicle_id)->first();
                    if ($vehicle) {
                        $vehicle->sticker_date = NULL;
                        $vehicle->new_sticker_no = NULL;
                        $vehicle->save();
                    }
                }
            }
        }

         logs_invoice_hist::create([
            'action_by' =>   auth()->user()->name,
            'action' =>  'Cancel OR|CR Number [' . $or . '] Inv no [' . $invoice_no . '] With Customer ID : [' . $customer_id . '] Total amount of: [' . $amount . '] With total discount : [' . $discount . ']',
            'customer_id' => $customer_id,
            'invoice_id' => $invoice_no,
        ]);
        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Succesfully cancelled OR|CR');
    }

    public function get_or(Request $req)
    {
        $invoice_no = $req->invoice_no;
        $invoice = CrmInvoice::where('invoice_no', $invoice_no)->first();

        return response()->json($invoice);
    }

    public function invoice_report_export(Request $req)
    {
        $data = $req->validate([
            'year' => 'required|integer|date_format:Y',
            'period' => 'required|integer',
            'status' => 'required|integer',
            'sub_select' => 'required|integer'
        ]);
        return (new InvoieExportFilter($data['year'], $data['period'], $data['status'], $data['sub_select']))->download('invoice.xlsx');
    }
}
