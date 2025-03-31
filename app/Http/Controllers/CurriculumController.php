<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index()
    {
        $user = Auth::user()->email;
        $idsss = Auth::id();
        if(Auth::user()->role=="")
        {
            return view('dashboard'); 
        }else if(Auth::user()->role=="Student")
        {
            
            return view('dashboard'); 
        }else{

            return view('dashboard'); 
        }
    }
}
