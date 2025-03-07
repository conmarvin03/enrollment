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
        $existingEmails = []; // Track emails within the Excel file
        $skippedEmails = []; // Store skipped duplicate emails

        foreach ($rows as $row) {
            try {
                // Validate required fields
                if (!isset($row['email'], $row['kldnum']) || empty(trim($row['email']))) {
                    throw new Exception("Missing required fields: Email or kldnum is empty.");
                }

                $email = strtolower(trim($row['email']));
                $kldnum = trim($row['kldnum']);

                // Skip duplicate emails within the Excel file
                if (in_array($email, $existingEmails)) {
                    $skippedEmails[] = $email;
                    continue; // Skip this record
                }

                // Add email to tracking array
                $existingEmails[] = $email;

                // Skip if email already exists in database
                if (User::where('email', $email)->exists()) {
                    $skippedEmails[] = $email;
                    continue; // Skip this record
                }

                // Insert or update User
                User::updateOrCreate(
                    ['kldID' => $kldnum, 'email' => $email], // Find by kldID & email
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

                // Insert or update Student record
                Students::updateOrCreate(
                    ['kldID' => $kldnum, 'email' => $email], // Find by kldID & email
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

            } catch (Exception $e) {
                // Log error but continue processing
                $skippedEmails[] = $email;
            }
        }

        // Show message after import is completed
        if (!empty($skippedEmails)) {
            throw new Exception("Import completed, but some emails were skipped: " . implode(", ", $skippedEmails));
        }
    }
}
