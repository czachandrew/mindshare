@extends('spark::layouts.app')
@section('content')
<div class="container">
	<company-view :company="{{$company}}"></company-view>
	<!-- <company-form></company-form> -->
</div>
@endsection