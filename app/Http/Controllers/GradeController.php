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

use App\Models\Programs;
use Maatwebsite\Excel\Facades\Excel;
class GradeController extends Controller
{
    

    public function index()
    {
        $subjects = Curriculums::where('status','=',NULL)->get();
        $programs = Programs::get();
        $settings = settings::where('id','=',1)->get();
       
        $Gradesubmissions=Gradesubmissions::all();
        return view('addgradesubmission',['subjects'=>$subjects,'settings'=>$settings,'Gradesubmissions'=>$Gradesubmissions,'programs'=>$programs]);
    }
    public function addgradesubmit(Request $request)
    {
        try{
           
            
            $newProduct=Gradesubmissions::create(['gradeName'=>$request->gName,
            'section'=>$request->section,
            'subject'=>$request->coursecode,  
            'coursecode'=>'',  
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
        $idsss = Auth::id();
    
        Logs::create([
            'userid' => $idsss,
            'remarks' => 'User ID ' . $idsss . ' imported grades using Excel with program ID ( ' . $id . ') in the system.'
        ]);
        try {
            Excel::import(new GradeImport($id,$request->semester,$request->year,$request->section,$request->tID,$request->subject), $request->file('file'));
    
        // Retrieve newly imported records
        $grades = Grades::where('tID', $idsss)->latest()->get();
        return redirect()->back()->with('success', 'Grades imported successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
   
    }



    public function gradesview($id)
    {
        $gradesStudent=Grades::where('gsID','=',$id)->get();
        $gradessubmissions=Gradesubmissions::where('id','=',$id)->get();
        $settings = settings::where('id','=',1)->get();
        $pid = Gradesubmissions::where('id', $id)->value('subject');
      
        $subjects = Curriculums::where('pID', $pid)
            ->whereNull('status') // better syntax for NULL
            ->get();
        return view('viewgradesubmission',['subjects'=>$subjects,'settings'=>$settings,'gradesStudent'=>$gradesStudent,'gradessubmissions'=>$gradessubmissions]);
   
    }
    public function updategrades(Gradesubmissions $Gradesubmissions, Request $request)
    {

        try{  
            $Gradesubmissions->update(['gradeName'=>$request->gName,'section'=>$request->section,'coursecode'=>$request->coursecode]);
        
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' update the subasdasdmittedd grade named ( '.$Gradesubmissions->coursecode.') in the system.'
    ]);
    Grades::where('gsID',$request->id)->update(['subject'=>$request->coursecode]);
            return back()->with('success', 'Grade details edit Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
        
    }
    public function editgrades(Request $request,$id)
    {
        try
        {

            if($request->grade==5.0)
            {
                $asd="Failed";
            }else{
                $asd="Passed";
            }
            Grades::where('id', $id)->update([
                'grade'=>$request->grade,
                'remark'=>$asd
            ]);
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' update the submitted grade in the system.'
    ]);
         return back()->with('success', 'Grade details edit Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
    }

}
