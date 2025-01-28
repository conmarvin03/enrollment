<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Curriculums extends Model
{
    use HasFactory;

    protected $fillable = [
       'pID','courseCode','course','type', 'semester','years','unit','leclab'
    ];
}
