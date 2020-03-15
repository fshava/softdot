<?php

namespace App\Http\Controllers\Receipts;

use DB;
use Illuminate\Http\Request;
use App\Models\Receipts\Pupil;
use App\Models\Receipts\Invoice;  
use App\Models\Receipts\Product;  
use App\Http\Controllers\Controller;
use App\Models\Receipts\Receipt;                           //the model pupil

class InvoicesController extends Controller
{
    // THIS CONTROLLER IS RESPONSIBLE FOR MANIPULATING PUPILS FEES ACCOUNTS
    /**
     * add multiple products to a specific invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function addToInvoice(Request $request)
    {
        //foreach product id from the request, add the product to the invoice with id $id from the route parameter
       
            // find the invoice of the pupil
            $invoice = Invoice::findOrFail($request->id);
            // if invoice already have the product, abort the operation by returning an error flag
            $exists = $invoice->products()->where('product_id',$request->items)->count();
            if ($exists>0) {    
                // abort addition 
                $v=$invoice->products()->where('balance','>',0)->get();
                $sum=$invoice->products()->sum('balance');
                return \response()->json(['invoice'=>$v,'total'=>$sum,'error'=>'pupil already charged this fee']);
            }
            //find a product with  id=$item 
            $product = Product::findOrFail($request->items);
            //link the found product to the invoice with id=$id and also add the balance of the amount 
            $product->invoices()->attach($request->id,['balance'=>$product->amount]);
            $v=$invoice->products()->where('balance','>',0)->get();
            $sum=$invoice->products()->sum('balance');
            return \response()->json(['invoice'=>$v,'total'=>$sum]);
            
    }

    //  remove multiple products from a specific invoice
    public function removeFromInvoice(Request $request, $id)
    {
        //foreach product id from the request, remove the product from the invoice with id $id from the route parameter
        foreach ($request->items as $item) {
            //find a product with  id=$item 
            $product = Product::findOrFail($item);
            //link the found product to the invoice with id=$id and also add the balance of the amount 
            $product->invoices()->detach($id);
        }
        
    }
    /* 
        add a product to many invoices
      
    */
    public function addProductToAllInvoices(Request $request)
    {
        $product = Product::findOrFail($request->id);
        //foreach product id from the request, remove the product from the invoice with id $id from the route parameter
        $pupils = Pupil::where('grade',$request->grade)->get();
        foreach ($pupils as $pupil) {
            $v = Invoice::findOrFail($pupil->id);
            // array_push($z,$v);
            $product->invoices()->attach($v,['balance'=>$product->amount]);
        }
        return \response()->json(['status'=>'ok']);
    }
    /* 
    |   add multiple products to multiple invoices, eg: add products [1,2,3,4,5,6,7,8,9] to each of invoices [1,2,3,4,5,6,7,8,9]
    |   expects within the request payload two arrays containing ids of products and of invoices
    */
    public function addManyProductsToManyInvoices(Request $request)
    {
        //iterate products with id=ids
        foreach ($request->ids as $id) {
            //find the product with id=id
            $product = Product::findOrFail($id);
            //iterate invoices with id=invoice_id
            foreach ($request->invoices as $invoice_id) {
                //check if a record does not exists
                if (!$product->invoices()->where('id', $invoice_id)->exists()) {
                    //attach a product to the invoice
                    $product->invoices()->attach($invoice_id,['balance'=>$product->amount]);                  
                }
            }
        }

    }
    /* 
    |   get an invoice of a pupil
     */
    public function getInvoice(Request $request)
    {
        $id = $request->id;
        $invoice = Invoice::findOrFail($id);
        $v=$invoice->products()->where('balance','>',0)->get();
        $sum=$invoice->products()->sum('balance');
        return \response()->json(['invoice'=>$v,'total'=>$sum]);
        
    }

    
}
