<?php

namespace App\Http\Controllers;

use App\Imports\CurriculumImport;
use App\Models\Programs;
use App\Models\Curriculums;
use App\Models\Prereqs;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
class ProgramController extends Controller
{
    public function index()
    {
        $program=Programs::all();
        return view('programs',['program'=>$program]);
    }

    public function updateStatus(Curriculums $curriculums ,Request $request)
    {
        try{
           
            $curriculums->where('id', $request->id)
            ->update(['status' => 'archived']);;
            
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' delete the subject ( '.$curriculums->id.') in the system.'
    ]);
    
            return back()->with('success', 'Program Edit Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Error!');
      }
    }
    public function addprogram(Request $request)
    {
        try{
            $data=$request->validate([
                'acc'=>'required',
                'program'=> 'required',
                'description'=>'required'
              
              
            ]);
            
            $newProduct=Programs::create(['acc'=>$request->acc,
            'program'=>$request->program,
            'description'=>$request->description,  
            'status'=>'']);
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' added program named '.$request->program.' in the system.'
    ]);
            return redirect(route('programs'))->with('success', 'Program Added Successfully!');
        } catch (Exception $e) {
              return response()->json(['error' => 'Error updating status'], 500);
        }
    
    }
    public function addprereq(Request $request)
    {
        try{
            $data=$request->validate([
                'coursecode'=>'required',
                'prereq'=> 'required',
              'zxc'=>'required'
              
            ]);

            $yearcc = Curriculums::where('id', $request->coursecode)->value('years'); // Replace 'column_name' with your desired column
            $yearpr = Curriculums::where('id', $request->prereq)->value('years'); // Replace 'column_name' with your desired column
            $semcc = Curriculums::where('id', $request->coursecode)->value('semester'); // Replace 'column_name' with your desired column
            $sempr = Curriculums::where('id', $request->prereq)->value('semester'); // Replace 'column_name' with your desired column
          
            if($request->coursecode==$request->prereq)
            {
                return back()->with('error', 'Prerequisite and course code cant be equal!!!');
            }
            else if($yearcc>$yearpr){
                return back()->with('error', 'Prerequisite must be in higher year/sem than course code!!!!');
         
            }else if($yearcc==$yearpr&&$semcc>$sempr)
            {
                return back()->with('error', 'Prerequisite must be in higher year/sem than course code!!!!');
         
            
            }else{



            $newProduct=Prereqs::create([
            'courseCode'=>$request->coursecode,
            'preReq'=>$request->prereq,
        'pID'=>$request->zxc,
            'status'=>''
            ]);
           
            $user = Auth::user();
            $idsss = Auth::id();
        Logs::create(['userid'=>$idsss,
        'remarks'=> 'User ID '.$idsss.' added prerequisite ( '.$request->coursecode.' - '.$request->prereq.') in the system.'
    ]);
        return back()->with('success', 'Prerequisite Added Successfully!');  }
        } catch (Exception $e) {
              return response()->json(['error' => 'Error updating status'], 500);
        }
    
    }
    public function editprogram(Programs $program)
    {
        try {        $noofcourses = Curriculums::where('pID', '=', $program->id)->where('status','!=','archived')->count();
            $countlecture = Curriculums::where('pID', '=', $program->id)->where('leclab', '=', 'Lecture')->where('status','!=','archived')->count();
            $countlaboratory = Curriculums::where('pID', '=', $program->id)->where('leclab', '=', 'Laboratory')->where('status','!=','archived')->count();
            $sumUnits = Curriculums::where('pID', '=', $program->id)->where('status','!=','archived')->sum('Unit');
            $subjects = Curriculums::where('pID', $program->id)->where('status','!=','archived')->get();
            
            $programs = Programs::findOrFail($program->id);
            $pp = DB::table('prereqs as cp')
            ->join('curriculums as c1', 'cp.courseCode', '=', 'c1.id')
            ->join('curriculums as c2', 'cp.preReq', '=', 'c2.id')
            ->select('cp.id', 'c1.courseCode as course', 'c1.course as course1', 'c2.courseCode as prerequisite', 'c2.course as prerequisite1')
            ->where('cp.pID', '=', $program->id)
            ->get();
            // Fetch prerequisite relationships
            $prereqs = DB::table('prereqs as p')
                ->join('curriculums as c1', 'p.courseCode', '=', 'c1.id')
                ->join('curriculums as c2', 'p.preReq', '=', 'c2.id')
                ->select('c1.courseCode as course', 'c2.courseCode as prerequisite')
                ->where('p.pID', '=', $program->id)
                ->get();


                $curriculum = Curriculums::leftJoin('prereqs as p', 'curriculums.id', '=', 'p.courseCode')
                ->where('curriculums.pID', '=', $program->id)
                ->where('curriculums.status', '!=', 'archived')
                
                ->orderBy('curriculums.type')
                ->orderBy('curriculums.years')
                ->orderBy('curriculums.semester')
                ->orderBy('curriculums.courseCode')
                ->select('curriculums.*')
                ->get();
            



            return view('curriculum', compact(
                
                'programs',
                'program',
                'countlecture',
                'countlaboratory',
                'sumUnits',
                'curriculum',
                'noofcourses',
                'pp'
            ));
    

















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
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }

    }
    public function updateprogram(Programs $program ,Request $request)
    {
        try{
        $program->update(['acc'=>$request->acc,
        'program'=>$request->program]);
        
        $user = Auth::user();
        $idsss = Auth::id();
    Logs::create(['userid'=>$idsss,
    'remarks'=> 'User ID '.$idsss.' update the program ( '.$program->acc.') in the system.'
]);

        return back()->with('success', 'Program Edit Successfully!');
    } catch (Exception $e) {
        return response()->json(['error' => 'Error updating status'], 500);
  }
    }
    
    public function editcourse(Curriculums $curriculum)
    {
        
        return view('editcourse',['curriculum'=>$curriculum]);
    
      
    }
    public function updatecourse(Curriculums $curriculum, Request $request)
    {
        try{
            $curriculum->update([
            'courseCode'=>$request->cc,
            'course'=>$request->c,
            'type'=>$request->type,
            'leclab'=>$request->leclab,
            'unit'=>$request->unit,
            'semester'=>$request->semester,
            'years'=>$request->years,
        ]);
        $user = Auth::user();
        $idsss = Auth::id();
    Logs::create(['userid'=>$idsss,
    'remarks'=> 'User ID '.$idsss.' update the course ( '.$request->cc.') in the system.'
]);

            return back()->with('success', 'Program Edit Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
    }
    public function addcurriculums(Request $request)
    {
   
      try{
            
            $newProduct=Curriculums::create([
            'pID'=>$request->pID,
            'courseCode'=>$request->cc,
            'course'=>$request->c,
            'type'=>$request->type,
            'semester'=>$request->semester,
            'years'=>$request->years,
            'leclab'=>$request->leclab,
            'unit'=>$request->unit
        ]);
        $user = Auth::user();
        $idsss = Auth::id();
    Logs::create(['userid'=>$idsss,
    'remarks'=> 'User ID '.$idsss.' add the course ( '.$request->cc.') in the system.'
]);
        return back()->with('success', 'Program Added Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $id = $request->input('id');
        $user = Auth::user();
        $idsss = Auth::id();
    Logs::create(['userid'=>$idsss,
    'remarks'=> 'User ID '.$idsss.' import curriculum using excel with program id ( '.$request->input('id').') in the system.'
]);
        Excel::import(new CurriculumImport($id), $request->file('file'));

        return back()->with('success', 'Excel file imported successfully.');
    }
}
