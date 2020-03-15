<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\Product;
use App\Models\Api\Supplier;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        if ($request->amount == 0) {
            DB::transaction(function(){
                $product = new Product;
                $product->category_id = \Request::input('category_id');
                $product->supplier_id = \Request::input('supplier_id');
                $product->description = \Request::input('description');
                $product->quantity = \Request::input('quantity');
                $product->unit_price = \Request::input('unit_price');
                $product->total_price = \Request::input('total_price');
                $product->balance = \Request::input('balance');
                $product->save();
                $supplier = Supplier::findOrFail(\Request::input('supplier_id'));
                $supplier->balance = $supplier->balance + \Request::input('balance');
                $supplier->save();
            });
            return \response()->json(['status' => 'ok']);
        } else {
            DB::transaction(function () {
                $balance = \Request::input('total_price') - \Request::input('amount');
                $id = DB::table('products')->insertGetId([
                    'category_id' => \Request::input('category_id'),
                    'supplier_id' => \Request::input('supplier_id'),
                    'description' => \Request::input('description'),
                    'quantity' => \Request::input('quantity'),
                    'unit_price' => \Request::input('unit_price'),
                    'total_price' => \Request::input('total_price'),
                    'created_at' => \now(),
                    'updated_at' => \now(),
                    'balance' => $balance
                ]);
                // add balance to supplier details
                $supplier = Supplier::findOrFail(\Request::input('supplier_id'));
                $supplier->balance = $supplier->balance + $balance;
                $supplier->save();
                DB::table('payments')->insert([
                    'product_id' => $id,
                    'category_id' => \Request::input('category_id'),
                    'supplier_id' => \Request::input('supplier_id'),
                    'created_at' => \now(),
                    'updated_at' => \now(),
                    'amount' => \Request::input('amount')
                ]);
            });
            return \response()->json(['status' => 'ok']);
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
        $product = Product::findOrFail($request->id);
        if ($product->total_price != $product->balance) {
            return \response()->json(['status' => 'you are not allowed to manipulate an invoice']);
        }
        $product->category_id = $request->category_id;
        $product->supplier_id = $request->supplier_id;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->unit_price = $request->unit_price;
        $product->total_price = $request->total_price;
        $product->balance = $request->total_price;
        if ($product->save()) {
                return \response()->json(['status' => 'ok']);
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

    public function list(Request $request)
    {
        return \response()->json(['products' => Product::where([['supplier_id',$request->supplier_id],['balance','>',0]])->get()]);
    }
    public function pay(Request $request)
    {
        DB::transaction(function () {
            // insert a payment
            DB::table('payments')->insert([
                'product_id' => \Request::input('product_id'),
                'category_id' => \Request::input('category_id'),
                'supplier_id' => \Request::input('supplier_id'),
                'amount' => \Request::input('amount'),
                'created_at' => \now(),
                'updated_at' => \now(),
            ]);
            // update balance of a product
             $product = Product::findOrFail(\Request::input('product_id'));
             $product->balance = $product->balance - \Request::input('amount');
             $product->save();
            // update balance of a supplier
            $supplier = Supplier::findOrFail(\Request::input('supplier_id'));
            $supplier->balance = $supplier->balance - \Request::input('amount');
            $supplier->save();
        });
        return \response()->json(['products' => Product::where([['supplier_id',$request->supplier_id],['balance','>',0]])->get()]);
    }
}
        