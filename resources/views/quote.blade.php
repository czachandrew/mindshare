@extends('spark::layouts.app')
@section('content')
<div class="container">
	<quote-component :load-company="{{$company}}" :load-quote="{{$quote}}" start-state="{{$start}}"></quote-component>

</div>
@endsection