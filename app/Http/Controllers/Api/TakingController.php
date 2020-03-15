<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Customer;
use App\Models\Api\Taking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TakingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \response()->json(collect(Taking::latest()->paginate(15)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function($request){
            // save a receipt from customer
            DB::table('takings')->insert([
                'amount'=>\Request::input('amount'),
                'customer_id'=>\Request::input('customer_id'),
                'fee_id'=>\Request::input('fee_id'),
                'description'=>\Request::input('description'),
                'created_at'=>\now(),
                'updated_at'=>\now()
                ]);
            // subtract balance in customer_fee table record 
            $balance = DB::table('customer_fee')->where([['customer_id',\Request::input('customer_id')],['fee_id',\Request::input('fee_id')]])->get();
            $plucked = $balance->pluck('balance');
            $customer_fee_balance = $plucked[0] - \Request::input('amount');
            DB::table('customer_fee')->where([['customer_id',\Request::input('customer_id')],['fee_id',\Request::input('fee_id')]])->update(['balance'=>$customer_fee_balance]);
            //  calculate new balance and subtract balance in customer table
            $customer = Customer::findOrFail(\Request::input('customer_id'));
            $newBalance = $customer->balance - \Request::input('amount');
            DB::table('customers')->where('id',\Request::input('customer_id'))->update(['balance'=>$newBalance]);
        });
        $customer = Customer::findOrFail(\Request::input('customer_id'));
        return \response()->json(['fees'=>$customer->fees()->where('balance','>',0)->get(),'customer'=>$customer]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Taking::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $taking = Taking::findOrFail($id);
        $taking->customer_id = $request->customer_id;
        $taking->fee_id = $request->fee_id;
        $taking->amount = $request->amount;
        if($taking->save())
        {
            return \response()->json(['status'=>'ok']);        
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Taking::findOrFail($id)->delete())
        {
            return \response()->json(['status'=>'ok']);        
        }
    }

    public function rollback(Request $request)
    {
        DB::transaction(function($request){
            // add back balance in customer_fee table record 
            $receipt = Taking::findOrFail(\Request::input('id'));
            $balance = DB::table('customer_fee')->where([['customer_id',\Request::input('customer_id')],['fee_id',\Request::input('fee_id')]])->get();
            $plucked = $balance->pluck('balance');
            $customer_fee_balance = $plucked[0] + $receipt->amount;
            DB::table('customer_fee')->where([['customer_id',\Request::input('customer_id')],['fee_id',\Request::input('fee_id')]])->update(['balance'=>$customer_fee_balance]);
            //  calculate new balance and add back balance in client table
            $client = Customer::findOrFail(\Request::input('customer_id'));
            $newBalance = $client->balance + $receipt->amount;
            DB::table('customers')->where('id',\Request::input('customer_id'))->update(['balance'=>$newBalance]);
            // destory a receipt from client
            $receipt->delete();
        });
        $client = Customer::findOrFail(\Request::input('customer_id'));
        return \response()->json(['fees'=>$client->fees,'client'=>$client]);
    }

    public function history(Request $request)
    {
        $history = Taking::where('customer_id',$request->customer_id)->latest()->take(5)->get();
        return \response()->json($history);
    }

    public function account(Request $request)
    {
        $account = Taking::where('customer_id',$request->id)->latest()->get();
        return \response()->json($account);
    }
}
