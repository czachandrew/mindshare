@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row justify-content-center">
            <!-- work button goes here get 10 tasks 10 companies to call email and follow up --> 

            <!-- one button for work reps and one button for working customers --> 

            <!-- show active leads which are customers that have been contacted --> 

            <!-- show active customers which are converted leads or sales --> 
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">{{__('Dashboard')}}</div>

                    <div class="card-body">
                        
                        <task-list :load-tasks="{{$tasks}}" task-scope="Global"></task-list>
                       <company-list :companies="{{$companies}}"></company-list>

                    </div>
                </div>
                 <!-- <task-list></task-list> --> 
                 
                 
            </div>
        </div>
    </div>
</home>

@endsection
