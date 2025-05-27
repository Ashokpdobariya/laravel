<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class SaveTimerDataAuto extends Controller
{
    public function saveTimerData(Request $request){
        $userId = auth()->id();
        //if(!$userId) return;
        $todayDate = $request ->date ? $request ->date :Carbon::today();
        $tasks = Task::where('task',$request->task)
            ->where('project_name',$request->project_name)
            //->where('user',$userId)
            ->whereDate('date', $todayDate)
            ->first();
            if(!isset($tasks)){
                $data = $request->all();
                $task = Task::create($data);
                 return response()->json([
                    'success'=>true,
                        'total_time'=>$task->total_time
                ]);
            };
            if(isset($tasks)){
                   $task_id = $tasks->id;
                   $toaltimeinbackend = $tasks->total_time;
                   $total_time = $this->updateTotalTime($request->total_time,$tasks->total_time);

                    $time_interval = $tasks->time_interval;
                    $time_interval=$tasks->time_interval.','.$request->start_time.' - '.$request->end_time;
                    $update = Task::where('id',$task_id)->update([
                        'total_time' =>  $total_time,
                        'end_time' => $request->end_time,
                        'time_interval'=>$time_interval
                    ]);
                    if(isset($update)){
                        return response()->json([
                        'success'=>true,
                        'total_time'=>$tasks->total_time,
                        'start_time'=>$request->start_time,
                        'end_time'=>$request->end_time,
                        'time_interval'=>$tasks->time_interval
                        ]);
                    }
            }
    }
    public function updateTotalTime($time1,$time2){
        $base = Carbon::createFromTimeString('00:00:00');
        $sec1 = $base->diffInSeconds(Carbon::createFromTimeString($time1));
        $sec2 = $base->diffInSeconds(Carbon::createFromTimeString($time2));
        $totalSeconds = $sec1 + $sec2;
        return gmdate('H:i:s', $totalSeconds);
    }
}