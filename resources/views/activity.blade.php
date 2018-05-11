@extends('spark::layouts.app')
@section('content')
<div class="container">
	<p>Hello</p>
	<activity-form :parent="{parent: {name: 'Test'}}"></activity-form>
</div>
@endsection