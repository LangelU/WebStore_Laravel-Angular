<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use DB;

class InvoiceController extends Controller
{
    public function index()
    {
        //
    }

    public function createInvoice(){
        $tentaviveNumber = rand(0000000001, 9999999999);
        //print($tentaviveNumber);
        $invNumberSQL = DB::table("invoices")->select("invoice_number")
            ->where('invoice_number', '=', $tentaviveNumber)->get();
        $invNumber = DB::select($invNumberSQL);
        if($invNumber->isEmpty()){
            $invoiceNumber = $invNumber;
            $newInvoice = new Invoice;

            $newInvoice->invoice_number = $newInvoice;
            
        }
    }

    public function showInvoices(Invoice $invoice)
    {
        //
    }


    public function deleteInvoice(Invoice $invoice)
    {
        //
    }
}
