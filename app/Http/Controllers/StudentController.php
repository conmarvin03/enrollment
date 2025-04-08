<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Programs;
use App\Models\Curriculums;
use App\Models\User;
use Exception;
use App\Models\Students;

use App\Actions\Fortify\CreateNewUser;
use App\Imports\StudentImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
class StudentController extends Controller
{
    public function index()
    {
        
        $students=User::select('users.*','programs.*','students.*')->join('programs','users.pID','programs.id')->join('students','students.kldID','users.kldID')->where('users.pID','!=',0)->where('users.role','=','Student')->get();

        $programs=Programs::all();
        return view('student',['students'=>$students,'programs'=>$programs]);
    }
    public function viewGrades($id)
{
         
        $program=Students::where('kldID','=',$id)->get();
        return view('viewgrades',['id'=>$program]
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

        return redirect()->route('addadmin')->with('success', 'User added successfully!');
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
        'address'=>NULL
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
        'bday'=>NULL,
        'address'=>NULL
    ]);

           
    return redirect(route('students'))->with('success', 'Students Added Successfully!');
} catch (Exception $e) {
    return redirect(route('students'))->with('error', 'Error adding students! Email must be unique!');
}

    }
    public function editStudent($id)
    {
        $student = Students::where('kldID', $id)->first();
     
        return view('editStudent',['students'=>$student]);
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
