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
 public  function viewTaskList(Request $request){
      //echo $request->all();
      $page = $request->currentPage;
      $perPage = $request->rawperPage;
      $skip = ($page - 1) * $perPage;

      $tasks = Task::skip($skip)->take($perPage)->orderBy('date', 'desc')->get();
      
      return response()->json([
                        'success'=>true,
                        'pagination'=>$request->all(),
                        
                        ]);
 }
}