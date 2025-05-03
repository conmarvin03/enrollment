<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grades extends Model
{
    use HasFactory;

    protected $fillable = [
       'gsID','grade','remark','kldID', 'semester','year','section','tID','years','status','subject'
    ];
}
