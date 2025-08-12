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
    
        Route::get('/get-subjects-by-program/{programId}', [GradeController::class, 'getSubjectsByProgram']);
        Route::get('/get-sections-by-program/{programId}', [GradeController::class, 'getSectionsByProgram']);
        Route::get('/grades', [GradeController::class, 'index'])->name('addgradesubmission');
        
        
        route::get('/printgradesheet/{Gradesubmissions}/',[GradeController::class,'printggs'])->name('printggs');
        route::put('/submission/{id}/edit',[GradeController::class,'editgrades'])->name('editgrades'); 
        route::put('/grades/{Gradesubmissions}/update',[GradeController::class,'updategrades'])->name('updategrades');  
        route::post('/add-grades',[GradeController::class,'addgradesubmit'])->name('addgradesubmit');
        route::post('/import-grades', [GradeController::class, 'import'])->name('importGrades');
      
        
        route::get('/grades/{Gradesubmissions}/edit',[GradeController::class,'gradesview'])->name('grades.edit');
        Route::put('/editgrades/bulk/{id}', [GradeController::class, 'bulkEdit'])->name('editgrades.bulk');


       
        
        
    });
    
    Route::middleware([CheckRole::class . ':capi'])->group(function () {
        // Route::get('/asd', [GradeController::class, 'index'])->name('addgradesubmission');
    
        route::get('/schedule',[StudentController::class,'viewSchedule'])->name('schedule'); 
        Route::post('/import-schedule', [StudentController::class, 'importschedule'])->name('import.schedule');
    });



    Route::middleware([CheckRole::class . ':Student'])->group(function () {
        // Route::get('/asd', [GradeController::class, 'index'])->name('addgradesubmission');
    
        route::get('/show-grades',[StudentController::class,'showGrades'])->name('show.grades');
        
        Route::get('/printcog',[ProgramController::class,'printcog'])->name('printcog');
       
        route::post('/enrollnxtsem',[StudentController::class,'enrollnxtsem'])->name('enrollnxtsem');  
        
    });

    Route::middleware([CheckRole::class . ':'])->group(function () {

        
        
        Route::put('/admingrades/edit-all', [GradeController::class, 'adminbulkEdit'])->name('admingrades.bulk');
        Route::put('/adminsection/edit-all', [GradeController::class, 'adminbulkEditSection'])->name('adminseciton.bulk');
        Route::get('/grade/admin', [GradeController::class, 'geditadmin'])->name('admingrade');
    
        Route::get('/sectioning', [ProgramController::class, 'sectioning'])->name('changesection');
        Route::get('/programs',[ProgramController::class,'index'])->name('programs');
        route::post('/addprograms',[ProgramController::class,'addprogram'])->name('addprograms');
    
        route::post('/addcurriculums',[ProgramController::class,'addcurriculums'])->name('addcurriculums');
        route::get('/program/{program}/edit',[ProgramController::class,'editprogram'])->name('program.edit');
        route::put('/program/{program}/update',[ProgramController::class,'updateprogram'])->name('updateprogram');
        route::get('/course/{curriculum}/edit',[ProgramController::class,'editcourse'])->name('course.edit'); 
        route::put('/course/{curriculum}/update',[ProgramController::class,'updatecourse'])->name('updatecourse');    
    
        route::post('/editsettings',[GradeController::class,'editsettings'])->name('editsettings');  
        route::post('/editcor',[GradeController::class,'editcor'])->name('editcor');  
       
        route::put('/curriculum/{curriculum}/update',[ProgramController::class,'updateStatus'])->name('updateStatus');
    
        route::get('/settings',[ProgramController::class,'settings'])->name('settings');
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
        
        Route::get('/get-curriculum/{pID}', [StudentController::class, 'getCurriculum'])->name('get.curriculum');
        Route::get('/enroll/student', [StudentController::class, 'enrollStudent'])->name('enroll.student');
        
        Route::post('/grade/enroll', [StudentController::class, 'gradeEnroll'])->name('grade.enroll');
    });
   
    

    route::get('/dashboard',[CurriculumController::class,'index'])->name('dashboard');
});
