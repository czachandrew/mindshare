@extends('spark::layouts.app')
@section('content')
<div class="container">
	<company-view :current-company="{{$company}}"></company-view>
	<!-- <company-form></company-form> -->
</div>
@endsection