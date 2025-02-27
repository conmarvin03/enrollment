<?php

namespace App\Imports;

use App\Models\Curriculums;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class CurriculumImport implements ToModel, WithHeadingRow
{
    protected $id;

    // Constructor to receive the extra parameter
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function model(array $row)
    {
        Log::info('Processing Row:', $row);

        return  Curriculums::updateOrCreate(
            [ 'pID' => $this->id], // Find by courseCode
            ['pID'=>$this->id,
                'courseCode' => $row['coursecode'],
            'course' => $row['course'],
            'type' => $row['type'],
            'semester' => $row['semester'],
            'years' => $row['years'],
            'unit' => $row['unit'],         
            'leclab' => $row['leclab'],  
            ]
                     
        );
    }
}

?>