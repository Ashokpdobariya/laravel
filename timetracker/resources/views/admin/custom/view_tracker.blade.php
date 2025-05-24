@extends(backpack_view('blank'))

@section('content')
    <h2>Time Tracker</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<head>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
</head>

@php
    $jsonData = json_encode($data);
    $jsonWeekWiseTotal = json_encode($weekWisetotal);
    $jsonDateWiseTotal = json_encode($dataeWiseTotal);
@endphp


<!-- <div class ="time-add-wrapper" x-data ="TimetrackerFun()" x-init=" h()" style="background-color:#F2F6F5 !important;" >
        <div x-show = "open_timer == false" class = "add_timer_wrapper">
            <form action="{{ url('admin/time-submit') }}" method="POST">
                @csrf
                    <div class= "d-flex  bg-white shadow flex-fill" >
                        <div class="p-2 w-25 ">
                            <input type="text" x-model = "task" name="task" class="form-control" placeholder="Enter something">
                        </div>
                        <div class="p-2 d-flex">
                        <div  class = "p-2 flex-fill  cursor-pointer">
                                        <img width ="30" src="{{ asset('images/plus-blue-req.svg') }}" alt="Logo" class="img-fluid">
                                    </div>
                            <select name="project_name" id="project_name" x-model = "project_name" class="form-control">
                                <option value =''>Project </option>
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                        </div>
                        <div class="p-2 ">
                            <input @change ="end_time != null ? calculateTimeDifference():''"  x-model ="start_time" type="time" name="start_time" id="start_time" class="form-control" value="start_time">
                        </div>
                        <div class="p-2 ">
                            <input @change ="calculateTimeDifference()" x-model ="end_time" type="time" name="end_time" id="end_time" class="form-control" value="end_time">
                        </div>
                        <div class="p-2 ">
                            <input type="date" name="date" id="date" x-model= "date" class="form-control" value="{{ old('start_time') }}">
                        </div>
                        <div class="p-2 ">
                            <input  x-model ="total_time" type="text" name="total_time" id="total_time" class="form-control" value="total_time">
                        </div>
                        <div class="p-2">
                            <button type="submit" class="btn btn-primary ">Add</button>
                        </div>
                        <div class="p-2 d-flex flex-column ms-2">
                            <div  class = " cursor-pointer " @click ="open_timer = true;getTodaysdate()">
                                <img src="{{ asset('images/clock-blue.svg') }}" alt="Logo" class="img-fluid">
                            </div>
                            <div class = "cursor-pointer">
                                <img  src="{{ asset('images/list-gray.svg') }}" alt="Logo" class=" img-fluid">
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    
        <div class="all-data-viewer mt-6" >
            <div class ="inner-main " style="background-color:#F2F6F5 !important;" >
                @foreach($data as $week => $days)
                    <div class = "d-flex justify-content-between mx-2 mt-2 ">
                        <div class ="ms-1 p-1 fw-bold">{{$week}}</div>
                        <div class ="ms-1 p-1 fw-bold">{{$weekWisetotal[$week]}}</div>
                    </div>
                    <div class="d-flex flex-column bg-white mt-1 ms-1 p-2 w-100">
                        @foreach($days as $date => $taskdata)
                            <div class ="d-flex  w-100">
                                <div class="ms-1 p-2 w-100 " style ="background-color:#e4eaee" >
                                    <div class="d-flex justify-content-between">
                                        <div class ="ms-1 p-1 fw-bold">{{ $date }}</div>
                                        <div class ="ms-1 p-1 fw-bold">{{$dataeWiseTotal[$date]}}</div>
                                    </div>
                                    @foreach($taskdata as $task )
                                        <div class ="d-flex bg-white p-1 mt-1 ">
                                            <div class ="d-flex  w-70 flex-fill " style="background-color:#F2F6F5 !important;">
                                                <div class="ms-1 p-2  " style ="background-color:#e4eaee">{{$task['task'] }}</div> 
                                                <div class=" p-2 ">{{$task['project_name'] }}</div>
                                            </div>
                                            <div class="d-flex ">
                                                <div class=" p-2 flex-fill ">{{$task['start_time'] }}</div>
                                                <div class=" p-2 flex-fill ">{{$task['end_time'] }}</div>
                                                <div class=" p-2 flex-fill ">{{$task['date'] }}</div>
                                                <div class=" p-2 flex-fill ">{{$task['day_total_time'] }}</div>
                                                <div  class = " p-2 flex-fill  cursor-pointer" @click = "editTimerData(`{{$task['task']}}`,`{{$task['project_name']}}`,`{{$task['date']}}`)">
                                                    <img src="{{ asset('images/play.svg') }}" alt="Logo" class="img-fluid">
                                                </div>
                                                <div  class = "p-2 flex-fill  cursor-pointer">
                                                    <img src="{{ asset('images/locked.svg') }}" alt="Logo" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div x-cloak x-show = "open_timer == true"
                class = "timer_popup shadow-lg border bg-white position-fixed bottom-0 end-0  mb-6 me-6 min-vh-50 w-25 z-3  p-2" 
                style ="min-height : 500px;background-color:#F2F6F5 !important;box-shadow: 2px 2px 8px rgba(0, 0, 0, 5) !important;"
            >
           
                <div class = "tracker_wrapper">
                    <div class = "fw-bold fs-1 text-center text-shadow" style ="text-shadow: 1px 1px 3px rgba(0,0,0,0.3)"> 
                        Time - Tracker
                    </div>
                    
                         <div class="p-1">
                            <p>Date</p>
                            <input type="date" name="date" id="date" x-model= "date" class="form-control" value="date" />
                        </div>
                        
                        <div class="p-1 w-100 ">
                            <p>Task</p>
                            <input type="text" name="task"  x-model ="task" class="form-control" placeholder="Enter something">
                        </div>
                        <div  class = "p-1  cursor-pointer">
                            <p>Project Name</p>
                            <select name="project_name" id="project_name" x-model ="project_name" class="form-control">
                                <option value =''>Project </option>
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                        </div>
                         <div class="p-1 fw-bold">
                            <p>Total Time</p>
                            <input  readonly x-model ="total_time" type="text" name="total_time" id="total_time" class="form-control" value="total_time">
                        </div>
                    </div>
                    <div  class = "input d-flex flex-column mt-2 ">
                        <div
                             @click = "closeTimer()" 
                             class = "btn btn-primary mt-2 fw-bold"
                             :style=" timer_start || timer_stop ? 'background-color: #ffc107;color:black' : '' "
                             x-show =" !timer_start || timer_stop " 
                             >
                             Close
                        </div>
                        <div 
                            @click ="stopTimer()"
                            class = "btn btn-primary mt-2 fw-bold"
                            :style="timer_start ? 'background-color: red;color:black' : '' "
                            >
                            Stop
                        </div>
                        <div @click = "startTimer()" 
                        x-show = "timer_start == false"
                            class = "btn btn-primary mt-2 fw-bold" 
                            :disabled ="timer_start == true"
                            :style="timer_start == false ? 'background-color: green; color:black' : '' ">
                            Start Timer
                        </div>
                    </div>
                </div>
            </div>
</div> -->
<!-- pagination -->
 

 <!-- <div x-data = "pagination({{ \App\Http\Controllers\Admin\Pagination::setListPerPage() }})" x-init ="calculatePage()" class ="pagination-main mt-6 ms-4   p-4 me-4">
    
    
     <div class="d-flex justify-content-between shadow p-4 bg-transparent">
        <div class =" ms-1 d-flex ">
            <div class ="d-flex justify-content-center text-center">
                <p class = "">Result Per Page </p>
                <input x-model = "rawperPage" @blur = "calculatePage()"  name ="rawperPage" class = "bg-info ms-2 w-25 text-center" />
            </div>
        </div>
        <div class ="page_wraper d-flex">
            <div class =" ms-1"><button @click ="goToPrevPage()" class = "bg-info" >Prev</button></div>
            <template x-for="page_num in countfullPage" :key="page_num">
                <div class =" ms-1" >
                    <button @click = "gotoPageNo(page_num)" x-text="page_num"  :class ="{'bg-primary text-white': currentPage == page_num}"></button>
                </div>
            </template>
            <div class =" ms-1 w-50">
                <button @click = "goToNextPage()" class = "bg-info" >Next</button>
            </div>
        </div>
        
    </div>
    
</div> -->

 <!-- end-->
<!-- <script>
    function TimetrackerFun(){
        return {
            task:null,
            project_name :null,
            start_time :null,
            end_time:null,
            total_time:null,
            open_timer:false,
            timer_start:false,
            timer_stop:false,
            data:null,
            timeoutID:null,
            date:null,
            savetimeaftertenminute:null,
            token:null,
            h(){
                console.log('called');
               
                //console.log('data',this.data)

            },
            editTimerData(task,project_name,date){
                console.log('id',task,project_name,date);
                this.open_timer = true;
                this.timer_task = task;
                this.project_name =project_name;
                this.date = date;
            }
            ,
            calculateTimeDifference() {
                    const [startHour, startMinute] =this.start_time.split(':').map(Number);
                    const [endHour, endMinute] = this.end_time.split(':').map(Number);

                    const startDate = new Date(0, 0, 0, startHour, startMinute);
                    const endDate = new Date(0, 0, 0, endHour, endMinute);

                    let diff = endDate - startDate;

                    const diffHours = Math.floor(diff / (1000 * 60 * 60));
                    const diffMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    this.total_time = diffHours + ":" + ((diffMinutes >= 0 && diffMinutes < 10 ) ? ('0' + diffMinutes) : diffMinutes);
            },
             startTimer(){
              
                 if( this.date == null || this.project_name == null || this.timer_task == null){
                    return;
                 }
                this.timer_start = true;
                this.timer_stop = false;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.start_time = this.getCurrentTime();
                console.log('start',this.start_time)
                this.timeoutID = setInterval(async () => {
                       console.log("This runs after 2 seconds");
                       this.end_time = this.getCurrentTime();
                       console.log('end',this.end_time);
                       //this.calculateTimeDifference();
                       this.savetimeaftertenminute = this.calulateDiffTimeForlivetimer();
                       var response = await fetch("{{ route('save.timerdata') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    task: this.timer_task,
                                    project_name:this.project_name,
                                    start_time:this.start_time,
                                    end_time:this.end_time,
                                    total_time: this.savetimeaftertenminute,
                                    date:this.date

                                }),
                                });
                                var data = await response.json();
                                if(data.success){
                                    this.start_time = this.getCurrentTime();
                                    this.total_time = data.total_time;
                                }
                        
                        
                }, 600000);
            },
            stopTimer(){
                this.start_time = null;
                this.end_time = null;
                clearInterval(this.timeoutID);
                this.timer_start = false;
                this.timer_stop = true;
               
            },
            closeTimer(){
                this.timer_task = null;
                this.project_name = null;
                this.date = null;
                this.total_time = null;
                this.savetimeaftertenminute = null;
                this.open_timer = false;
                this.timer_start = false;
                this.timer_stop = false;
               
            },
            getCurrentTime(){
                    const now = new Date();
                   

                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    const currentTime = `${hours}:${minutes}:${seconds}`;
                    return currentTime;
            },
            getTodaysdate(){
                
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based
                    const day = now.getDate().toString().padStart(2, '0');
                    const formattedDate = `${year}-${month}-${day}`;
                    this.date = formattedDate;
                    
            },
            calulateDiffTimeForlivetimer(){
                const [startHour, startMinute] =this.start_time.split(':').map(Number);
                    const [endHour, endMinute] = this.end_time.split(':').map(Number);

                    const startDate = new Date(0, 0, 0, startHour, startMinute);
                    const endDate = new Date(0, 0, 0, endHour, endMinute);

                    let diff = endDate - startDate;

                    const diffHours = Math.floor(diff / (1000 * 60 * 60));
                    const diffMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    return diffHours + ":" + ((diffMinutes >= 0 && diffMinutes < 10 ) ? ('0' + diffMinutes) : diffMinutes);
            }
        }
    }
    function pagination(a){
        return {
            totalRaw:a,
            
            rawperPage:10,
            countfullPage:null,
            countRawOnLastPage:null,
            startingRawOnpage:null,
            endingRawOnPage:null,
            currentPage:1,
            token:null,
            calculatePage(){
                this.countfullPage = (this.totalRaw % this.rawperPage === 0) ? Math.floor(this.totalRaw / this.rawperPage) :Math.floor(this.totalRaw / this.rawperPage)+1;
                this.countRawOnLastPage = (this.totalRaw % this.rawperPage);
                this.token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                
            },
            gotoPageNo(num){
                this.startingRawOnpage = ((num-1) * this.rawperPage) +1;
                this.endingRawOnPage = (num ==this.countfullPage) ? (this.startingRawOnpage + ( this.countRawOnLastPage-1) ):this.startingRawOnpage + (this.rawperPage -1)
                this.currentPage = num;
                this.getViewTaskList();
                
            },
            goToPrevPage(){
               this.currentPage  ==1 ? this.gotoPageNo(1) : this.gotoPageNo(this.currentPage -1);
               this.getViewTaskList();
               
            },
            goToNextPage(){
                this.currentPage  == this.countfullPage ? this.gotoPageNo(this.countfullPage) : this.gotoPageNo(this.currentPage + 1);
                this.getViewTaskList();
            },
            async getViewTaskList(){
                console.log(this.currentPage,this.startingRawOnpage,this.endingRawOnPage,this.token);
                var response = await fetch("{{ route('task.list.page') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.token,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    currentPage: this.currentPage,
                                    startingRawOnpage:this.startingRawOnpage,
                                    endingRawOnPage:this.endingRawOnPage,
                                    rawperPage:this.rawperPage
                                    

                                }),
                                });
                                var data = await response.json();
                                if(data.success){
                                    console.log("pageination",data);
                                     
                                }
            }


        }
    }
    </script> -->
<div class ="time-add-wrapper p-2" x-data ="TimetrackerFun1()"  x-init ="calculatePage()" style="background-color:#F2F6F5 !important;" >
    
    <div x-cloak x-show = "open_timer == false" class = "add_timer_wrapper">
            <form action="{{ url('admin/time-submit') }}" method="POST">
                @csrf
                    <div class= "d-flex  bg-white shadow flex-fill" >
                        <div class="p-2 w-25 ">
                            <input type="text" x-model = "timer_task" name="task" class="form-control" placeholder="Enter something">
                        </div>
                        <div class="p-2 d-flex">
                        <div  class = "p-2 flex-fill  cursor-pointer">
                                        <img width ="30" src="{{ asset('images/plus-blue-req.svg') }}" alt="Logo" class="img-fluid">
                                    </div>
                            <select name="project_name" id="project_name" x-model = "project_name" class="form-control">
                                <option value =''>Project </option>
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                        </div>
                        <div class="p-2 ">
                            <input @change ="end_time != null ? calculateTimeDifference():''"  x-model ="start_time" type="time" name="start_time" id="start_time" class="form-control" value="start_time">
                        </div>
                        <div class="p-2 ">
                            <input @change ="calculateTimeDifference()" x-model ="end_time" type="time" name="end_time" id="end_time" class="form-control" value="end_time">
                        </div>
                        <div class="p-2 ">
                            <input type="date" name="date" id="date" x-model= "date" class="form-control" value="{{ old('start_time') }}">
                        </div>
                        <div class="p-2 ">
                            <input  x-model ="total_time" type="text" name="total_time" id="total_time" class="form-control" value="total_time">
                        </div>
                        <div class="p-2">
                            <button type="submit" class="btn btn-primary ">Add</button>
                        </div>
                        <div class="p-2 d-flex flex-column ms-2">
                            <div  class = " cursor-pointer " @click ="open_timer = true;getTodaysdate()">
                                <img src="{{ asset('images/clock-blue.svg') }}" alt="Logo" class="img-fluid">
                            </div>
                            <div class = "cursor-pointer">
                                <img  src="{{ asset('images/list-gray.svg') }}" alt="Logo" class=" img-fluid">
                            </div>
                        </div>
                    </div>
            </form>
        </div><!-- addd task end-->
        
    <div class = "inside_wrapper p-2">
        <div class="all-data-viewer mt-6" >
            <div class ="inner-main " style="background-color:#F2F6F5 !important;" >
                <template x-for="([week, days], index) in Object.entries(trackdata)" :key="index">
                    <div class="mb-2">
                        <div class = "d-flex justify-content-between mx-2 mt-2 ">
                        <div class ="ms-1 p-1 fw-bold" x-text ="week"></div>
                        <div class ="ms-1 p-1 fw-bold" x-text ="weekWisetotal[week]"></div>
                        </div>
                       
                        <div class="d-flex flex-column bg-white mt-1 ms-1 p-2 w-100">
                            <template x-for="([daywaisedate,taskdata], i) in Object.entries(days)" :key="i">
                                <div class ="d-flex  w-100">
                                    <div class="ms-1 p-2 w-100 " style ="background-color:#e4eaee" >
                                        
                                        <div class="d-flex justify-content-between">
                                            <div class ="ms-1 p-1 fw-bold" x-text ="daywaisedate"></div>
                                            <div class ="ms-1 p-1 fw-bold" x-text="dataeWiseTotal[daywaisedate]"></div>
                                        </div>
                                        <template x-for="(task, ind) in taskdata" :key="ind">
                                            <div class ="d-flex bg-white p-1 mt-1 ">
                                                <div class ="d-flex  w-70 flex-fill " style="background-color:#F2F6F5 !important;">
                                                    <div class="ms-1 p-2  " style ="background-color:#e4eaee" x-text ="task.task"></div> 
                                                    <div class=" p-2 " x-text ="task.project_name"></div>
                                                </div>
                                                <div class="d-flex ">
                                                    <div class=" p-2 flex-fill " x-text ="task.start_time"></div>
                                                    <div class=" p-2 flex-fill " x-text ="task.end_time"></div>
                                                    <div class=" p-2 flex-fill " x-text ="task.date"></div>
                                                    <div class=" p-2 flex-fill " x-text ="task.day_total_time"></div>
                                                    <div  class = " p-2 flex-fill  cursor-pointer" @click = "editTimerData(task.task,task.project_name,task.date)">
                                                        <img src="{{ asset('images/play.svg') }}" alt="Logo" class="img-fluid">
                                                    </div>
                                                    <div  class = "p-2 flex-fill  cursor-pointer">
                                                        <img src="{{ asset('images/locked.svg') }}" alt="Logo" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>    
                                </div>
                            </template>
                            
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <!-- pagination end-->
 <div  class ="pagination-main mt-6 ms-4   p-4 me-4">
     <div class="d-flex justify-content-between shadow p-4 bg-transparent">
        <div class =" ms-1 d-flex ">
            <div class ="d-flex justify-content-center text-center ">
                <input class = "" style="border: none;background: none;width: 110px" type="text" value ="Result Per Page :" readonly/>
                <input x-model = "rawperPage" @blur = "currentPage=1;calculatePage();"  name ="rawperPage" class = "bg-info w-25 text-center" />
            </div>
        </div>
        <div class ="ms-1 d-flex ">
            <div class ="d-flex justify-content-center text-center">
                <input class = "" style="border: none;background: none;width: 95px" type="text" value ="Current Page :" readonly/>
                <input @change = "gotoPageNo($event.target.value)"  :value ="currentPage" name ="current_page" class = "bg-info w-25 text-center" />
            </div>
        </div>
        
        <div class ="page_wraper d-flex">
            <div class =" ms-1"><button @click ="goToPrevPage()" class = "bg-info" >Prev</button></div>
            <template x-for="page_num in countfullPage" :key="page_num">
                <div class =" ms-1" >
                    <button @click = "gotoPageNo(page_num)" x-text="page_num"  :class ="{'bg-primary text-white': currentPage == page_num}"></button>
                </div>
            </template>
            <div class =" ms-1 w-50">
                <button @click = "goToNextPage()" class = "bg-info" >Next</button>
            </div>
        </div>
    </div>
</div>
 <!-- pagination end-->
    <!-- timer-->
     <div x-cloak x-show = "open_timer == true"
                class = "timer_popup shadow-lg border bg-white position-fixed bottom-0 end-0  mb-6 me-6 min-vh-50 w-25 z-3  p-2" 
                style ="min-height : 500px;background-color:#F2F6F5 !important;box-shadow: 2px 2px 8px rgba(0, 0, 0, 5) !important;"
            >
           
                <div class = "tracker_wrapper">
                    <div class = "fw-bold fs-1 text-center text-shadow" style ="text-shadow: 1px 1px 3px rgba(0,0,0,0.3)"> 
                        Time - Tracker
                    </div>
                    
                         <div class="p-1">
                            <p>Date</p>
                            <input type="date" name="date" id="date" x-model= "date" class="form-control" value="date" />
                        </div>
                        
                        <div class="p-1 w-100 ">
                            <p>Task</p>
                            <input type="text" name="timer_task"  x-model ="timer_task" class="form-control" placeholder="Enter something">
                        </div>
                        <div  class = "p-1  cursor-pointer">
                            <p>Project Name</p>
                            <select name="project_name" id="project_name" x-model ="project_name" class="form-control">
                                <option value =''>Project </option>
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                        </div>
                         <div class="p-1 fw-bold">
                            <p>Total Time</p>
                            <input  readonly x-model ="total_time" type="text" name="total_time" id="total_time" class="form-control" value="total_time">
                        </div>
                    </div>
                    <div  class = "input d-flex flex-column mt-2 ">
                        <div
                             @click = "closeTimer()" 
                             class = "btn btn-primary mt-2 fw-bold"
                             :style=" timer_start || timer_stop ? 'background-color: #ffc107;color:black' : '' "
                             x-show =" !timer_start || timer_stop " 
                             >
                             Close
                        </div>
                        <div 
                            @click ="stopTimer()"
                            class = "btn btn-primary mt-2 fw-bold"
                            :style="timer_start ? 'background-color: red;color:black' : '' "
                            >
                            Stop
                        </div>
                        <div @click = "startTimer()" 
                        x-show = "timer_start == false"
                            class = "btn btn-primary mt-2 fw-bold" 
                            :disabled ="timer_start == true"
                            :style="timer_start == false ? 'background-color: green; color:black' : '' ">
                            Start Timer
                        </div>
                    </div>
                </div>
            </div>
     <!-- timer end-->
      
      <!-- pagination -->
       
 
    
</div>


<script>
        function TimetrackerFun1(){
            return {
                trackdata:<?= json_encode($data) ?>,
                weekWisetotal:<?= json_encode($weekWisetotal) ?>,
                dataeWiseTotal:<?= json_encode($dataeWiseTotal) ?>,
                timer_task:null,
                project_name :null,
                start_time :null,
                end_time:null,
                total_time:null,
                open_timer:false,
                timer_start:false,
                timer_stop:false,
                data:null,
                timeoutID:null,
                date:null,
                savetimeaftertenminute:null,
                totalRaw:<?= $totalRaw?>,
                rawperPage:10,
                countfullPage:null,
                countRawOnLastPage:null,
                startingRawOnpage:null,
                endingRawOnPage:null,
                currentPage:1,
                token:null,
                editTimerData(task,project_name,date){
                    console.log('id',task,project_name,date);
                    this.open_timer = true;
                    this.timer_task = task;
                    this.project_name =project_name;
                    this.date = date;
                },
                calculateTimeDifference() {
                    const [startHour, startMinute] =this.start_time.split(':').map(Number);
                    const [endHour, endMinute] = this.end_time.split(':').map(Number);

                    const startDate = new Date(0, 0, 0, startHour, startMinute);
                    const endDate = new Date(0, 0, 0, endHour, endMinute);

                    let diff = endDate - startDate;

                    const diffHours = Math.floor(diff / (1000 * 60 * 60));
                    const diffMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    this.total_time = diffHours + ":" + ((diffMinutes >= 0 && diffMinutes < 10 ) ? ('0' + diffMinutes) : diffMinutes);
            },
             startTimer(){
              
                 if( this.date == null || this.project_name == null || this.timer_task == null){
                    return;
                 }
                this.timer_start = true;
                this.timer_stop = false;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.start_time = this.getCurrentTime();
                console.log('start',this.start_time)
                this.timeoutID = setInterval(async () => {
                       console.log("This runs after 2 seconds");
                       this.end_time = this.getCurrentTime();
                       console.log('end',this.end_time);
                       //this.calculateTimeDifference();
                       this.savetimeaftertenminute = this.calulateDiffTimeForlivetimer();
                       var response = await fetch("{{ route('save.timerdata') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    task: this.timer_task,
                                    project_name:this.project_name,
                                    start_time:this.start_time,
                                    end_time:this.end_time,
                                    total_time: this.savetimeaftertenminute,
                                    date:this.date

                                }),
                                });
                                var data = await response.json();
                                if(data.success){
                                    this.start_time = this.getCurrentTime();
                                    this.total_time = data.total_time;
                                }
                        
                        
                }, 600000);
            },
            stopTimer(){
                this.start_time = null;
                this.end_time = null;
                clearInterval(this.timeoutID);
                this.timer_start = false;
                this.timer_stop = true;
               
            },
            closeTimer(){
                this.timer_task = null;
                this.project_name = null;
                this.date = null;
                this.total_time = null;
                this.savetimeaftertenminute = null;
                this.open_timer = false;
                this.timer_start = false;
                this.timer_stop = false;
               
            },
            getCurrentTime(){
                    const now = new Date();
                   

                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    const currentTime = `${hours}:${minutes}:${seconds}`;
                    return currentTime;
            },
            getTodaysdate(){
                
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based
                    const day = now.getDate().toString().padStart(2, '0');
                    const formattedDate = `${year}-${month}-${day}`;
                    this.date = formattedDate;
                    
            },
            calulateDiffTimeForlivetimer(){
                const [startHour, startMinute] =this.start_time.split(':').map(Number);
                    const [endHour, endMinute] = this.end_time.split(':').map(Number);

                    const startDate = new Date(0, 0, 0, startHour, startMinute);
                    const endDate = new Date(0, 0, 0, endHour, endMinute);

                    let diff = endDate - startDate;

                    const diffHours = Math.floor(diff / (1000 * 60 * 60));
                    const diffMinutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    return diffHours + ":" + ((diffMinutes >= 0 && diffMinutes < 10 ) ? ('0' + diffMinutes) : diffMinutes);
            },
            calculatePage(){
                this.countfullPage = (this.totalRaw % this.rawperPage === 0) ? Math.floor(this.totalRaw / this.rawperPage) :Math.floor(this.totalRaw / this.rawperPage)+1;
                
                this.countRawOnLastPage = (this.totalRaw % this.rawperPage);
                this.token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.getViewTaskList();
            },
            gotoPageNo(num){
                this.startingRawOnpage = ((num-1) * this.rawperPage) +1;
                this.endingRawOnPage = (num ==this.countfullPage) ? (this.startingRawOnpage + ( this.countRawOnLastPage-1) ):this.startingRawOnpage + (this.rawperPage -1)
                this.currentPage = num;
                console.log(this.startingRawOnpage,this.endingRawOnPage,this.currentPage )
                this.getViewTaskList();
                
            },
            goToPrevPage(){
               this.currentPage  ==1 ? this.gotoPageNo(1) : this.gotoPageNo(this.currentPage -1);
               //this.getViewTaskList();
               
            },
            goToNextPage(){
                this.currentPage  == this.countfullPage ? this.gotoPageNo(this.countfullPage) : this.gotoPageNo(this.currentPage + 1);
                //this.getViewTaskList();
            },
            async getViewTaskList(){
                console.log(this.startingRawOnpage,this.endingRawOnPage,this.currentPage )
                var response = await fetch("{{ route('task.list.page') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.token,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    currentPage: this.currentPage,
                                    startingRawOnpage:this.startingRawOnpage,
                                    endingRawOnPage:this.endingRawOnPage,
                                    rawperPage:this.rawperPage
                                    

                                }),
                                });
                                var data = await response.json();
                                if(data.success){
                                    
                                     this.trackdata= JSON.parse(data.task);
                                    this.weekWisetotal= JSON.parse(data.weekWisetotal);
                                    this.dataeWiseTotal= JSON.parse(data.dateWiseTotal);
                                    console.log('track f',data.pagination)
                                      //console.log('wekk g',data.weekWisetotal)
                                       // console.log('date',data.dateWiseTotal)
                                     
                                }
                 }
            }
        }
        </script>
@endsection
