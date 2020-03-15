<?php

use App\Exports\PupilsExport;
use App\Exports\ClientExport;
use App\Exports\DebtorExport;
use App\Exports\OtherDebtorExport;
use App\Exports\CreditorExport;
use App\Exports\IncomeExport;
use App\Exports\ExpenditureExport;
use App\Exports\IncomeAndExpenditureExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('/download', function(){
//     return Excel::download(new PupilsExport, 'enrolment.xlsx');
// });
Route::get('/excell/clients', function(){
    return Excel::download(new ClientExport, 'enrolment.xlsx');
});
Route::get('/excell/debtors', function(){
    return Excel::download(new DebtorExport, 'debtors.xlsx');
});
Route::get('/excell/otherDebtors', function(){
    return Excel::download(new OtherDebtorExport, 'other_debtors.xlsx');
});
Route::get('/excell/creditors', function(){
    return Excel::download(new CreditorExport, 'creditors.xlsx');
});
Route::get('/excell/income', function(){
    return Excel::download(new IncomeExport, 'income.xlsx');
});
Route::get('/excell/expenditure', function(){
    return Excel::download(new ExpenditureExport, 'expenditure.xlsx');
});
Route::get('/excell/incAndExp', function(){
    return Excel::download(new IncomeAndExpenditureExport, 'income_and_expenditure.xlsx');
});
// //pupils routes
// Route::get('/pupil/{id}', 'Receipts\PupilsController@show');
// Route::post('/pupils', 'Receipts\PupilsController@search');
// Route::post('/pupil', 'Receipts\PupilsController@store');
// Route::put('/pupil', 'Receipts\PupilsController@update');
// Route::delete('/pupil', 'Receipts\PupilsController@destroy');
// Route::get('/pupils', 'Receipts\PupilsController@count');

// //products routes
// Route::get('/product/{id}', 'Receipts\ProductsController@show');
// Route::post('/products', 'Receipts\ProductsController@search');
// Route::post('/product', 'Receipts\ProductsController@store');
// Route::put('/product/{id}', 'Receipts\ProductsController@update');
// Route::delete('/product/{id}', 'Receipts\ProductsController@destroy');
// Route::get('/products', 'Receipts\ProductsController@products');

// //invoicing routes
// Route::post('/addToInvoice', 'Receipts\InvoicesController@addToInvoice');//add products to invoice with id $id
// Route::post('/removeFromInvoice/{id}', 'Receipts\InvoicesController@removeFromInvoice');//remove product from invoice with id $id
// Route::post('/addProductToAllInvoices', 'Receipts\InvoicesController@addProductToAllInvoices');//remove product from invoice with id $id
// Route::post('/addManyProductsToManyInvoices', 'Receipts\InvoicesController@addManyProductsToManyInvoices');//remove product from invoice with id $id
// Route::post('/getInvoice', 'Receipts\InvoicesController@getInvoice');//get pupil invoice profile

// // receipts routes
// Route::post('receipt', 'Receipts\ReceiptsController@receipt');  //receipt a pupil payment
// Route::get('excel', 'Receipts\PupilsController@export');  //receipt a pupil payment
// Route::get('exportTotalEnrolment', 'Receipts\PupilsController@exportTotalEnrolment');  //receipt a pupil payment
// Route::get('exportPupils', 'Receipts\ReportsController@exportPupils');  //receipt a pupil payment
// Route::get('exportReceipts', 'Receipts\ReportsController@exportReceipts');  //receipt a pupil payment

Route::get('enrolment/total','Receipts\ReportsController@totalEnrolment');

/* 
-----------------------------------------------------------------------------------------------------------------
new API routes for App/Http/Controllers/Api and App/Models/Api
-----------------------------------------------------------------------------------------------------------------
*/
// restore a specific client into the storage
Route::post('clients/restore','Api\ClientController@restore');
Route::post('clients/addBalance','Api\ClientController@addBalance');
// search clients
Route::post('clients/search','Api\ClientController@search');
// search suppliers
Route::post('suppliers/search','Api\SupplierController@search');
// enrolment statistics
Route::get('clients/count','Api\ClientController@count');
// attach a fee to one client
Route::post('invoices/attachFeeToOne','Api\ClientFeeController@attachFeeToOne');
// attach a fee to all clients
Route::post('invoices/attachFeeToAll','Api\ClientFeeController@attachFeeToAll');
// detach a fee from all clients
Route::post('invoices/detachFeeFromAll','Api\ClientFeeController@detachFeeFromAll');
// detach a fee from one client
Route::post('invoices/detachFeeFromOne','Api\ClientFeeController@detachFeeFromOne');
// get invoice for a client
Route::post('invoices/invoice','Api\ClientFeeController@invoice');
// attach a fee to one customer
Route::post('invoices/customers/attachFeeToOne','Api\CustomerFeeController@attachFeeToOne');
// attach a fee to all customer
Route::post('invoices/customers/attachFeeToAll','Api\CustomerFeeController@attachFeeToAll');
// detach a fee from all customer
Route::post('invoices/customers/detachFeeFromAll','Api\CustomerFeeController@detachFeeFromAll');
// detach a fee from one customer
Route::post('invoices/customers/detachFeeFromOne','Api\CustomerFeeController@detachFeeFromOne');
// get invoice for a /customers
Route::post('invoices/customers/invoice','Api\CustomerFeeController@invoice');
// get history of payments by a client
Route::post('revenues/history','Api\RevenueController@history');
// rollback a payment by a client
Route::post('revenues/rollback','Api\RevenueController@rollback');
// get an account history 
Route::post('revenues/account','Api\RevenueController@account');
// get an account history 
Route::post('takings/account','Api\TakingController@account');
// search customers
Route::post('customers/search','Api\CustomerController@search');
// get history of takings by a customer
Route::post('takings/history','Api\TakingController@history');
// rollback a payment by a customer
Route::post('takings/rollback','Api\TakingController@rollback');
// search categories
Route::post('categories/search','Api\CategoryController@search');
// search fees
Route::post('fees/search','Api\FeeController@search');
Route::post('products/list','Api\ProductController@list');
Route::post('products/pay','Api\ProductController@pay');


Route::apiResource('clients','Api\ClientController');
Route::apiResource('categories','Api\CategoryController');
Route::apiResource('fees','Api\FeeController');
Route::apiResource('revenues','Api\RevenueController');
Route::apiResource('takings','Api\TakingController');
Route::apiResource('customers','Api\CustomerController'); 
Route::apiResource('suppliers','Api\SupplierController'); 
Route::apiResource('products','Api\ProductController'); 
// go on and create the ProductController\List and \Pay methods