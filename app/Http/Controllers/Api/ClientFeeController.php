<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Fee;
use App\Models\Api\Client;
use App\Models\Api\Taking;
use App\Models\Api\Revenue;
use App\Models\Api\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClientFeeController extends Controller
{
    /** 
    *   Attach a fee to a client
    *   @param Illuminate\Http\Request $request
    *   @return Illuminate\Http\Response
    */
    public function attachFeeToOne(Request $request)
    {
        // get a client
        $client = Client::findOrFail($request->client_id);
         // if invoice already have the product, abort the operation by returning an error flag
         $exists = $client->fees()->where('fee_id',$request->fee_id)->count();
         if ($exists>0) {    
             return \response()->json(['status'=>'client already invoiced']);
         }
        // get the balance of the client
        $balance = $client->balance;
        // find a fee
        $fee = Fee::findOrFail($request->fee_id);
        // calculate the new balance and update client record
        $client->balance = $balance + $fee->amount;
        $client->save();
        // if fee is attached to a client and balance of a client is updated: return status ok
        if($client->fees()->attach($request->fee_id,['balance'=>$fee->amount,'created_at'=>\now(),'updated_at'=>\now()])){

            return \response()->json(['status'=>'ok']);
        }
    }

    /**
     *  Attach a fee to all clients
     *  @param Illuminate\Http\Request $request (fee_id)
     *  @return Illuminate\Http\Response
     */
    public function attachFeeToAll(Request $request)
    {
        Client::chunkById(100,function ($clients){
            // find a fee
            $fee = Fee::findOrFail(\Request::input('fee_id'));
                foreach ($clients as $client) {
                // if invoice already have the product, abort the operation by returning an error flag
                $exists = $client->fees()->where('fee_id',\Request::input('fee_id'))->count();
                if ($exists==0) {    
                    // return \response()->json(['warning'=>'client already invoiced']);
                    // get the balance of the client
                    $balance = $client->balance;
                    // calculate the new balance and update client record
                    $client->balance = $balance + $fee->amount;
                    $client->save();
                    $client->fees()->attach(\Request::input('fee_id'),['balance'=>$fee->amount,'created_at'=>\now(),'updated_at'=>\now()]);
                }
            }
        });
        return \response()->json(['status'=>'ok']);
    }
    public function detachFeeFromOne(Request $request)
    {
        // get a client
        $client = Client::findOrFail($request->client_id);
          // get the balance of the client
          $balance = $client->balance;
          $exists = $client->fees()->where('fee_id',\Request::input('fee_id'))->count();
            if ($exists!=0) {  
          // find a fee
          $fee = Fee::findOrFail($request->fee_id);
          // calculate the new balance and update client record
          $client->balance = $balance - $fee->amount;
          $client->save();
        $client->fees()->detach($request->fee_id);
        return \response()->json(['status'=>'ok']);
        }
    }
    public function detachFeeFromAll(Request $request)
    {
        Client::chunkById(100,function ($clients){
            // find a fee
            $fee = Fee::findOrFail(\Request::input('fee_id'));
            foreach ($clients as $client) {
                $exists = $client->fees()->where('fee_id',\Request::input('fee_id'))->count();
                if ($exists!=0) {  
                // get the balance of the client
                $balance = $client->balance;
                // calculate the new balance and update client record
                $client->balance = $balance - $fee->amount;
                $client->save();
                $client->fees()->detach(\Request::input('fee_id'));
                }
            }
        });
        return \response()->json(['status'=>'ok']);
    }
    public function invoice(Request $request)
    {
        $client = Client::findOrFail($request->id);
        return \response()->json(['fees'=>$client->fees()->where('balance','>',0)->get(),'client'=>$client]);
    }
}
