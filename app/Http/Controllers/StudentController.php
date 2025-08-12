<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Programs;
use App\Models\Curriculums;
use App\Models\Prereqs;
use App\Models\Settings;
use App\Models\User;
use Exception;
use App\Models\Students;

use App\Actions\Fortify\CreateNewUser;
use App\Imports\ScheduleImport;
use App\Imports\StudentImport;
use App\Models\Grades;
use App\Models\Gradesubmissions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
class StudentController extends Controller
{
    public function index()
    {
        
        $students=User::select('users.*','programs.*','students.*')->join('programs','users.pID','programs.id')->join('students','students.kldID','users.kldID')->where('users.pID','!=',0)->where('users.role','=','Student')->get();

        $programs=Programs::all();
        return view('student',['students'=>$students,'programs'=>$programs]);
    }

    public function viewSchedule()
    {   
        $settings=Settings::where('id',1)->first();
        $gradesubmission = Gradesubmissions::where('gradesubmissions.year', $settings->year)
        ->where('gradesubmissions.semester', $settings->semester)
        ->join('programs', 'gradesubmissions.gsID', '=', 'programs.id')
        ->join('users', 'gradesubmissions.tID', '=', 'users.id')
        ->select('gradesubmissions.*', 'programs.acc', 'programs.program', 'users.name as teacher_name') // select fields as needed
        ->get();
    
        return view('schedule',compact('gradesubmission'));
    }
    public function importschedule(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new ScheduleImport, $request->file('file'));

        

        return back()->with('success', 'Schedule imported successfully!');
    }
    public function showGrades()
{         $user = Auth::user();
          $idsss = Auth::id();

          $p=User::where('id','=',$idsss)->first();
          $grades = Grades::leftJoin('users', 'grades.tID', '=', 'users.id')
          ->join('curriculums', 'grades.subject', '=', 'curriculums.courseCode')
          ->where('grades.gsID','=',$p->pID)
          ->where('grades.kldID', '=', $p->kldID)
          ->select('grades.*', 'users.name as teacher_name', 'curriculums.course')->distinct()->get();
        $settings=Settings::where('id','=',1)->first();
      $program=Students::where('kldID','=',$idsss)->get();
              return view('viewgrades',['id'=>$program,'grades'=>$grades,'settings'=>$settings]
              );
      
    

    }public function getNextYearSemester($currentYear, $currentSemester, $pID)
    {
        // 1. Get ordered list of year/semester pairs with row numbers
        $orderedSemesters = DB::table('curriculums')
            ->select('years', 'semester')
            ->where('pID', $pID)
            ->distinct()
            ->orderBy('years')
            ->orderBy('semester')
            ->get()
            ->values(); // make sure it's indexed
    
        // 2. Find current index
        $currentIndex = $orderedSemesters->search(function ($item) use ($currentYear, $currentSemester) {
            return $item->years == $currentYear && $item->semester == $currentSemester;
        });
    
        // 3. Get next semester if available
        if ($currentIndex !== false && isset($orderedSemesters[$currentIndex + 1])) {
            $next = $orderedSemesters[$currentIndex + 1];
            return [
                'year' => $next->years,
                'semester' => $next->semester
            ];
        }
    
        return null; // No more semesters
    }



    public function enrollnxtsem(Request $request)
    {
        // Retrieve the authenticated student's ID and program
        $studentId = Auth::id();
        $p = Auth::user();
        $programId = $p->pID;
    
        // STEP 1: Get the latest enrolled subject to determine current year/semester
        $currentGrade = Grades::where('kldID', $p->kldID)
            ->orderBy('years', 'desc')
            ->orderBy('semester', 'desc')
            ->first();
    
        if (!$currentGrade) {
            return back()->with('error', 'No current enrollment found.');
        }
    
        // STEP 2: Check if all grades are complete and published
        $gradesWithIssues = Grades::leftJoin('users', 'grades.tID', '=', 'users.id')
            ->join('curriculums', 'grades.subject', '=', 'curriculums.courseCode')
            ->where('grades.gsID', $programId)
            ->where('grades.kldID', $p->kldID)
            ->where(function ($query) {
                $query->where('grades.tID', '=', 0)
                      ->orWhere('grades.grade', '=', 0)
                      ->orWhere('grades.status', '!=', 'Published');
            })
            ->select('grades.*')
            ->distinct()
            ->get();
    
        if ($gradesWithIssues->isNotEmpty()) {
            return back()->with('error', 'Must have grades in all subjects and all grades must be published!');
        }
    
        // STEP 3: Get next year/semester (next academic year/semester)
        $nextSem = $this->getNextYearSemester($currentGrade->years, $currentGrade->semester, $programId);
    
        if (!$nextSem) {
            return back()->with('error', 'No more semesters to enroll.');
        }
    
        // STEP 4: Get all subjects for the next semester
        $nextSubjects = Curriculums::where('years', $nextSem['year'])
            ->where('semester', $nextSem['semester'])
            ->where('pID', $programId)
            ->get();
    
        $settings = Settings::first();
    
        foreach ($nextSubjects as $subject) {
            // STEP 5: Check if the subject has prerequisites in the prereqs table
            $prereqs = Prereqs::where('courseCode', $subject->id)->get();
    
            foreach ($prereqs as $prereq) {
                // Get the prerequisite course code (from the curriculums table)
                $preReqCourseCode = Curriculums::where('id', $prereq->preReq)->value('courseCode');
    
                // STEP 6: Check if the student has passed the prerequisite subject (grade should be 1, 2, or 3)
                $failedPrereqs = Grades::where('kldID', $p->kldID)
                    ->where('subject', $preReqCourseCode)
                    ->whereIn('grade', [5, 6])  // Grades 5 or 6 mean failure
                    ->where('status', 'Published')
                    ->exists();
    
                if ($failedPrereqs) {
                    // If student failed the prerequisite, skip enrolling in this subject
                    continue 2; // Skip this subject and go to the next one
                }
            }
    
            // STEP 7: If no prerequisites failed, enroll the student in this subject
            Grades::create([
                'section'   => $p->section,
                'subject'   => $subject->courseCode,
                'years'     => $subject->years,
                'semester'  => $subject->semester,
                'grade'     => 0,  // Initial grade is 0 (in-progress)
                'remark'    => '--',  // Placeholder remark
                'status'    => '',  // No status yet
                'kldID'     => $p->kldID,
                'year'      => $settings->year,
                'gsID'      => $p->pID,
                'tID'       => 0  // No teacher assigned yet
            ]);
        }
    
        // STEP 8: Success message
        return back()->with('success', 'Next semester subjects enrolled successfully.');
    }
    



public function gradeEnroll(Request $request)
{  $settings = Settings::where('id', '=', 1)->first();
    // Check if a record exists with the same kldID, subject, and a grade less than 3
    $existingGrade = Grades::where('kldID', '=', $request->kldID)
                           ->where('subject', '=', $request->curriculum)
                           ->where('grade', '<', 3)
                           ->first();

    if ($existingGrade) {
        // If such a record exists, return an error message
        return back()->with('error', 'Record already exists with a grade less than 3. Cannot proceed.');
    }

    // Check if a record exists with the same kldID, subject, grade = 0, and tID > 0
    $existingRecord = Grades::where('kldID', '=', $request->kldID)
                            ->where('subject', '=', $request->curriculum)
                            ->where('grade', '=', 0)
                            ->where('tID', '>', 0)
                            ->first();

    if ($existingRecord) {
        // If such a record exists, return an error message
        return back()->with('error', 'Record already exists with grade 0 and tID > 0. Cannot proceed.');
    }

    // If no conflicting records exist, proceed with the insertion
    $subject = Curriculums::where('courseCode', '=', $request->curriculum)->first();
  
    $student = Students::where('kldID', '=', $request->kldID)->first();

    $gradesubmissions=Gradesubmissions::where('coursecode','=',$subject->courseCode)
    ->where('year','=',$settings->year)
    ->where('gsID','=',$student->pID)
    ->where('semester','=',$settings->semester)->first();
$settings=Settings::where('id',1)->first();
    if($gradesubmissions)
    {
        Grades::create([
            'section'   => $request->section,
            'subject'   => $subject->courseCode,
            'years'     => $settings->year,
            'semester'  => $settings->semester,
            'grade'     => 0,
            'remark'    => '--',
            'status'    => '',
            'kldID'     => $request->kldID,
            'year'      => $settings->year,
            'gsID'      => $student->pID,
            'tID'       => $gradesubmissions->tID
        ]);
    }else{

        Grades::create([
            'section'   => $request->section,
            'subject'   => $subject->courseCode,
            'years'     => $settings->year,
            'semester'  => $settings->semester,
            'grade'     => 0,
            'remark'    => '--',
            'status'    => '',
            'kldID'     => $request->kldID,
            'year'      => $settings->year,
            'gsID'      => $student->pID,
            'tID'       => 0
        ]);
    }
   

    return back()->with('success', 'Added subject successfully!');
}


public function getCurriculum($pID)
{
    // Fetch the curriculum based on the pID (assuming pID is stored in the Curriculum table)
    $s=Students::where('kldID','=',$pID)->first();
    $curriculums = Curriculums::where('pID', $s->pID)->get(['course', 'courseCode']); // Adjust based on your schema
    
    // Return the curriculum data as JSON
    return response()->json($curriculums);
}
public function enrollStudent()
{

    $student=Students::get();
    return view('enrollstudent',['students'=>$student]);
   
}

    public function viewGrades($id)
{
    
$p=Students::where('kldID','=',$id)->first();
    $grades = Grades::leftJoin('users', 'grades.tID', '=', 'users.id')
    ->join('curriculums', 'grades.subject', '=', 'curriculums.courseCode')
    ->where('grades.gsID','=',$p->pID)
    ->where('grades.kldID', '=', $id)
    ->select('grades.*', 'users.name as teacher_name', 'curriculums.course')->distinct()->get();
$program=Students::where('kldID','=',$id)->get();

$settings = Settings::first();
        return view('viewgradesadmin',['id'=>$program,'grades'=>$grades,'settings'=>$settings]
        );


















        // $noofcourses = Curriculums::where('pID', '=', $program->id)->count();
        // $countlecture = Curriculums::where('pID', '=', $program->id)->where('leclab', '=', 'Lecture')->count();
        // $countlaboratory = Curriculums::where('pID', '=', $program->id)->where('leclab', '=', 'Laboratory')->count();
        // $sumUnits = Curriculums::where('pID', '=', $program->id)->sum('Unit');
        // $subjects = Curriculums::where('pID', $program->id)->get();
        
        // $programs = Programs::findOrFail($program->id);
    
        // // Fetch prerequisite relationships
        // $prereqs = DB::table('prereqs as p')
        //     ->join('curriculums as c1', 'p.courseCode', '=', 'c1.id')
        //     ->join('curriculums as c2', 'p.preReq', '=', 'c2.id')
        //     ->select('c1.courseCode as course', 'c2.courseCode as prerequisite')
        //     ->where('p.pID', '=', $program->id)
        //     ->get();
    
        //     $nodeDataArray = $subjects->map(function ($subject) {
        //         // Assign colors based on subject type
        //         $color = match($subject->type) {
        //             'Core' => 'red',
        //             'Elective' => 'blue',
        //             'General' => 'green',
        //             default => 'gray'
        //         };
            
        //         return [
        //             'key' => $subject->courseCode,
        //             'courseCode' => $subject->courseCode,
        //             'type' => $subject->type,
        //             'semester' => $subject->semester,
        //             'year' => $subject->years,
        //             'color' => $color
        //         ];
        //     });
            
        //     $linkDataArray = $prereqs->map(function ($prereq) {
        //         return [
        //             'from' => $prereq->prerequisite,
        //             'to' => $prereq->course
        //         ];
        //     });
    
        // $pp = DB::table('prereqs as cp')
        //     ->join('curriculums as c1', 'cp.courseCode', '=', 'c1.id')
        //     ->join('curriculums as c2', 'cp.preReq', '=', 'c2.id')
        //     ->select('cp.id', 'c1.courseCode as course', 'c1.course as course1', 'c2.courseCode as prerequisite', 'c2.course as prerequisite1')
        //     ->where('cp.pID', '=', $program->id)
        //     ->get();
    
        // $curriculum = Curriculums::where('pID', '=', $program->id)->get();
    
        // return view('curriculum', compact(
        //     'nodeDataArray',
        //     'linkDataArray',
        //     'programs',
        //     'program',
        //     'curriculum',
        //     'countlecture',
        //     'countlaboratory',
        //     'sumUnits',
        //     'noofcourses',
        //     'pp'
        // ));
    
    }
    public function create()
    {
        return view('addadmin'); // Reuse Jetstream's register form
    }

    public function store(Request $request)
    {
        // Use Jetstream's CreateNewUser action
        $userCreator = new CreateNewUser();
        
        // Validate and create user
        $user = $userCreator->create($request->all());

        return redirect()->route('users.create')->with('success', 'User added successfully!');
    }
    public function teacherscreate()
    {
        return view('addteacher'); // Reuse Jetstream's register form
    }

    public function teachersstore(Request $request)
    {
        // Use Jetstream's CreateNewUser action
        $userCreator = new CreateNewUser();
        
        // Validate and create user
        $user = $userCreator->teachercreate($request->all());

        return redirect()->route('users.teacher')->with('success', 'User added successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        
        Excel::import(new StudentImport(), $request->file('file'));

        return back()->with('success', 'Excel file imported successfully.');
    }



    public function addstudents(Request $request)
    {
        try{

            $aaa=Settings::where('id','=',1)->first();
      
          User::create([
        'name' => $request->fName.' '.$request->mName.' '.$request->lName,
        'fName' => $request->fName,
        'lName' => $request->lName,
        'mName' => $request->mName,
        'email' => $request->email,
        'password' => Hash::make($request->kldid.''.$request->lName),
        'role' => 'Student',
        'pID'=>$request->pID,
        'kldID'=>$request->kldid,
        'img'=>'asd',
        'gender'=>NULL,
        'bday'=>NULL,
        'address'=>NULL,
        'section'=>$request->section,
        'ay'=>$aaa->year
    ]);

    Students::create([
       
        'fName' => $request->fName,
        'lName' => $request->lName,
        'mName' => $request->mName,
        'email' => $request->email,
         'pID'=>$request->pID,
        'kldID'=>$request->kldid,
        'img'=>'asd',
        'gender'=>NULL,
        'bday'=>NULL,
        'address'=>NULL,
        'section'=>$request->section
        ,'ay'=>$aaa->year
    ]);


    $firstSemSubjects = Curriculums::where('pID', $request->pID)
            ->where('years', 1)
            ->where('semester', 1)
            ->get();

        foreach ($firstSemSubjects as $subject) {

            $chcktId=Grades::where('gsID',$request->pID)->where('year',$aaa->year)->where('section',$request->section)->where('semester',$aaa->semester)->where('subject',$subject->courseCode)->first();
            
    
            Gradesubmissions::where('gsID',$request->pID)->where('year',$aaa->year)->where('section',$request->section)->where('coursecode',$subject->courseCode)->update(['status'=>'Initial']);
            if($chcktId)
            {  Grades::firstOrCreate(
                [
                    'kldID' => $request->kldid,
                    'subject' => $subject->courseCode,
                    'semester' => 1,
                    'year' => $aaa->year, // include year to avoid duplicates per year
                    'section' => trim($request->section),
                ],
                [
                  'pID' =>$request->pID,
                    'grade' => 0,
                    'tID' => $chcktId->tID,
                    'gsID' => $request->pID,
                    'remark' => '--',
                    'status' => '',
                    'years' => 1
                ]
            );

            }else{
                Grades::firstOrCreate(
                    [
                        'kldID' => $request->kldid,
                        'subject' => $subject->courseCode,
                        'semester' => 1,
                        'year' => $aaa->year, // include year to avoid duplicates per year
                        'section' => trim($request->section),
                    ],
                    [
                      'pID' =>$request->pID,
                        'grade' => 0,
                        'tID' => 0,
                        'gsID' => $request->pID,
                        'remark' => '--',
                        'status' => '',
                        'years' => 1
                    ]
                );
            }
          
        }
    return back()->with('success', 'Students Added Successfully!');
} catch (Exception $e) {
    return back()->with('error', $e->getMessage()); // ✅ works for general exceptions
}

    }
    public function editStudent($id)
    {
        $student = Students::where('kldID', $id)->first();
     
$settings = Settings::first();
        return view('editStudent',['students'=>$student,'settings'=>$settings]);
    }
    public function updateStudent( Request $request,$id)
    {
       try {
        // Validate input
        $request->validate([
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'mName' => 'nullable|string|max:255',
            'bday' => 'required|date',
            'address' => 'required|string|max:500',
            'gender' => 'required|string|max:10',
             'img'=>'nullable|mimes:png,jpg,jpeg'
        ]);
        if($request->has('img'))
        { $file=$request->file('img');
            $extension=$file->getClientOriginalExtension();
            $filename=time().','.$extension;
            $path='upload/profilepic/';
            $file->move($path, $filename);
        Students::where('kldID', $id)->update([
            'fName' => $request->input('fName'),
            'lName' => $request->input('lName'),
            'mName' => $request->input('mName'),
            'bday' => $request->input('bday'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'img'=>$path.$filename
        ]);
        User::where('kldID', $id)->update([
            'name' => $request->input('fName').' '. $request->input('mName').' '.$request->input('lName'),
            'fName' => $request->input('fName'),
            'lName' => $request->input('lName'),
            'mName' => $request->input('mName'),
            'bday' => $request->input('bday'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'img'=>$path.$filename
        ]);
    }else{

    }
        // Redirect back to the edit page with success message
        return redirect()->route('student.edit', $id)->with('success', 'Student Information Updated Successfully!');

    } catch (Exception $e) {
        // Fetch student data again if an error occurs
        $student = Students::where('kldID', $id)->first();

        return view('editStudent', ['students' => $student])->with('error', 'Error updating student! Email must be unique!');
    }
}
}
