@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
      Video Embed
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <form action="{{ route("admin.videototext.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
               
           
		  <div class="form-group {{ $errors->has('destination_language') ? 'has-error' : '' }}">
            <label for="destination_language" class="required">Service</label>
            <select name="service" id="service" class="form-control select2" required>
                <option value="">Select Service Type</option>     
                <option value="">Aws </option>     
                <option value="">Google Speech</option>    
            </select> 
              
            </div>
        <div id="div_source_file" class="form-group {{ $errors->has('source_type') ? 'has-error' : '' }} files">
            <label>Source File </label>
            <input type="file" class="form-control" name="source_file" id="source_file">
           <span style="color:red;">Note: Supports only .mp4 file.</span>
        </div>
		
		  <div class="form-group {{ $errors->has('destination_language') ? 'has-error' : '' }}">
            <label for="destination_language" class="required">Target Language</label>
            <select name="destination_language" id="destination_language" class="form-control select2" required>
                <option value="">Select Target Language</option>

                @foreach($loc_languages as $key => $lang)
                <option value="{{ $lang->lang_code }}">{{ $lang->lang_name }}</option>
                @endforeach

            </select>
            @if($errors->has('destination_language'))
            <em class="invalid-feedback">
                {{ $errors->first('destination_language') }}
            </em>
            @endif
        </div>
		
		
		
        <div>&nbsp;</div>
        <div>
            <input class="btn btn-danger" id="videototext_submit" type="submit" value="{{ trans('global.submit') }}">
        </div>
        </form>
    </div>
</div>
</div>
@endsection