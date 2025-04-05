<?php

namespace App\Imports;

use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Students;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $existingEntries = []; // Track kldnum and emails within the Excel file
        $duplicateEntries = []; // Store duplicate kldnum or email
        $importData = []; // Store valid data for import

        // First pass: Check for duplicates within the file
        foreach ($rows as $row) {
            $email = strtolower(trim($row['email'] ?? ''));
            $kldnum = trim($row['kldnum'] ?? '');

            if (empty($email) || empty($kldnum)) {
                $duplicateEntries[] = ['kldnum' => $kldnum, 'email' => $email, 'reason' => 'Missing required fields'];
                continue;
            }

            if (isset($existingEntries[$email]) || isset($existingEntries[$kldnum])) {
                $duplicateEntries[] = ['kldnum' => $kldnum, 'email' => $email, 'reason' => 'Duplicate entry within file'];
                continue;
            }

            $existingEntries[$email] = true;
            $existingEntries[$kldnum] = true;

            // Collect valid data
            $importData[] = $row;
        }

        // If duplicates exist within the file, prevent import and return an error
        if (!empty($duplicateEntries)) {
            return back()->with('error', 'Import failed due to duplicate or invalid entries.')->with('details', $duplicateEntries);
        }

        // Proceed with updating or creating records, skipping database duplicate check
        foreach ($importData as $row) {
            $email = strtolower(trim($row['email']));
            $kldnum = trim($row['kldnum']);

            // Update or create in User table
            User::updateOrCreate(
                ['kldID' => $kldnum, 'email' => $email],
                [
                    'name' => trim($row['firstname']).' '.trim($row['middlename']).' '.trim($row['lastname']),
                    'fName' => trim($row['firstname']),
                    'lName' => trim($row['lastname']),
                    'mName' => trim($row['middlename']),
                    'email' => $email,
                    'password' => Hash::make($kldnum . trim($row['lastname'])),
                    'role' => 'Student',
                    'pID' => trim($row['pid']),
                    'kldID' => $kldnum,
                    'img' => '',
                    'gender' => trim($row['gender']),
                    'bday' => trim($row['bday']),
                    'address' => trim($row['address']),
                ]
            );

            // Update or create in Students table
            Students::updateOrCreate(
                ['kldID' => $kldnum, 'email' => $email],
                [
                    'fName' => trim($row['firstname']),
                    'lName' => trim($row['lastname']),
                    'mName' => trim($row['middlename']),
                    'email' => $email,
                    'role' => 'Student',
                    'pID' => trim($row['pid']),
                    'kldID' => $kldnum,
                    'img' => '',
                    'gender' => trim($row['gender']),
                    'bday' => trim($row['bday']),
                    'address' => trim($row['address']),
                ]
            );
        }

        return back()->with('success', 'Excel file imported and records updated successfully.');
    }
}
