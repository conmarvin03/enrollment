<?php

namespace App\Http\Controllers;
use App\Models\Programs;
use App\Models\Curriculums;
use App\Models\Prereqs;
use Illuminate\Http\Request;
use Exception;

class ProgramController extends Controller
{
    public function index()
    {
        $program=Programs::all();
        return view('programs',['program'=>$program]);
    }
    public function addprogram(Request $request)
    {
        try{
            $data=$request->validate([
                'acc'=>'required',
                'program'=> 'required'
              
              
            ]);
            
            $newProduct=Programs::create(['acc'=>$request->acc,
            'program'=>$request->program,
            'status'=>'']);
           
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
                'prereq'=> 'required'
              
              
            ]);

            $yearcc = Curriculums::where('id', $request->coursecode)->value('years'); // Replace 'column_name' with your desired column
            $yearpr = Curriculums::where('id', $request->prereq)->value('years'); // Replace 'column_name' with your desired column
            $semcc = Curriculums::where('id', $request->coursecode)->value('semester'); // Replace 'column_name' with your desired column
            $sempr = Curriculums::where('id', $request->prereq)->value('semester'); // Replace 'column_name' with your desired column
          
            if($request->coursecode==$request->prereq)
            {
                return redirect(route('programs'))->with('error', 'Prerequisite and course code cant be equal!!!');
            }
            else if($yearcc>$yearpr){
                return redirect(route('programs'))->with('error', 'Prerequisite must be in higher year/sem than course code!!!!');
         
            }else if($yearcc==$yearpr&&$semcc>$sempr)
            {
                return redirect(route('programs'))->with('error', 'Prerequisite must be in higher year/sem than course code!!!!');
         
            
            }else{



            $newProduct=Prereqs::create([
            'courseCode'=>$request->coursecode,
            'preReq'=>$request->prereq,
            ]);
           
            return redirect(route('programs'))->with('success', 'Prerequisite Added Successfully!');
        }
        } catch (Exception $e) {
              return response()->json(['error' => 'Error updating status'], 500);
        }
    
    }
    public function editprogram(Programs $program)
    {

        $noofcourses=Curriculums::where('pID','=',$program->id)->count();
        $countlecture=Curriculums::where('pID','=',$program->id)->where('leclab','=','Lecture')->count();
        $countlaboratory=Curriculums::where('pID','=',$program->id)->where('leclab','=','Laboratory')->count();
        $countlaboratory=Curriculums::where('pID','=',$program->id)->where('leclab','=','Laboratory')->count();
        $sumUnits=Curriculums::where('pID','=',$program->id)->sum('Unit');
     
        $curriculum=Curriculums::where('pID','=',$program->id)->get();
        return view('curriculum',['program'=>$program,'curriculum'=>$curriculum,'countlecture'=>$countlecture,'countlaboratory'=>$countlaboratory,
        'sumUnits'=>$sumUnits,'noofcourses'=>$noofcourses]);
    }
    public function updateprogram(Programs $program ,Request $request)
    {
        try{
        $program->update(['acc'=>$request->acc,
        'program'=>$request->program]);
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
        
        return back()->with('success', 'Program Added Successfully!');
        } catch (Exception $e) {
            return response()->json(['error' => 'Error updating status'], 500);
      }
    }
}
