<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    //
    use HasFactory;

    // The table associated with the model.
    protected $table = 'tasks';

    // The attributes that are mass assignable.
    protected $fillable = [
        'task',
        'project_name',
        'start_time',
        'end_time',
        'total_time',
        'date',
        'user'
    ];
}
