<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/programs',[ProgramController::class,'index'])->name('programs');
    route::post('/addrank',[ProgramController::class,'addprogram'])->name('addprograms');

    route::post('/addcurriculums',[ProgramController::class,'addcurriculums'])->name('addcurriculums');
    route::get('/program/{program}/edit',[ProgramController::class,'editprogram'])->name('program.edit');
    route::put('/program/{program}/update',[ProgramController::class,'updateprogram'])->name('updateprogram');
    route::get('/course/{curriculum}/edit',[ProgramController::class,'editcourse'])->name('course.edit'); 
    route::put('/course/{curriculum}/update',[ProgramController::class,'updatecourse'])->name('updatecourse');    

// add prereq
    route::post('/addprereq',[ProgramController::class,'addprereq'])->name('addprereq');
  
    route::put('/archive/prereq',[ProgramController::class,'archiveprereq'])->name('archiveprereq');
    Route::get('/students',[StudentController::class,'index'])->name('students');
    route::post('/addstudents',[StudentController::class,'addstudents'])->name('addstudents');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
 
});
