<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\GradeImport;
use App\Models\Curriculums;
use App\Models\settings;
use App\Models\Gradesubmissions;
use App\Models\Grades;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;
use App\Models\Logs;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;
class GradeController extends Controller
{
    

    public function index()
    {
        $subjects = Curriculums::where('status','!=','archived')->get();
        $settings = settings::where('id','=',1)->get();
       
        $Gradesubmissions=Gradesubmissions::all();
        return view('addgradesubmission',['subjects'=>$subjects,'settings'=>$settings,'Gradesubmissions'=>$Gradesubmissions]);
    }
    public function addgradesubmit(Request $request)
    {
        try{
           
            
            $newProduct=Gradesubmissions::create(['gradeName'=>$request->gName,
            'section'=>$request->section,
            'subject'=>$request->coursecode,  
            'semester'=>$request->semester,
            'year'=>$request->year,
            'tID'=>$request->tID,
            'status'=>''
        
        ]);
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' added grade named '.$request->gName.' in the system.'
    ]);
    return back()->with('success', 'Added Successfully!');
} catch (Exception $e) {
              return response()->json(['error' => 'Error updating status'], 500);
        }
    
    }

    public function import(Request $request)
    {

      
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $id = $request->ids;
        $user = Auth::user();
        $idsss = Auth::id();
    Logs::create(['userid'=>$idsss,
    'remarks'=> 'User ID '.$idsss.' import curriculum using excel with program id ( '.$request->ids.') in the system.'
]);
        Excel::import(new GradeImport($id), $request->file('file'));

        return back()->with('success', 'Excel file imported successfully.');

    }




    public function gradesview($id)
    {
        $gradesStudent=Grades::where('gsID','=',$id)->get();
        $gradessubmissions=Gradesubmissions::where('id','=',$id)->get();
        $settings = settings::where('id','=',1)->get();
        $subjects = Curriculums::where('status','!=','archived')->get();
        return view('viewgradesubmission',['subjects'=>$subjects,'settings'=>$settings,'gradesStudent'=>$gradesStudent,'gradessubmissions'=>$gradessubmissions]);
   
    }
    public function updategrades(Gradesubmissions $Gradesubmissions, Request $request)
    {
        try{
            $Gradesubmissions->update(['gradeName'=>$request->gName,
            'section'=>$request->section,'subject'=>$request->coursecode]);
        
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' update the submittedd grade named ( '.$Gradesubmissions->gName.') in the system.'
    ]);
    
            return back()->with('success', 'Grade details edit Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
        
    }

}
