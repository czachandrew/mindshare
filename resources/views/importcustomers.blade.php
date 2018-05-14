@extends('spark::layouts.app')
@section('content')
<div class="container">
	<div class="card card-body col-md-8">
		<import-component :users="{{$users}}"></import-component>
	</div>
</div>

@endsection