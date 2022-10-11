@extends('layouts.admin')

@section('content')
<div class="content">
<?php 
 $user_role = Auth::user()->roles[0]->name;?>
	<div class="card">
	  <div class="card-body">
	    
	  </div>
	</div>
</div>
@endsection
@section('scripts')
@parent
@endsection