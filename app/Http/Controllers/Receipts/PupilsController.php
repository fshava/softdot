<?php

namespace App\Http\Controllers\Receipts;

use DB;  
use App\Models\Api\Client;
use App\Exports\EnrolmentExport;
use App\Http\Controllers\Controller;            //base controller
use App\Http\Requests;                          //for api requests
use Illuminate\Http\Request;                    //for laravel requests
use App\Models\Receipts\Pupil;                           //the model pupil
use App\Models\Receipts\Invoice;                           //the model pupil
use PDF;                                       //for making database queries
use App\Http\Resources\Pupil as PupilResource;  //for making this controller an API
use Maatwebsite\Excel\Facades\Excel;            //for bringing in maatwebsite functionality
use App\Exports\PupilsExport;                   //for mass importing pupils via excell sheets
use App\Imports\PupilsImport;                   //for mass importing pupils via excell sheets


class PupilsController extends Controller
{
    public function show($id)
    {
        $pupil = Pupil::findOrFail($id);
        return new PupilResource($pupil);
    }
    public function store(Request $request)
    {
        $request = $request;
        DB::transaction(function($request){
            //first create a model for the registered pupil
            $id = DB::table('pupils')->insertGetId([
                'name'=>\Request::input('name'),
                'surname'=>\Request::input('surname'),
                'grade'=>\Request::input('grade'),
                'class'=>\Request::input('class'),
                'sex'=>\Request::input('sex'),
                'dob'=>\Request::input('dob')
                ]);
             //then create an invoice for that pupil
             DB::table('invoices')->insert(['pupil_id'=>$id]);
            });
            return \response()->json(['status'=>'ok']);
    }

    public function count()
    {
        $pupils = Client::count();
        $boys = Client::where('sex','B')->count();
        $girls = Client::where('sex','G')->count();
        $grade1 = Client::where('grade','1')->count();
        $grade2 = Client::where('grade','2')->count();
        $grade3 = Client::where('grade','3')->count();
        $grade4 = Client::where('grade','4')->count();
        $grade5 = Client::where('grade','5')->count();
        $grade6 = Client::where('grade','6')->count();
        $grade7 = Client::where('grade','7')->count();
        $gradeSP = Client::where('grade','SP')->count();
        $gradeECDA = Client::where('grade','ECD A')->count();
        $gradeECDB = Client::where('grade','ECD B')->count();
        $grade1boys = Client::where([['grade','1'],['sex','B']])->count();
        $grade1girls = Client::where([['grade','1'],['sex','G']])->count();
        $grade2boys = Client::where([['grade','2'],['sex','B']])->count();
        $grade2girls = Client::where([['grade','2'],['sex','G']])->count();
        $grade3boys = Client::where([['grade','3'],['sex','B']])->count();
        $grade3girls = Client::where([['grade','3'],['sex','G']])->count();
        $grade4boys = Client::where([['grade','4'],['sex','B']])->count();
        $grade4girls = Client::where([['grade','4'],['sex','G']])->count();
        $grade5boys = Client::where([['grade','5'],['sex','B']])->count();
        $grade5girls = Client::where([['grade','5'],['sex','G']])->count();
        $grade6boys = Client::where([['grade','6'],['sex','B']])->count();
        $grade6girls = Client::where([['grade','6'],['sex','G']])->count();
        $grade7boys = Client::where([['grade','7'],['sex','B']])->count();
        $grade7girls = Client::where([['grade','7'],['sex','G']])->count();
        $gradeSPboys = Client::where([['grade','SP'],['sex','B']])->count();
        $gradeSPgirls = Client::where([['grade','SP'],['sex','G']])->count();
        $gradeECDAboys = Client::where([['grade','ECD A'],['sex','B']])->count();
        $gradeECDAgirls = Client::where([['grade','ECD A'],['sex','G']])->count();
        $gradeECDBboys = Client::where([['grade','ECD B'],['sex','B']])->count();
        $gradeECDBgirls = Client::where([['grade','ECD B'],['sex','G']])->count();

        return response()->json([
            'total'=>$pupils,
            'total boys'=>$boys,
            'total girls'=>$girls,
            'total grade 1'=>$grade1,
            'total grade 2'=>$grade2,
            'total grade 3'=>$grade3,
            'total grade 4'=>$grade4,
            'total grade 5'=>$grade5,
            'total grade 6'=>$grade6,
            'total grade 7'=>$grade7,
            'total grade SPECIAL'=>$gradeSP,
            'total ECD A'=>$gradeECDA,
            'total ECD B'=>$gradeECDB,
            'total grade 1 boys'=>$grade1boys,
            'total grade 2 boys'=>$grade2boys,
            'total grade 3 boys'=>$grade3boys,
            'total grade 4 boys'=>$grade4boys,
            'total grade 5 boys'=>$grade5boys,
            'total grade 6 boys'=>$grade6boys,
            'total grade SP boys'=>$gradeSPboys,
            'total ECD A boys'=>$gradeECDAboys,
            'total ECD B boys'=>$gradeECDBboys,
            'total grade 1 girls'=>$grade1girls,
            'total grade 2 girls'=>$grade2girls,
            'total grade 3 girls'=>$grade3girls,
            'total grade 4 girls'=>$grade4girls,
            'total grade 5 girls'=>$grade5girls,
            'total grade 6 girls'=>$grade6girls,
            'total grade 7 girls'=>$grade7girls,
            'total grade SP girls'=>$gradeSPgirls,
            'total ECD A girls'=>$gradeECDAgirls,
            'total ECD B girls'=>$gradeECDBgirls,
        ]);
    }

    public function update(Request $request)
    {
        $pupil = Pupil::findOrFail($request->id);
        $pupil->name = $request->name;
        $pupil->surname = $request->surname;
        $pupil->grade = $request->grade;
        $pupil->class = $request->class;
        $pupil->sex = $request->sex;
        $pupil->dob = $request->dob;
        if($pupil->save())
        {
            return new PupilResource($pupil);        
        }
    }
    public function destroy(Request $request)
    {
        $pupil = Pupil::findOrFail($request->id);
        $pupil->delete();
        return new PupilResource($pupil); 
    }
    public function import() 
    {
        Excel::import(new PupilsImport, request()->file('pupils'));
        
        return redirect('/')->with('success', 'All good!');
    }
    public function search(Request $request)
    {
        // dd($request);
        if ($request->search=='') {
            return response()->json(['response'=>'please provide some data in the search box']);
        }
    
        $pupils=DB::table('pupils')->where('name','LIKE','%'.$request->search."%")
                                    ->orWhere('surname', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('grade', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('class', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('sex', 'LIKE', '%'.$request->search.'%')
                                    ->orWhere('dob', 'LIKE', '%'.$request->search.'%')
                                    ->paginate(15);
        return PupilResource::collection($pupils);
    }

    public function export()
    {
        return (new PupilsExport())->download('invoices.xlsx');
    }
    public function exportTotalEnrolment()
    {
        return (new EnrolmentExport())->download('enrolment.xlsx');
    }
    public function htmltopdfview(Request $request)
    {
        $pupils = Pupil::all();
        $invoice = Invoice::findOrFail(1);
        $fee=$invoice->products()->get();
        $total=$invoice->products()->sum('balance');
        view()->share('fee',$fee);
        view()->share('total',$total);
        if($request->has('download')){
            $pdf = PDF::loadView('htmltopdfview');
            return $pdf->download('htmltopdfview.pdf');
        }
        return view('htmltopdfview');
    }
}
