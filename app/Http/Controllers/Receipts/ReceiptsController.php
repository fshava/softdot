<?php

namespace App\Http\Controllers\Receipts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receipts\Receipt;                           
use App\Models\Receipts\Product;  
use App\Models\Receipts\Invoice;  
use DB;

class ReceiptsController extends Controller
{
    public function receipt(Request $request)
    {
        
        // a receipt transaction
        DB::transaction(function($request){
            //first create a receipt
            $id = DB::table('receipts')->insertGetId([
                'pupil_id'=>\Request::input('id'),
                'amount'=>\Request::input('amount'),
                'created_at'=>now()
                ]);
                // find the above receipt
                $receipt = Receipt::findOrFail($id);
                // create a product amount record
                $receipt->products()->attach(\Request::input('product_id'));
                // get the balance of the product paid for as it is in the invoice
                $bal = DB::table('invoice_product')->select('balance')->where('invoice_id','=',\Request::input('id'))->where('product_id','=',\Request::input('product_id'))->get();
                $plucked = $bal->pluck('balance');
                // calculate the new balance 
                $balance = $plucked[0] - \Request::input('amount');
                // update the balance in the invoice
                DB::table('invoice_product')->where('invoice_id','=',\Request::input('id'))->where('product_id','=',\Request::input('product_id'))->update(['balance'=>$balance]);
            });        
            // return the updated invoice
            $invoice = Invoice::findOrFail(\Request::input('id'));
            $v=$invoice->products()->where('balance','>',0)->get();
            $sum=$invoice->products()->sum('balance');
            return \response()->json(['invoice'=>$v,'total'=>$sum]);
    }

    public function getReceiptsForDay()
    {

    }

    public function getReceiptsForDayByCategory()
    {

    }

    public function getReceiptsForMonthByCategory()
    {
        
    }
    public function getReceiptsForYearByCategory()
    {
        
    }
}
