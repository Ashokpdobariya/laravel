<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class TimeTracker extends Controller
{
    public function showMessage()
    {
        $tasks = Task::all();
        if(isset($tasks)){
            
            return view('admin.custom.view_tracker',["tasks"=>$tasks]);
        }
        //return view('admin.custom.view_tracker');// Create this view
    }

    public function savetime(Request $request)
    {
        // handle logic here
        $validatedData = $request->validate([
            'task' => 'required|string',
            'project_name' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'date' => 'required',
            
        ]);
        $data = $request->all(); 
        if(isset($data)){
            //$task = Task::create($data);
            $tasks = Task::limit(10)->orderBy('created_at', 'asc')->get();

            $monthlyData = [];
            $weeklydat = [];
            $daywisedata =[];
            foreach ($tasks  as $key => $value ) {
                $date = Carbon::parse($value->date);
                
                $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY); // Keep as Carbon object
                $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);     // Keep as Carbon object
                
                $weeklyRange = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d');
                $dateRange = $date->format('D, M j');
                $daywisedata[$weeklyRange][$dateRange][] =[
                    'id'=> $value->id,
                    'task'=> $value->task,
                    'project_name' => $value->project_name,
                    'start_time' => $value->start_time,
                    'end_time' => $value->end_time,
                    'day_total_time' => $value->total_time,
                    'date'=> $value->date
                ];
            };
         
            $date_Key =[];
            $week_key =[];
            foreach ($daywisedata as $week => $day ) {
                
                array_push($week_key, $week);
                foreach($day as $date => $d){
                    array_push($date_Key, $date);
                }
            };
            
            echo "<pre>";
            echo print_r( $date_Key,true) ;
            echo "<pre>";
                echo print_r($week_key ,true);
            


        //    echo "<pre>";
        //    echo print_r($daywisedata,true) ;
            
            // echo "<pre>";
            // echo print_r($daywisedata,true) ;
           //return view('admin.custom.view_tracker',["tasks"=>$daywisedata]);
            return redirect()->route('view.timetracker')->with('taskdata',json_encode($daywisedata));
        }
       
        //return redirect()->back()->with('success', 'Form submitted!');
    }
    public function calculte_time($start_time,$end_time){
        $startTime = Carbon::createFromFormat('H:i:s', $start_time);
            $endTime = Carbon::createFromFormat('H:i:s', $end_time);
            $diff = $startTime->diff($endTime)->format('%H:%I');
            return $diff;
    }
}
