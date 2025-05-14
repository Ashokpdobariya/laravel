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




<div class ="time-add-wrapper" x-data ="TimetrackerFun()" x-init=" h()" style="background-color:#F2F6F5 !important;" >
    <form action="{{ url('admin/time-submit') }}" method="POST">
        @csrf
        <div class= "d-flex  bg-white shadow flex-fill" >
            <div class="p-2 w-25 ">
                <input type="text" name="task" class="form-control" placeholder="Enter something">
            </div>
            <div class="p-2 d-flex">
            <div  class = "p-2 flex-fill  cursor-pointer">
                            <img width ="30" src="{{ asset('images/plus-blue-req.svg') }}" alt="Logo" class="img-fluid">
                        </div>
                <select name="project_name" id="project_name" class="form-control">
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
                <input type="date" name="date" id="date" class="form-control" value="{{ old('start_time') }}">
            </div>
            <div class="p-2 ">
                <input  x-model ="total_time" type="total_time" name="total_time" id="total_time" class="form-control" value="total_time">
            </div>
            <div class="p-2">
                <button type="submit" class="btn btn-primary ">Add</button>
            </div>
            <div class="p-2 d-flex flex-column ms-2">
                <div  class = " cursor-pointer">
                    <img src="{{ asset('images/clock-blue.svg') }}" alt="Logo" class="img-fluid">
                </div>
                <div class = "cursor-pointer">
                    <img  src="{{ asset('images/list-gray.svg') }}" alt="Logo" class=" img-fluid">
                </div>
            </div>
    </div>
    </form>
    <!-- <div class="all-data-viewer mt-6" >
        <div class ="inner-main " style ="background-color:#e4eaee" >
            <p class ="ms-2">this week </p>
            @foreach($tasks as $task)
                <div class="d-flex bg-white mt-1 ms-1 p-2">
                    <div class ="d-flex flex-fill w-70">
                        <div class="ms-1 p-2  " style ="background-color:#e4eaee" >{{ $task->task }}</div>
                        <div class=" p-2 ">{{ $task->project_name }}</div>
                    </div>
                    <div class="d-flex ">
                        <div class=" p-2 flex-fill ">{{ $task->start_time }}</div>
                        <div class=" p-2 flex-fill ">{{ $task->end_time }}</div>
                        <div class=" p-2 flex-fill  ">{{ $task->date }}</div>
                        <div class="p-2 flex-fill ">{{ $task->total_time }}</div>
                        <div  class = " p-2 flex-fill  cursor-pointer">
                            <img src="{{ asset('images/play.svg') }}" alt="Logo" class="img-fluid">
                        </div>
                        <div  class = "p-2 flex-fill  cursor-pointer">
                            <img src="{{ asset('images/locked.svg') }}" alt="Logo" class="img-fluid">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div> -->
</div>

<script>
    function TimetrackerFun(){
        return {
            start_time :null,
            end_time:null,
            total_time:null,
            data:null,
            h(){
                console.log('called',this.taskdata);
                this.data = `<?= $tasks?>`;
                console.log('data',this.data)

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
                }
        }
    }
    </script>
@endsection
