<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Fee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \response()->json(collect(Fee::latest()->paginate(15)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fees = new Fee;
        $fees->category_id = $request->category_id;
        $fees->year = $request->year;
        $fees->description = $request->description;
        $fees->term = $request->term;
        $fees->amount = $request->amount;
        if($fees->save())
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
        return response()->json(Fee::findOrFail($id));
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
        $fees = Fee::findOrFail($request->id);
        $fees->category_id = $request->category_id;
        $fees->year = $request->year;
        $fees->description = $request->description;
        $fees->term = $request->term;
        $fees->amount = $request->amount;
        if($fees->save())
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
    public function destroy(Request $request, $id)
    {
        if(Fee::findOrFail($request->id)->delete())
        {
            return \response()->json(['status'=>'ok']);        
        }
    }
      /**
     * Search a fee 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // dd($request);
        if ($request->search=='') {
            return response()->json(['response'=>'please type in the search box']);
        }
    
        $fees=DB::table('fees')->where('description','LIKE','%'.$request->search."%")
                                    ->orWhere('year', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('term', 'LIKE', '%'.$request->search.'%')
                                    ->paginate(5);
        return \response()->json($fees);
    }
}
