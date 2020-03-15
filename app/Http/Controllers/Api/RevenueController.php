<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Client;
use App\Models\Api\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \response()->json(collect(Revenue::latest()->paginate(15)));
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
            // save a receipt from client
            DB::table('revenues')->insert([
                'amount'=>\Request::input('amount'),
                'client_id'=>\Request::input('client_id'),
                'fee_id'=>\Request::input('fee_id'),
                'description'=>\Request::input('description'),
                'created_at'=>\now(),
                'updated_at'=>\now()
                ]);
            // subtract balance in client_fee table record 
            $balance = DB::table('client_fee')->where([['client_id',\Request::input('client_id')],['fee_id',\Request::input('fee_id')]])->get();
            $plucked = $balance->pluck('balance');
            $client_fee_balance = $plucked[0] - \Request::input('amount');
            DB::table('client_fee')->where([['client_id',\Request::input('client_id')],['fee_id',\Request::input('fee_id')]])->update(['balance'=>$client_fee_balance]);
            //  calculate new balance and subtract balance in client table
            $client = Client::findOrFail(\Request::input('client_id'));
            $newBalance = $client->balance - \Request::input('amount');
            DB::table('clients')->where('id',\Request::input('client_id'))->update(['balance'=>$newBalance]);
        });
        $client = Client::findOrFail(\Request::input('client_id'));
        return \response()->json(['fees'=>$client->fees()->where('balance','>',0)->get(),'client'=>$client]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Revenue::findOrFail($id));
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
        $revenue = Revenue::findOrFail($id);
        $revenue->client_id = $request->client_id;
        $revenue->fee_id = $request->fee_id;
        $revenue->amount = $request->amount;
        if($revenue->save())
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
        if(Revenue::findOrFail($id)->delete())
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
    public function rollback(Request $request)
    {
        DB::transaction(function($request){
            // add back balance in client_fee table record 
            $receipt = Revenue::findOrFail(\Request::input('id'));
            $balance = DB::table('client_fee')->where([['client_id',\Request::input('client_id')],['fee_id',\Request::input('fee_id')]])->get();
            $plucked = $balance->pluck('balance');
            $client_fee_balance = $plucked[0] + $receipt->amount;
            DB::table('client_fee')->where([['client_id',\Request::input('client_id')],['fee_id',\Request::input('fee_id')]])->update(['balance'=>$client_fee_balance]);
            //  calculate new balance and add back balance in client table
            $client = Client::findOrFail(\Request::input('client_id'));
            $newBalance = $client->balance + $receipt->amount;
            DB::table('clients')->where('id',\Request::input('client_id'))->update(['balance'=>$newBalance]);
            // destory a receipt from client
            $receipt->delete();
        });
        $client = Client::findOrFail(\Request::input('client_id'));
        return \response()->json(['fees'=>$client->fees,'client'=>$client]);
    }

    public function history(Request $request)
    {
        $history = Revenue::where('client_id',$request->client_id)->latest()->take(5)->get();
        return \response()->json($history);
    }

    public function account(Request $request)
    {
        $account = Revenue::where('client_id',$request->id)->latest()->get();
        return \response()->json($account);
    }
}
