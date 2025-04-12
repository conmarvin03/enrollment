<?php 

namespace App\Imports;

use App\Models\Grades;
use App\Models\Gradesubmissions;
use App\Models\Students;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradeImport implements ToCollection, WithHeadingRow
{
    protected $ids;
    protected $semester;
    protected $year;
    protected $section;
    protected $tID;
    protected $subject;

    public function __construct($id, $semester, $year, $section, $tID, $subject)
    {
        $this->ids = $id;
        $this->tID = $tID;
        $this->semester = $semester;
        $this->year = $year;
        $this->section = $section;
        $this->subject = $subject;
    }
    public function collection(Collection $rows)
    {$seenKldIDs = [];
        $errors = [];
        
        foreach ($rows as $row) {
            $kldID = $row['kldid'];
            $grade = $row['grade'];
            $inputRemark = strtolower(trim($row['remark']));
        
            // 🔁 Prevent duplicate rows in the uploaded file
            if (in_array($kldID, $seenKldIDs)) {
                $errors[] = "Duplicate KLD Number in file: " . $kldID;
                continue;
            }
            $seenKldIDs[] = $kldID;
        
            // 🔍 Check if student exists
            $student = Students::where('kldID', $kldID)->first();
            if (!$student) {
                $errors[] = "No student found for KLD Number: " . $kldID;
                continue;
            }
        
            // ✅ Determine remark based on grade
            $remarks = ($grade == 5.0) ? "Failed" : "Passed";
        
            // 💾 Update the grade if matching record exists
            Grades::where('kldID', $kldID)
                ->where('subject', $this->subject)
                ->where('year', $this->year)
                ->where('section', $this->section)
                ->where('tID', $this->tID)
                ->update([
                    'grade' => $grade,
                    'remark' => $remarks,
                    'status' => ''
                ]);
        }
        
        // ✅ Update the submission status
        Gradesubmissions::where('id', $this->ids)->update(['status' => 'Initial']);
        
        // ⚠️ Show errors if there are any
        if (!empty($errors)) {
            throw new \Exception(implode("\n", $errors));
        }
    }        
}
