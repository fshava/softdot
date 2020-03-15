<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Client;
use App\Models\Api\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \response()->json(collect(Client::paginate(15)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client;
        $client->name = $request->name;
        $client->surname = $request->surname;
        $client->class = $request->class;
        $client->grade = $request->grade;
        $client->sex = $request->sex;
        $client->dob = $request->dob;
        if($client->save())
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
        return response()->json(Client::findOrFail($id));
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
        $client = Client::findOrFail($request->id);
        $client->name = $request->name;
        $client->surname = $request->surname;
        $client->class = $request->class;
        $client->grade = $request->grade;
        $client->sex = $request->sex;
        $client->sex = $request->sex;
        $client->dob = $request->dob;
        if($client->save())
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
        $client = Client::findOrFail($request->id);
        if($client->delete())
        {      
            return \response()->json(['name'=>$client->name]);        
        }
    }
    /**
     * Restore the specified resource to storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if(Client::withTrashed()->where('id',$request->id)->restore())
        {
            $client = Client::findOrFail($request->id);
            return \response()->json(['name'=>$client->name]);        
        }
    }

    public function addBalance(Request $request)
    {
        $client = Client::findOrFail($request->id);
        if ($client->check) {
            return \response()->json(['status'=>'fail']);
        }
        $client->balance = $client->balance + $request->balance;
        $client->check = true;
        $client->save();
        $fee = Fee::findOrFail(1);
        $id = 1;
        $client->fees()->attach($id,['balance'=>$request->balance,'created_at'=>\now(),'updated_at'=>\now()]);
        return \response()->json(['status'=>'success']);
    }
    /**
     * Search a client 
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
    
        $clients=DB::table('clients')->where('name','LIKE','%'.$request->search."%")
                                    ->orWhere('surname', 'LIKE', '%'.$request->search.'%')
                                    ->paginate(5);
        return \response()->json($clients);
    }

    public function count()
    {
        $clients = Client::count();
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
            'total'=>$clients,
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
}
