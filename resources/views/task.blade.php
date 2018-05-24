@extends('spark::layouts.app')
@section('content')
	<div class="container">
		<task-zoom-modal :load-task="{{$task}}"></task-zoom-modal>
	</div>
@endsection