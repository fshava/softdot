<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Resources\Product as ProductResource;  //for making this controller an API
use App\Http\Requests; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receipts\Product;                           //the model product
use DB;    

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function products()
    {
        $products = Product::orderBy('category','asc')->get();
        return response()->json(['items'=>$products]);
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;
        // get data from the request 
        $product->year = $request->year;
        $product->term = $request->term;
        $product->category = $request->category;
        $product->description = $request->description;
        $product->amount = $request->amount;
        if($product->save())
        {
            return new ProductResource($product);        
        }
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
        $product = Product::findOrFail($id);
        $product->year = $request->year;
        $product->term = $request->term;
        $product->category = $request->category;
        $product->description = $request->description;
        $product->amount = $request->amount;
        if($product->save())
        {
            return new ProductResource($product);        
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
        $product = Product::findOrFail($id);
        $product->delete();
        return \response()->json(['message'=>'successfully deleted']); 
    }
    public function search(Request $request)
    {
        // dd($request);
        if ($request->search=='') {
            return response()->json(['response'=>'please provide some data in the search box']);
        }
    
        $products=DB::table('products')->where('year','LIKE','%'.$request->search."%")
                                    ->orWhere('term', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('category', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('description', 'LIKE', '%'.$request->search.'%')
                                    ->paginate(10);
        return ProductResource::collection($products);
    }
}
