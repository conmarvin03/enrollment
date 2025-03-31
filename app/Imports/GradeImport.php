<?php

namespace App\Imports;
use App\Models\Grades;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log; // Import Log facade

class GradeImport implements ToModel, WithHeadingRow
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {  
        $user = Auth::user();
        $idsss = Auth::id();

        var_dump($row);
        return Grades::updateOrCreate(
            [ 'gsID' => $row['gsid'] ], // Ensure 'gsID' is unique
            [
                'grade' => $row['grade'],
                'remark' => $row['remark'],
                'name' => $row['name'],
                'kldID' => $row['kldid'], // FIXED
                'tID' => $idsss,
                'semester' => $row['semester'] ?? "",
                'year' => $row['year'] ?? "",
                'section' => $row['section'] ?? "",
                'status' => $row['status'] ?? ""
            ]
        );
        
    }
}

