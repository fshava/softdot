<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->partner_number = $request->partner_number;
        $supplier->id_number = $request->id_number;
        $supplier->address_1 = $request->address_1;
        $supplier->address_2 = $request->address_2;
        $supplier->contact_number = $request->contact_number;
        $supplier->email = $request->email;
        if($supplier->save())
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $supplier = Supplier::findOrFail($request->id);
        $supplier->name = $request->name;
        $supplier->partner_number = $request->partner_number;
        $supplier->id_number = $request->id_number;
        $supplier->address_1 = $request->address_1;
        $supplier->address_2 = $request->address_2;
        $supplier->contact_number = $request->contact_number;
        $supplier->email = $request->email;
        if($supplier->save())
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
        //
    }
    public function search(Request $request)
    {
        // dd($request);
        if ($request->search=='') {
            return response()->json(['response'=>'please type in the search box']);
        }
    
        $suppliers=DB::table('suppliers')->where('name','LIKE','%'.$request->search."%")
                                    ->orWhere('partner_number', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('id_number', 'LIKE', '%'.$request->search.'%')
                                    ->paginate(5);
        return \response()->json($suppliers);
    }
}
