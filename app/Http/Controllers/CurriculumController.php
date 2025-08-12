<?php

namespace App\Http\Controllers;

use App\Models\Gradesubmissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\settings;
use App\Models\Curriculums;
use App\Models\Students;
use App\Models\Grades;
use App\Models\Programs;
use App\Models\User;

class CurriculumController extends Controller
{
    public function index()
    {
        $user = Auth::user()->email;
        $idsss = Auth::id();
        if(Auth::user()->role=="capi"){
            return view('dashboardcapi'); 


        }else if(Auth::user()->role=="")
        {
            $settings=Settings::where('id',1)->first();
            $studentcount = Students::count('id');
            $programs = Programs::count('id');
            $gradesubmissions = Gradesubmissions::where('status','Published')->count('id');
            $teacher = User::where('role','teacher')->count('id');
            
            $programData = DB::table('programs')
            ->join('students', 'students.pID', '=', 'programs.id')
            ->select('programs.acc as program', DB::raw('count(students.id) as student_count'))->groupBy('programs.acc')
            ->get();

            $gradeStats = DB::table('grades')
            ->select('remark', DB::raw('COUNT(*) as total'))
            ->where('status', 'Published')
            ->whereIn('remark', ['Passed', 'Failed'])
            ->where('year',$settings->year)->where('semester',$settings->semester)
            ->groupBy('remark')
            ->get();

            return view('dashboard',compact('studentcount','teacher','gradesubmissions','programs','programData','gradeStats','gradeStats')); 
        }else if(Auth::user()->role=="Student")
        {
               
            $user = Auth::user()->email;
            $idsss = Auth::id();
            $pID=Auth::user()->pID;
            $kldID=Auth::user()->kldID;
            $gradeStats = DB::table('grades')
            ->select('remark', DB::raw('COUNT(*) as total'))
            ->where('status', 'Published')
            ->where('kldID',Auth::user()->kldID)
            ->whereIn('remark', ['Failed', 'Passed'])
            ->groupBy('remark')
            ->get();

            $gradescount=Grades::where('kldID',$kldID)->where('grade','>',0)->count('id');
            $grades=Grades::where('kldID',$kldID)->where('grade','>',0)->sum('grade');
            if($grades==0 || $gradescount==0)
            {
                $GWA=0;
            }else{
                
            $GWA=$grades/$gradescount;

            }
            
            $totalUnits = DB::table('grades')
            ->join('curriculums', 'grades.gsID', '=', 'curriculums.pID')
            ->where('grades.kldID', $kldID)
            ->where('grades.status','Published')
            ->where('grades.grade','>',0) 
            ->sum('curriculums.unit');


            $programs = Programs::findOrFail($pID);
            $pp = DB::table('prereqs as cp')
            ->join('curriculums as c1', 'cp.courseCode', '=', 'c1.id')
            ->join('curriculums as c2', 'cp.preReq', '=', 'c2.id')
            ->select('cp.id', 'c1.courseCode as course', 'c1.course as course1', 'c2.courseCode as prerequisite', 'c2.course as prerequisite1')
            ->where('cp.pID', '=', $pID)
            ->get();
            // Fetch prerequisite relationships
            $prereqs = DB::table('prereqs as p')
                ->join('curriculums as c1', 'p.courseCode', '=', 'c1.id')
                ->join('curriculums as c2', 'p.preReq', '=', 'c2.id')
                ->select('c1.courseCode as course', 'c2.courseCode as prerequisite')
                ->where('p.pID', '=', $pID)
                ->get();

                $curriculum = DB::table('curriculums')
    ->leftJoin('grades', function ($join) use ($kldID) {
        $join->on('curriculums.courseCode', '=', 'grades.subject')
             ->where('grades.kldID', '=', $kldID);
    })
    ->where('curriculums.pID', '=', $pID)
    ->whereNull('curriculums.status')
    ->select('curriculums.*', 'grades.grade')
    ->orderBy('curriculums.type')
    ->orderBy('curriculums.years')
    ->orderBy('curriculums.semester')
    ->orderBy('curriculums.courseCode')
    ->get();

    $gradeCounts = DB::table('grades')
    ->select('grade', DB::raw('count(*) as count'))
    ->where('kldID',$kldID)
    ->whereNotNull('grade') // optional: only count non-null grades
    ->groupBy('grade')
    ->orderBy('grade') // ascending order (1.00 to 5.00)
    ->get();


    // Retrieve the current year and semester from the settings table$settings = DB::table('settings')->first();

// Check if there are any zero grades
$hasZeroGrades = Grades::where('kldID', $kldID)
->where('grade', 0)
->exists();

if ($hasZeroGrades) {
// Get the first grade=0 row
$zeroGrade = Grades::where('kldID', $kldID)
    ->where('grade', 0)
    ->first();

// Use its year, semester, gsID, section
$grades = Grades::leftJoin('users', 'grades.tID', '=', 'users.id')
    ->join('curriculums', 'grades.subject', '=', 'curriculums.courseCode')
    ->where('grades.kldID', '=', $kldID)
    ->where('grades.gsID', '=', $zeroGrade->gsID)
    ->where('grades.year', '=', $zeroGrade->year)
    ->where('grades.semester', '=', $zeroGrade->semester)
    ->where('grades.section', '=', $zeroGrade->section)
    ->select('grades.*', 'users.name as teacher_name', 'curriculums.course')
    ->distinct()
    ->get();
} else {
// Default to settings year/semester
$settings = DB::table('settings')->first();
$grades = Grades::leftJoin('users', 'grades.tID', '=', 'users.id')
    ->join('curriculums', 'grades.subject', '=', 'curriculums.courseCode')
    ->where('grades.kldID', '=', $kldID)
    ->where('grades.year', '=', $settings->year)
    ->where('grades.semester', '=', $settings->semester)
    ->where('grades.section', '=', Auth::user()->section)
    ->where('grades.gsID', '=', Auth::user()->pID)
    ->select('grades.*', 'users.name as teacher_name', 'curriculums.course')
    ->distinct()
    ->get();
}

// Attach schedule (or TBA)
foreach ($grades as $grade) {
$schedule = DB::table('gradesubmissions')
    ->where('gsID', '=', $grade->gsID)
    ->where('section', '=', $grade->section)
    ->where('year', '=', $grade->year)
    ->where('semester', '=', $grade->semester)
    ->where('courseCode', '=', $grade->subject)
    ->first();

$grade->timestart = $schedule->timestart ?? 'TBA';
$grade->timeend = $schedule->timeend ?? 'TBA';
$grade->room = $schedule->room ?? 'TBA';
$grade->day = $schedule->day ?? 'TBA';
}






            return view('dashboardstudent',compact('totalUnits','gradeStats','programs','prereqs','curriculum','GWA','gradeCounts','grades')); 
        }else{
            
       
            $user = Auth::user()->email;
            $idsss = Auth::id();
            $settings=Settings::where('id',1)->first();
            $grades=Gradesubmissions::where('tID',Auth::user()->id)->where('year',$settings->year)->where('semester',$settings->semester)->count('id');
            $published=Gradesubmissions::where('tID',Auth::user()->id)->where('status','Published')->where('year',$settings->year)->where('semester',$settings->semester)->count('id');
            $initial=Gradesubmissions::where('tID',Auth::user()->id)->where('status','Initial')->where('year',$settings->year)->where('semester',$settings->semester)->count('id');
            $needstograde=Gradesubmissions::where('tID',Auth::user()->id)->where('status','')->where('year',$settings->year)->where('semester',$settings->semester)->count('id');
        

            $gradeStats = DB::table('grades')
            ->select('remark', DB::raw('COUNT(*) as total'))
            ->where('status', 'Published')
            ->where('tID',Auth::user()->id)->where('year',$settings->year)->where('semester',$settings->semester)
            ->whereIn('remark', ['Failed', 'Passed'])
            ->groupBy('remark')
            ->get();
            return view('dashboardteacher',compact('grades','published','initial','needstograde','gradeStats')); 
        }
    }
 
}
