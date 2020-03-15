<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \response()->json(collect(Customer::paginate(15)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->partner_number = $request->partner_number;
        $customer->id_number = $request->id_number;
        $customer->address_1 = $request->address_1;
        $customer->address_2 = $request->address_2;
        $customer->contact_number = $request->contact_number;
        $customer->email = $request->email;
        if($customer->save())
        {
            return \response()->json(['status'=>'ok']);        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Customer::findOrFail($id));
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
        $customer = Customer::findOrFail($request->id);
        $customer->name = $request->name;
        $customer->partner_number = $request->partner_number;
        $customer->id_number = $request->id_number;
        $customer->address_1 = $request->address_1;
        $customer->address_2 = $request->address_2;
        $customer->contact_number = $request->contact_number;
        $customer->email = $request->email;
        $customer->balance = 0;
        if($customer->save())
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
        if(Customer::findOrFail($id)->delete())
        {
            return \response()->json(['status'=>'ok']);        
        }
    }

    public function search(Request $request)
    {
        // dd($request);
        if ($request->search=='') {
            return response()->json(['response'=>'please type in the search box']);
        }
    
        $customers=DB::table('customers')->where('name','LIKE','%'.$request->search."%")
                                    ->orWhere('partner_number', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('id_number', 'LIKE', '%'.$request->search.'%')
                                    ->paginate(5);
        return \response()->json($customers);
    }
}
