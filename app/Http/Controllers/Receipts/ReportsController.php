<?php

namespace App\Http\Controllers\Receipts;

use DB;
use PDF;
use Illuminate\Http\Request;
// use App\Models\Receipts\Invoice;
use App\Models\Receipts\Invoice;
use App\Models\Receipts\Product;
use App\Models\Receipts\Pupil;  
use App\Models\Receipts\Receipt;
use App\Http\Controllers\Controller;
use App\Models\Api\Client;
class ReportsController extends Controller
{
    // generate an invoice for a single pupil and download it as pdf. the request should contain id of the pupil
    public function singleDebtor(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        $pupil = Pupil::findOrFail($request->id);
        $fee=$invoice->products()->where('balance','>',0)->orWhere('balance','<',0)->get();
        $total=$invoice->products()->sum('balance');
        $date = now();
        view()->share('fee',$fee);
        view()->share('total',$total);
        view()->share('pupil',$pupil);
        view()->share('date',$date);
        $pdf = PDF::loadView('reports.singleDebtor');
        return $pdf->download($pupil->name.'_'.$pupil->surname.'_invoice.pdf');
    }

    public function debtorsForOneItem(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product_name = \strtoupper($product->description);
        $invoices = $product->invoices()->where('balance','>',0)->get();
        $date = now();
        $debtorsForOneItem = [];
        foreach ($invoices as $invoice) {
            $pupil = Pupil::findOrFail($invoice->id);
            $name = $pupil->name;
            $surname = $pupil->surname;
            $class = $pupil->class;
            $balance = $invoice->pivot->balance;
            $record = [$name,$surname,$class,$balance];
            array_push($debtorsForOneItem,$record);
        }
        // dd($debtorsForOneItem);
        view()->share('debtorsForOneItem',$debtorsForOneItem);
        view()->share('product_name',$product_name);
        view()->share('date',$date);
        $pdf = PDF::loadView('reports.debtorsForOneItem');
        return $pdf->download($product_name.'_DEBTORS_LIST'.$date.'_.pdf');
    }
    public function debtorsForOneItemBetween(Request $request)
    {

    }
    public function totalEnrolment()
    {
        // $pupils = [];
        // foreach (Client::all() as $pupil) {
        //     array_push($pupils,$pupil);
        // }
        // Client::chunk(100, function ($array){
        //         foreach ($array as $value) {
                
        //             }
        //         });
        //         $pupils = Pupil::where('name','like','%e%')->get();
        // dd($pupils);
        $pupils = Client::all();
        $total = Client::count();
        $date = now();
        view()->share('date',$date);
        view()->share('pupils',$pupils);
        view()->share('total',$total);
        $pdf = PDF::loadView('reports.totalEnrolment');
        return $pdf->download('Total_Enrolment.pdf');
    }
    public function totalGirls(Request $request)
    {
        $pupils = Client::where('sex','=','G')->get();
        $total = $pupils->count();
        $date = now();
        view()->share('date',$date);
        view()->share('pupils',$pupils);
        view()->share('total',$total);
        $pdf = PDF::loadView('reports.totalGirls');
        return $pdf->download('Total_Girls_Enrolment'.$date.'.pdf');
    }
    public function totalBoys(Request $request)
    {
        $pupils = Client::where('sex','=','B')->get();
        $total = $pupils->count();
        $date = now();
        view()->share('date',$date);
        view()->share('pupils',$pupils);
        view()->share('total',$total);
        $pdf = PDF::loadView('reports.totalBoys');
        return $pdf->download('Total_Boys_Enrolment'.$date.'.pdf');
        
    }
    public function totalSexInGrade(Request $request)
    {
        $pupils = Pupil::where('sex','=',$request->sex)->where('grade','=',$request->grade)->get();
        $sex = '';
        $grade = \strtoupper($request->grade);
        if ($request->sex == 'B') {
            $sex = 'BOYS';
        } else {
            $sex = 'GIRLS';
        }
        $total = $pupils->count();
        $date = now();
        view()->share('date',$date);
        view()->share('sex',$sex);
        view()->share('grade',$grade);
        view()->share('pupils',$pupils);
        view()->share('total',$total);
        $pdf = PDF::loadView('reports.totalSexInGrade');
        return $pdf->download('Total_'.$sex.'_Enrolment.pdf');

    }
    public function totalSexInClass(Request $request)
    {
        $pupils = Pupil::where('sex','=',$request->sex)->where('class','=',$request->class)->get();
        $sex = '';
        $class = \strtoupper($request->class);
        if ($request->sex == 'B') {
            $sex = 'BOYS';
        } else {
            $sex = 'GIRLS';
        }
        $total = $pupils->count();
        $date = now();
        view()->share('date',$date);
        view()->share('sex',$sex);
        view()->share('class',$class);
        view()->share('pupils',$pupils);
        view()->share('total',$total);
        $pdf = PDF::loadView('reports.totalSexInClass');
        return $pdf->download('Total_'.$sex.'_In'.$class.'pdf');
    }
    public function balances(Request $request)
    {
        // pupils and their balances in a record
        $records = [];
        // get all invoices 
        $invoices = Invoice::all();
        // for each invoice
        foreach ($invoices as $invoice) {
            // get balance 
            $balance = $invoice->products()->sum('balance');
            // get pupil
            $pupil = Pupil::findOrFail($invoice->id);
            // one record
            $record = ["name"=>$pupil->name,"surname"=>$pupil->surname,"grade"=>$pupil->grade,"class"=>$pupil->class,"balance"=>$balance];
            // push to records array
            array_push($records,$record);
        }
        // dump and die $records
        dd($records);
    }
    public function expenditure(Request $request)
    {

    }
    public function receipts(Request $request)
    {

    }
    public function payments(Request $request)
    {

    }
    public function incomeAndExpenditure(Request $request)
    {

    }
    public function receiptsAndPayments(Request $request)
    {

    }
    public function deposits(Request $request)
    {

    }
}
