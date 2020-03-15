<?php

use App\Exports\EnrolExport;
use App\Exports\PupilsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\LanguageController;

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__.'/frontend/');
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__.'/backend/');
});
Route::get('reports', function(){
    return view('frontend.app.reports');
})->name('reports');
Route::get('/download', function(){
    return Excel::download(new EnrolExport, 'enrolment.xlsx');
});
Route::get('/download1', function(){
    return Excel::download(new PupilsExport, 'enrolment.xlsx');
});
//reports routes
Route::get('singleDebtor',array('as'=>'singleDebtor','uses'=>'Receipts\ReportsController@singleDebtor'));
Route::get('debtorsForOneItem',array('as'=>'debtorsForOneItem','uses'=>'Receipts\ReportsController@debtorsForOneItem'));
Route::get('debtorsForOneItemInGrade',array('as'=>'debtorsForOneItemInGrade','uses'=>'Receipts\ReportsController@debtorsForOneItemInGrade'));
Route::get('enrolment/total','Receipts\ReportsController@totalEnrolment')->name('totalEnrolment');
Route::get('enrolment/girls','Receipts\ReportsController@totalGirls')->name('totalGirls');
Route::get('enrolment/boys','Receipts\ReportsController@totalBoys')->name('totalBoys');
Route::get('totalSexInGrade',array('as'=>'totalSexInGrade','uses'=>'Receipts\ReportsController@totalSexInGrade'));
Route::get('totalSexInClass',array('as'=>'totalSexInClass','uses'=>'Receipts\ReportsController@totalSexInClass'));
Route::get('balances',array('as'=>'balances','uses'=>'Receipts\ReportsController@balances'));
Route::post('import','Receipts\PupilsController@import')->name('import');