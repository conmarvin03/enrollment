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
use Illuminate\Support\Facades\DB;
use App\Models\Programs;
use Maatwebsite\Excel\Facades\Excel;
class GradeController extends Controller
{
    

    public function index()
    {
        $subjects = Curriculums::where('status','=',NULL)->get();
        $programs = Programs::get();
        $settings = settings::where('id','=',1)->get();
        $user = Auth::user();
        $idsss = Auth::id();
        
        $Gradesubmissions = Gradesubmissions::join('programs', 'gradesubmissions.gsID', '=', 'programs.id')
    ->where('gradesubmissions.tID', $idsss)
    ->orderBy('gradesubmissions.id', 'DESC')
    ->select('gradesubmissions.*', 'programs.program as program', 'programs.acc as acc')
    ->get();


    $settingss = Settings::first();
    
if (!$settingss || $settingss->grades == 0) {
    abort(403, 'Access to grades is disabled.');
}

        return view('addgradesubmission',['subjects'=>$subjects,'settings'=>$settings,'Gradesubmissions'=>$Gradesubmissions,'programs'=>$programs]);
    }public function getSubjectsByProgram($programId)
    { $settings = Settings::first(); // adjust if you have multiple settings
        $semester = $settings->semester;
        $subjects = Curriculums::where('pID', $programId)
            ->whereNull('status') ->where('semester', $semester)
            ->get(['courseCode', 'course']);
    
        return response()->json($subjects);
    }
    public function geditadmin()
    {
        $gradesStudent= Grades::join('students', 'grades.kldID', '=', 'students.kldID')
        ->where('grades.status', 'Published')
        ->select('grades.*', 'students.fName', 'students.lName', 'students.mName')  ->orderBy('grades.created_at', 'desc')->get();
        $gradessubmissions=Gradesubmissions::get();
        $settings = settings::where('id','=',1)->get();
        return view('viewgradesubmissionadmin',['settings'=>$settings,'gradesStudent'=>$gradesStudent,'gradessubmissions'=>$gradessubmissions]);
   
 }

 public function editsettings(Request $request)
    {

        try{  
            settings::where('id', 1)->update([
                'academicyear' => $request->academicyear,
                'year' => $request->year,
                'semester' => $request->semester
            ]);
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' update the settings in the system.'
    ]);
            return back()->with('success', 'Settings updated Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
        
    }

    public function editcor(Request $request)
{
    $settings = Settings::find(1); // Update first record in settings

    $settings->cor = $request->has('cor') ? 1 : 0; // Set 1 if checked, 0 if not checked

    $settings->enrollment = $request->has('enrollment') ? 1 : 0; // Set 1 if checked, 0 if not checked

    $settings->grades = $request->has('grade') ? 1 : 0; // Set 1 if checked, 0 if not checked

    $settings->save();

    return back()->with('success', 'Settings updated successfully!');
}

    public function addgradesubmit(Request $request)
    {
        try {
            // Check if any grades are already assigned (tID != 0)
            $existingGrades = Grades::where('year', $request->year)
                ->where('subject', $request->coursecode)
                ->where('section', $request->section)
                ->where('gsID', $request->program)
                ->where('tID', '!=', 0)
                ->get();
        
            if ($existingGrades->count() > 0) {
                // Get the first tID as example
                $existingTID = $existingGrades->first()->tID;
                return back()->with('error', 'Already graded by Teacher ID: ' . $existingTID);
            }
        
            // If no existing graded records, proceed
            $newProduct = Gradesubmissions::create([
                'gradeName' => $request->gName,
                'section' => $request->section,
                'gsID' => $request->program,
                'coursecode' => $request->coursecode,
                'semester' => $request->semester,
                'year' => $request->year,
                'tID' => $request->tID,
                'status' => '',
                'room' => $request->room,
                'timestart' => $request->timestart,
                'timeend' => $request->timeend,
                'day' => $request->day,
                'program' =>''
            ]);
        
            Grades::where('year', $request->year)
                ->where('subject', $request->coursecode)
                ->where('section', $request->section)
                ->where('gsID', $request->program)
                ->update([
                    'tID' => $request->tID,
                ]);
        
            $idsss = Auth::id();
            Logs::create([
                'userid' => $idsss,
                'remarks' => 'User ID ' . $idsss . ' added grade named ' . $request->gName . ' in the system.'
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
    { $gradesubmission = Gradesubmissions::findOrFail($id); // Get one row
        $teacherID = Auth::user()->id;
    
        // Get all grade rows assigned to this teacher and section and year
        $gradesStudent = Grades::join('students', 'grades.kldID', '=', 'students.kldID')
        ->where('grades.section', $gradesubmission->section)
        ->where('grades.tID', $teacherID)
        ->where('grades.year', $gradesubmission->year)
        ->where('grades.subject', $gradesubmission->coursecode)
        ->where('grades.gsID', $gradesubmission->gsID)
        ->select('grades.*', 'students.fName', 'students.lName', 'students.mName')
        ->get();
    
        $Gradesubmissions = Gradesubmissions::where('id', '=', $id)->get();
        $settings = settings::where('id', '=', 1)->get();
    
        // Get curriculum subjects linked to this program ID
        $pid = $gradesubmission->gsID;
        $subjects = Curriculums::where('pID', $pid)
            ->whereNull('status')
            ->get();
    
        return view('viewgradesubmission', [
            'subjects' => $subjects,
            'settings' => $settings,
            'gradesStudent' => $gradesStudent,
            'Gradesubmissions' => $Gradesubmissions
        ]);
    }

    public function printggs($id)
    { $gradesubmission = Gradesubmissions::findOrFail($id); // Get one row
        $teacherID = Auth::user()->id;
    
        // Get all grade rows assigned to this teacher and section and year
        $gradesStudent = Grades::join('students', 'grades.kldID', '=', 'students.kldID')
        ->where('grades.section', $gradesubmission->section)
        ->where('grades.tID', $teacherID)
        ->where('grades.year', $gradesubmission->year)
        ->where('grades.subject', $gradesubmission->coursecode)
        ->where('grades.gsID', $gradesubmission->gsID)
        ->select('grades.*', 'students.fName', 'students.lName', 'students.mName')
        ->get();
    
        $Gradesubmissions = Gradesubmissions::where('id', '=', $id)->get();
        $settings = settings::where('id', '=', 1)->first(); $pid = $gradesubmission->gsID;
        $c=Programs::where('id',$pid)->first();
        $cc=Curriculums::where('pID',$pid)->first();
        // Get curriculum subjects linked to this program ID
       
        $subjects = Curriculums::where('pID', $pid)
            ->whereNull('status')
            ->get();
    
        return view('printggs', [
            'subjects' => $subjects,
            'settings' => $settings,
            'gradesStudent' => $gradesStudent,
            'Gradesubmissions' => $Gradesubmissions,
            'programs'=>$c,
            'curr'=>$cc
        ]);
    }
    public function updategrades(Gradesubmissions $Gradesubmissions, Request $request)
    {

        try{  
            $Gradesubmissions->update(['gradeName'=>$request->gName,'room'=>$request->room,'timestart'=>$request->timestart,'timeend'=>$request->timeend,'day'=>$request->day]);
        
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' update the subasdasdmittedd grade named ( '.$Gradesubmissions->coursecode.') in the system.'
    ]);
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
    
    public function adminbulkEdit(Request $request)
{
    $ids = $request->input('ids', []);
    $grades = $request->input('grades', []);
    $userId = Auth::id();
    foreach ($ids as $index => $id) {
        $grade = $grades[$index];
    
        // If 0 is selected, consider it as ungraded
        if ($grade == 0) {
            Grades::where('id', $id)->update([
                'grade' => 0,
                'remark' => '--',
            ]);
            continue;
        }
    
        $remark = ($grade == 5.0) ? 'Failed' : 'Passed';
    
        Grades::where('id', $id)->update([
            'grade' => $grade,
            'remark' => $remark,
        ]);
    }

    Logs::create([
        'userid' => $userId,
        'remarks' => 'User ID '.$userId.' updated multiple grades at once.',
    ]);

    return back()->with('success', 'All grades updated successfully!');
}
    
public function bulkEdit(Request $request, $id)
{
    try {
        if ($request->action_type == 'edit') {
            // Loop through submitted grades
            foreach ($request->ids as $key => $gradeId) {
                $gradeValue = $request->grades[$key];
                $remark = ($gradeValue == 5.0) ? 'Failed' : 'Passed';

                Grades::where('id', $gradeId)->update([
                    'grade' => $gradeValue,
                    'remark' => $remark,'status'=>'Initial'
                ]);
            }

            Gradesubmissions::where('id', $id)->update(['status' => 'Initial']);
            Logs::create([
                'userid' => Auth::id(),
                'remarks' => 'User ID '.Auth::id().' edited grades in the system.'
            ]);

            return back()->with('success', 'Grades updated successfully!');
        }

        if ($request->action_type == 'publish') {
            // ❗ Check if any grade is 0
            foreach ($request->grades as $gradeValue) {
                if ($gradeValue == 0 || $gradeValue === "0" || $gradeValue === null) {
                    return back()->with('error', 'Cannot publish. One or more students have a grade of 0.');
                }
            }
        
            // ✅ Update all grades under the same submission
            foreach ($request->ids as $key => $gradeId) {
                Grades::where('id', $gradeId)->update([
                    'status' => 'Published'
                ]);
            }
        
            // ✅ Update Gradesubmission status
            Gradesubmissions::where('id', $id)->update(['status' => 'Published']);
        
            Logs::create([
                'userid' => Auth::id(),
                'remarks' => 'User ID '.Auth::id().' published all grades for gsID '.$id
            ]);
        
            return redirect()->route('addgradesubmission')->with('success', 'Grades Published successfully!');
           }
       
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


}
