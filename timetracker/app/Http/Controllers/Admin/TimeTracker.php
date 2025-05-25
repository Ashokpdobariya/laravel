<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use App\Http\Controllers\Admin\Pagination;
use Illuminate\Support\Facades\Storage;



class TimeTracker extends Controller
{
    protected $pagination;
    public $page ;
    public $perPage;
      public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;
        $this->page =4;
        $this->perPage =10;
    }
   
    public function showMessage()
    {
        $this->page = floor($this->setListPerPage() / $this->perPage);
        $daywisedata    = $this->getTimeTrackerData($perPage=10 ,$page=1);
        $dataeWiseTotal = $this->calcaluteDateWiseTime($perPage=10 ,$page=1);
        $weekWisetotal  = $this->calcaluteWeeklyWiseTime($perPage=10 ,$page=1);
        $totalTaskRaw    = $this->setListPerPage();
        
        return view('admin.custom.view_tracker',["data"=>$daywisedata,"dataeWiseTotal"=>$dataeWiseTotal,"weekWisetotal"=>$weekWisetotal,"totalRaw"=>$totalTaskRaw]);
        
       
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
                    $daywisedata = $this->getTimeTrackerData($perPage=10 ,$page=1);
                    $dataeWiseTotal = $this->calcaluteDateWiseTime($perPage=10 ,$page=1);
                    $weekWisetotal  = $this->calcaluteWeeklyWiseTime($perPage=10 ,$page=1);
                    $totalTaskRaw    = $this->setListPerPage();
                    return redirect()->route('view.timetracker')->with('data',$daywisedata)->with("dataeWiseTotal",$dataeWiseTotal)->with("weekWisetotal",$weekWisetotal)->with("totalRaw",$totalTaskRaw);
               }
        }
    }
    public function calculte_time($start_time,$end_time){
        $startTime = Carbon::createFromFormat('H:i:s', $start_time);
        $endTime = Carbon::createFromFormat('H:i:s', $end_time);
        $diff = $startTime->diff($endTime)->format('%H:%I');
        return $diff;
    }
    public function getTimeTrackerData($perPage ,$page){
        $tasks = $this->getTaskData($perPage,$page);
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
    public function calcaluteDateWiseTime($perPage ,$page){
        $tasks = $this->getTaskData($perPage,$page);
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
    public function calcaluteWeeklyWiseTime($perPage ,$page){
        $tasks = $this->getTaskData($perPage,$page);
        
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
    public function getTaskData($perPage ,$page){
        //$count = $this->page;
         $skip = ($page - 1) * $perPage;

        $tasks = Task::skip($skip)->take($perPage)->orderBy('date', 'desc')->get();
       // $tasks =Task::limit($count)->orderBy('date', 'desc')->get();
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
    //for pagination 
    public  function viewTaskList(Request $request){
     
      $totalpage = $request->currentPage;
      $perPageList = $request->rawperPage;
      $skip = ($totalpage - 1) * $perPageList;

        //$tasks = Task::skip($skip)->take($perPageList)->orderBy('date', 'desc')->get();
        $page = $totalpage;
        $perPage = (int)($perPageList);
    
        $daywisedata    = $this->getTimeTrackerData($perPage ,$page);
        $dataeWiseTotal = $this->calcaluteDateWiseTime($perPage ,$page);
        $weekWisetotal  = $this->calcaluteWeeklyWiseTime($perPage ,$page);
        $com = [
                        'success'=>true,
                        'pagination'=>$request->all(),
                        'task'=>json_encode($daywisedata,true),
                        'dateWiseTotal' =>json_encode($dataeWiseTotal,true),
                        'weekWisetotal' =>json_encode($weekWisetotal,true)
                       
        ];
      return response()->json($com);
    }
    public  function setListPerPage(){
        return Task::count();
    }
    public function uploadScreenShot(Request $request)
    {
        
        $image = $request->input('image');

        if (!$image) {
            return response()->json(['message' => 'No image provided'], 400);
        }

        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $filename = 'screenshot_' . time() . '.png';
        Storage::disk('public')->put($filename, $imageData);

        return response()->json(["message" => "Screenshot saved!", "file" => $filename]);
    }
}
