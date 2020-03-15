<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Fee;
use App\Models\Api\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerFeeController extends Controller
{
    /** 
    *   Attach a fee to a Customer
    *   @param Illuminate\Http\Request $request
    *   @return Illuminate\Http\Response
    */
    public function attachFeeToOne(Request $request)
    {
        // get a Customer
        $customer = Customer::findOrFail($request->customer_id);
         // if invoice already have the product, abort the operation by returning an error flag
         $exists = $customer->fees()->where('fee_id',$request->fee_id)->count();
         if ($exists>0) {    
             return \response()->json(['warning'=>'Customer already invoiced']);
         }
        // get the balance of the Customer
        $balance = $customer->balance;
        // find a fee
        $fee = Fee::findOrFail($request->fee_id);
        // calculate the new balance and update Customer record
        $customer->balance = $balance + $fee->amount;
        $customer->save();
        // if fee is attached to a Customer and balance of a Customer is updated: return status ok
        if($customer->fees()->attach($request->fee_id,['balance'=>$fee->amount,'created_at'=>\now(),'updated_at'=>\now()])){

            return \response()->json(['status'=>'ok']);
        }
    }

    /**
     *  Attach a fee to all Customers
     *  @param Illuminate\Http\Request $request (fee_id)
     *  @return Illuminate\Http\Response
     */
    public function attachFeeToAll(Request $request)
    {
        Customer::chunkById(100,function ($customers){
            // find a fee
            $fee = Fee::findOrFail(\Request::input('fee_id'));
                foreach ($customers as $customer) {
                // if invoice already have the product, abort the operation by returning an error flag
                $exists = $customer->fees()->where('fee_id',\Request::input('fee_id'))->count();
                if ($exists==0) {    
                    // return \response()->json(['warning'=>'Customer already invoiced']);
                    // get the balance of the Customer
                    $balance = $customer->balance;
                    // calculate the new balance and update Customer record
                    $customer->balance = $balance + $fee->amount;
                    $customer->save();
                    $customer->fees()->attach(\Request::input('fee_id'),['balance'=>$fee->amount,'created_at'=>\now(),'updated_at'=>\now()]);
                }
            }
        });
    }
    public function detachFeeFromOne(Request $request)
    {
            // get a Customer
            $customer = Customer::findOrFail($request->customer_id);
            // get the balance of the Customer
            $balance = $customer->balance;
            // if record of a fee exists, proceed: otherwise return error
            $exists = $customer->fees()->where('fee_id',\Request::input('fee_id'))->count();
            if ($exists!=0) {  
            $fee = Fee::findOrFail($request->fee_id);
            // find a fee
            // calculate the new balance and update Customer record
            $customer->balance = $balance - $fee->amount;
            $customer->save();
            $customer->fees()->detach($request->fee_id);
            return \response()->json(['status'=>'ok']);
          }
    }
    public function detachFeeFromAll(Request $request)
    {
        Customer::chunkById(100,function ($customers){
            // find a fee
            $fee = Fee::findOrFail(\Request::input('fee_id'));
            foreach ($customers as $customer) {
                $exists = $customer->fees()->where('fee_id',\Request::input('fee_id'))->count();
                if ($exists!=0) {
                    // get the balance of the Customer
                    $balance = $customer->balance;
                    // calculate the new balance and update Customer record
                    $customer->balance = $balance - $fee->amount;
                    $customer->save();
                    $customer->fees()->detach(\Request::input('fee_id'));
                }
            }
        });
        return \response()->json(['status'=>'ok']);
    }
    public function invoice(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        return \response()->json(['fees'=>$customer->fees()->where('balance','>',0)->get(),'customer'=>$customer]);
    }
}
