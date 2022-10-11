@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.show') }}
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                  {{ trans('global.view') }} {{ trans('cruds.locrequest.title_singular') }}  
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-12">
                      <label>{{ trans('cruds.locrequest.fields.request_id') }}:</label>
                      <b>{{ $edit_request->reference_id }}</b>
                    </div>
                    <div class="col-md-12">
                      <label>{{ trans('cruds.locrequest.fields.name') }}:</label>
                      <b>{{ $edit_request->project_name }}</b>
                    </div>
                    <div class="col-md-12">
                      <label>{{ trans('cruds.locrequest.fields.category') }}:</label>
                      <b>{{ $edit_request->category }}</b>
                    </div>
                    <div class="col-md-12">
                      <label>{{ trans('cruds.locrequest.fields.source_language') }}:</label>
                      <?php
                        $source_lang=app\loc_request::loc_reference_lang_select($edit_request->req_id);
                      ?>
                      <b>{{ $source_lang[0]->lang_name }}</b>
                    </div>
                    <div class="col-md-12">
                      <label>{{ trans('cruds.locrequest.fields.target_language') }}:</label>
                      <?php
                        $source_lang=app\loc_request::loc_reference_lang_select($edit_request->req_id,'target');
                      ?>
                      <b>{{ $source_lang[0]->lang_name }}</b>
                    </div>
                    <div class="col-md-12">
                      <label>{{ trans('cruds.locrequest.fields.special_instructions') }}:</label>
                      <b>{{ $edit_request->special_instructions }}</b>
                    </div>
                    <div class="col-md-12">
                      @if($edit_request->source_type == 'File')
                      <label>{{ trans('cruds.locrequest.fields.source_file') }}:</label>
                      <b><a href="{{ asset('storage/request/'.$edit_request->source_file_path)}}" download="">{{ $edit_request->source_file_path }}</a></b>
                      @endif

                      @if($edit_request->source_type == 'Text')
                      <label>{{ trans('cruds.locrequest.fields.source_text') }}:</label>
                      <b>{{ $edit_request->source_text }}</b>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                Add Update 
            </div>
            <div class="card-body">
            <form action="{{ route("admin.request.requestcomments") }}" method="POST" enctype="multipart/form-data">
                @csrf  
                <input type="hidden" name="reference_id" value="{{ $edit_request->reference_id }}">
                <div class="form-group {{ $errors->has('upload_file') ? 'has-error' : '' }}">  
                    <label>{{ trans('cruds.locrequest.fields.upload_file') }} </label>
                    <input type="file" class="form-control" name="source_file" id="source_file" >
                </div>
            <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                <label for="special_instruction" class="required">{{ trans('cruds.locrequest.fields.comments') }}</label>
                 <textarea rows="4" cols="50" name="comments" id="comments" class="form-control" required=""></textarea>
                     @if($errors->has('comments'))
                        <em class="invalid-feedback">
                            {{ $errors->first('comments') }}
                        </em>
                    @endif               
            </div>
                
                <div>&nbsp;</div>                   
                <div>
                    <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
                </div>
            </form>
          </div>
          </div>
        </div>
        </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Updates On Request
          </div>
          <div class="card-body"> 
            @foreach($request_comments as $key => $comments)
            <div class="card">
              <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <label>{{ trans('cruds.locrequest.fields.comments') }}:</label>
                  <b>{{ $comments->special_instructions }}</b>
                </div>
                @if($comments->source_file_path != '')
                <div class="col-md-12">
                  <label>{{ trans('cruds.locrequest.fields.download_link') }}:</label>
                @if($comments->source_file_path != '')
                <a href="{{ asset('storage/request/comments/'.$comments->source_file_path) }}" download=""><b>{{ $comments->source_file_path }}</b></a>
                @endif                  
                </div>
                @endif
                <div class="col-md-12">
                  <label>{{ trans('cruds.locrequest.fields.updated_by') }}:</label>
                  <b>{{ $comments->user_name }}</b>
                </div>
                <div class="col-md-12">
                  <label>{{ trans('cruds.locrequest.fields.updated_on') }}:</label>
                  <b>{{ $comments->created_time }}</b>
                </div>
              </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      </div>
    </div>
</div>
@endsection