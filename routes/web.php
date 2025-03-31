<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\GradeController;
use App\Http\Middleware\CheckRole;
Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::middleware([CheckRole::class . ':teacher'])->group(function () {
        Route::get('/addgradesubmission', [GradeController::class, 'index'])->name('addgradesubmission');
        route::post('/add-grades',[GradeController::class,'addgradesubmit'])->name('addgradesubmit');
        route::get('/grades/{Gradesubmissions}/edit',[GradeController::class,'gradesview'])->name('grades.edit');
       
        route::put('/grades/{Gradesubmissions}/update',[GradeController::class,'updategrades'])->name('edit.grades');    
       
       
    });
    Route::post('/import-excel', [GradeController::class, 'import'])->name('import.excel');
    
    Route::middleware([CheckRole::class . ':Student'])->group(function () {
        Route::get('/asd', [GradeController::class, 'index'])->name('addgradesubmission');
    
       
    });

    Route::middleware([CheckRole::class . ':'])->group(function () {
        Route::get('/addgradesubmission', [GradeController::class, 'index'])->name('addgradesubmission');
    
        Route::get('/programs',[ProgramController::class,'index'])->name('programs');
        route::post('/addprograms',[ProgramController::class,'addprogram'])->name('addprograms');
    
        route::post('/addcurriculums',[ProgramController::class,'addcurriculums'])->name('addcurriculums');
        route::get('/program/{program}/edit',[ProgramController::class,'editprogram'])->name('program.edit');
        route::put('/program/{program}/update',[ProgramController::class,'updateprogram'])->name('updateprogram');
        route::get('/course/{curriculum}/edit',[ProgramController::class,'editcourse'])->name('course.edit'); 
        route::put('/course/{curriculum}/update',[ProgramController::class,'updatecourse'])->name('updatecourse');    
    
    
        route::put('/curriculum/{curriculum}/update',[ProgramController::class,'updateStatus'])->name('updateStatus');
    
    // add prereq
        route::post('/addprereq',[ProgramController::class,'addprereq'])->name('addprereq');
      
        Route::get('/students',[StudentController::class,'index'])->name('students');
        route::post('/addstudents',[StudentController::class,'addstudents'])->name('addstudents');
    
        route::get('/student/{id}/edit',[StudentController::class,'editStudent'])->name('student.edit');
        route::put('/student/{id}/update',[StudentController::class,'updateStudent'])->name('updatestudent'); 
    
        route::get('/student/{id}/grades',[StudentController::class,'viewGrades'])->name('viewGrades');
        
    
    
        
        Route::post('/import-excel', [ProgramController::class, 'import'])->name('import.excel');
        
    
        Route::post('/import-excelStudent', [StudentController::class, 'import'])->name('import.excelStudent');
        
      
    
        Route::get('/users/create', [StudentController::class, 'create'])->name('users.create');
        Route::post('/admin/users/store', [StudentController::class, 'store'])->name('users.store');
        
        Route::get('/teachers/create', [StudentController::class, 'teacherscreate'])->name('users.teacher');
        Route::post('/admin/teachers/store', [StudentController::class, 'teachersstore'])->name('teachers.store');
        
    
    });
   
    
    Route::get('/grades', [GradeController::class, 'index'])->name('addgradesubmission');
   
    
    
    

    route::get('/dashboard',[CurriculumController::class,'index'])->name('dashboard');
});
