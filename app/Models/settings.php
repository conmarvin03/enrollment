<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class settings extends Model
{
    use HasFactory;

    protected $fillable = [
       'academicyear','semester','year'
     ];
}
