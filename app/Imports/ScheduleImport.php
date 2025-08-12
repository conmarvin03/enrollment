<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Students;
use App\Models\Curriculums;
use App\Models\Settings;
use App\Models\Grades;
use App\Models\Gradesubmissions;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ToModel;

class ScheduleImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {

        $settings=Settings::where('id',1)->first();
     Grades::where(['section' => $row['section']
            ,'gsID'=>$row['program'],
            'subject'=>$row['coursecode'],
            'year'=>$settings->year,
            'semester'=>$settings->semester
        ])->update([
        'tID'=>$row['teacherid']
        ]);
        return Gradesubmissions::updateOrCreate(
            ['section' => $row['section']
            ,'gsID'=>$row['program'],
            'coursecode'=>$row['coursecode'],
            'year'=>$settings->year,
            'semester'=>$settings->semester
        
        ], // Unique column to check
            ['gradeName'=>'CAPI'.$settings->semester.' - '.$settings->semester ,
              'section' => $row['section']
            ,'gsID'=>$row['program'],
            'coursecode'=>$row['coursecode'],
            'tID'=>$row['teacherid'],
            'room'=>$row['room'],
            'timestart'=>$row['timestart'],
            'timeend'=>$row['timeend'],
            'day'=>$row['day'],
            'year'=>$settings->year,
            'semester'=>$settings->semester,
            'status'=>'', 'program'=>''

            ]
        );
      
        return back()->with('success', 'Schedule imported successfully!');
    
    }
}
