<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use App\Http\Controllers\Admin\Pagination;

class TimeTracker extends Controller
{
    protected $pagination;
      public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }
   
    public function showMessage()
    {
        
        $daywisedata    = $this->getTimeTrackerData();
        $dataeWiseTotal = $this->calcaluteDateWiseTime();
        $weekWisetotal  = $this->calcaluteWeeklyWiseTime();
        return view('admin.custom.view_tracker',["data"=>$daywisedata,"dataeWiseTotal"=>$dataeWiseTotal,"weekWisetotal"=>$weekWisetotal]);
        
       
    }
    public function savetime(Request $request)
    {
        
        $validatedData = $request->validate([
            'task' => 'required|string',
            'project_name' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'date' => 'required',
            
        ]);
        $data = $request->all();
         $userId = auth()->id();
        if(isset($data)){
                $task = Task::create($data);
               if($task){
                    $daywisedata = $this->getTimeTrackerData();
                    $dataeWiseTotal = $this->calcaluteDateWiseTime();
                    $weekWisetotal  = $this->calcaluteWeeklyWiseTime();
                    return redirect()->route('view.timetracker')->with('data',$daywisedata)->with("dataeWiseTotal",$dataeWiseTotal)->with("weekWisetotal",$weekWisetotal);
               }
        }
    }
    public function calculte_time($start_time,$end_time){
        $startTime = Carbon::createFromFormat('H:i:s', $start_time);
        $endTime = Carbon::createFromFormat('H:i:s', $end_time);
        $diff = $startTime->diff($endTime)->format('%H:%I');
        return $diff;
    }
    public function getTimeTrackerData(){
        $tasks = $this->getTaskData();
        if(isset($tasks)){
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
        return $daywisedata;
        }
    }
    public function calcaluteDateWiseTime(){
        $tasks = $this->getTaskData();
        $groupedByDate = $tasks->groupBy('date');

            $totalsByDate = [];
            
            foreach ($groupedByDate as $date => $entries) {
            
                $totalSeconds = 0;
                foreach ($entries as $entry) {
                    $timeParts = explode(':', $entry->total_time); // "02:00:00"
                    $hours = (int) $timeParts[0];
                    $minutes = (int) $timeParts[1];
                    $seconds = (int) $timeParts[2];
                    $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                }
                $formatted = $this->formatSecond($totalSeconds);//convert seconds to HH:MM:SS
                $dateformat = Carbon::parse($date);
                $dateRange = $dateformat->format('D, M j');
                $totalsByDate[$dateRange] = $formatted; // converts total seconds back to HH:MM:SS
            }
            //dd($totalsByDate);
            return $totalsByDate;
            
    }
    public function calcaluteWeeklyWiseTime(){
        $tasks = $this->getTaskData();
        $weeklyTotals = [];

        foreach ($tasks as $log) {
            $date = Carbon::parse($log->date);
            $startOfWeek = Carbon::now()->setISODate($date->year, $date->weekOfYear)->startOfWeek(); // Monday
            $endOfWeek = $startOfWeek->copy()->endOfWeek(); // Sunday
            $week = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d');
            [$hours, $minutes, $seconds] = explode(':', $log->total_time);
            $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
            if (!isset($weeklyTotals[$week])) {
                $weeklyTotals[$week] = 0;
            }
            $weeklyTotals[$week] += $totalSeconds;
        }
        foreach ($weeklyTotals as $week => $seconds) {
                $formatted = $this->formatSecond($seconds);//convert seconds to HH:MM:SS
                $weeklyTotals[$week] = $formatted;
        }
          //dd($weeklyTotals); // or return/view as needed
        return $weeklyTotals;
      
    }
    public function getTaskData(){
        $count = $this->pagination->listPerPage;
        $tasks =Task::limit($count)->orderBy('date', 'desc')->get();
        if(isset($tasks)){
            return $tasks;
        }
    }
    public function formatSecond($seconds){
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $remainingSeconds = $seconds % 60;
                $formatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
                return  $formatted;
    }

}
