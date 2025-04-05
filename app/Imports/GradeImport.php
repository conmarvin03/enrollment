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
    {
        $seenKldIDs = [];
        $errors = [];
    
        // 🧹 STEP 1: Delete current grades for this gsID only
        Grades::where('gsID', $this->ids)->delete();
    
        foreach ($rows as $row) {
            $kldID = $row['kldid'];
            $grade = $row['grade'];
            $inputRemark = strtolower(trim($row['remark']));
    
            // 🔁 STEP 2: Prevent duplicate rows in the uploaded file
            if (in_array($kldID, $seenKldIDs)) {
                $errors[] = "Duplicate KLD Number in file: " . $kldID;
                continue;
            }
            $seenKldIDs[] = $kldID;
    
            // 🔍 STEP 3: Check if student exists
            $student = Students::where('kldID', $kldID)->first();
            if (!$student) {
                $errors[] = "No student found for KLD Number: " . $kldID;
                continue;
            }
    
            $studentName = $student->fName . ' ' . $student->mName . ' ' . $student->lName;
    
            // 🔒 STEP 4: Check existing grades (excluding current gsID)
            $existingOther = Grades::where('kldID', $kldID)
                ->where('subject', $this->subject)
                ->where('gsID', '!=', $this->ids)
                ->orderByDesc('id')
                ->first();
    
            if ($existingOther) {
                $existingRemark = strtolower($existingOther->remark);
    
                if ($existingRemark === 'passed') {
                    // ❌ Don't allow import if student already passed in another gsID
                    $errors[] = "KLD Number {$kldID} already passed this subject under a different record. Import skipped.";
                    continue;
                }
            }
    
            // ✅ STEP 5: Determine new remark
            $remarks = ($grade == 5.0) ? "Failed" : "Passed";
    
            // 💾 STEP 6: Save new grade
            Grades::create([
                'kldID' => $kldID,
                'gsID' => $this->ids,
                'grade' => $grade,
                'remark' => $remarks,
                'name' => $studentName,
                'tID' => $this->tID,
                'subject' => $this->subject,
                'semester' => $this->semester,
                'year' => $this->year,
                'section' => $this->section,
                'status' => ""
            ]);
        }
    
        // ✅ STEP 7: Update submission status
        Gradesubmissions::where('id', $this->ids)->update(['status' => 'Initial']);
    
        // ⚠️ STEP 8: Show all error messages via SweetAlert
        if (!empty($errors)) {
            throw new \Exception(implode("\n", $errors));
        }
    }
    
    
}
