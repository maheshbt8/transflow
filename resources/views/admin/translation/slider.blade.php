@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.translation.title_singular') }} 
        {{ trans('cruds.translation.fields.slider') }}
    </div>
    <div class="card-body">
            <div id="img-slider" class="twentytwenty-container" >
                <img alt="english sample" src="{{ asset('img/translateslide/english.jpg') }}" />
                <img alt="hindi sample" src="{{ asset('img/translateslide/hindi.jpg') }}" />
            </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<link rel="stylesheet" href="{{ asset('css/twentytwenty.css') }}" type="text/css" media="screen" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('js/jquery.event.move.js') }}"></script>
<script src="{{ asset('js/jquery.twentytwenty.js') }}"></script>
    
<style>
#img-slider {
   margin: 0 auto;
   width: 70vw;
   box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
}
</style>
    
    <script>
    $(document).ready(function(){
        $('#img-slider').twentytwenty();
      });
    </script>
@endsection