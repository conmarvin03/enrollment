<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Programs extends Model
{
    use HasFactory;

    protected $fillable = [
       'acc','program','status'
    ];
}
