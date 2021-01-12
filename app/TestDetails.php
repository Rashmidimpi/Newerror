<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestDetails extends Model
{
    //
    protected $fillable = [
        'test_name',
        'board',
        'class',
        'subject',
        'level',
        'module',
        'duration',
        'marks',
        'datetime',
        'no_of_question',
        'created_by',
        'is_avilable',
    ];
}
