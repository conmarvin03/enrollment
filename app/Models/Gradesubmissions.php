<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gradesubmissions extends Model
{
    use HasFactory;

    protected $fillable = [
       'gsID','gradeName','section','gsID', 'semester','coursecode','year','status','tID','room','timestart','timeend','day','program'
    ];
}
