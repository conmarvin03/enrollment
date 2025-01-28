<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Programs;
use App\Models\Curriculums;
use App\Models\User;
use Exception;
use App\Models\Students;
class StudentController extends Controller
{
    public function index()
    {
        
        $students=User::select('users.*','programs.*')->join('programs','users.pID','programs.id')->where('users.pID','!=',0)->where('users.role','=','Student')->get();

        $programs=Programs::all();
        return view('student',['students'=>$students,'programs'=>$programs]);
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

    
           
    return redirect(route('students'))->with('success', 'Students Added Successfully!');
} catch (Exception $e) {
    return redirect(route('students'))->with('error', 'Error adding students! Email must be unique!');
}

    }
}
