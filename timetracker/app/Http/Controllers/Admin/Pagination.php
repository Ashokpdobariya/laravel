<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class Pagination extends Controller
{
 public $listPerPage =10;
 
 public static function setListPerPage(){
    return Task::count();
 }
}