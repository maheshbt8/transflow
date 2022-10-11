@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.create') }}
    </div>

    <div class="card-body">
      <div class="d-flex justify-content">
        <form action="{{ route("admin.translationmemory.store") }}" method="POST" enctype="multipart/form-data">
            @csrf    
            <!-- Source Language block -->
         <div class="form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
            <label for="source_language" class="required">Source Language</label>
             <select name="source_language" id="source_language" class="form-control select2" required >
             <option value="" >Select Source Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
                
                 @if($errors->has('source_language'))
                    <em class="invalid-feedback">
                        {{ $errors->first('source_language') }}
                    </em>
                @endif               
            </div>

            <!-- Organization block -->
            
              
            
            <!-- Sub Organization block -->
            <div class="form-group {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                <label for="destination_language" class="required">Target Language</label>
                 <select name="destination_language" id="destination_language" class="form-control select2" required >
                 <option value="" >Select Target Language</option>
                    @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
                    @endforeach   
                </select>                   
                     @if($errors->has('destination_language'))
                        <em class="invalid-feedback">
                            {{ $errors->first('destination_language') }}
                        </em>
                    @endif             
            </div>
        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
            <label for="domain" class="required">Domain</label>
             <select name="domain" id="domain" class="form-control select2" required >
             <option value="" >Select Domain</option>
              @foreach($domains as $domain)
                              <option value="{{$domain->domain_id }}" >{{$domain->domain_name}}</option>
              @endforeach
                                        
                    </select>
              
               @if($errors->has('domain'))
                          <em class="invalid-feedback">
                              {{ $errors->first('domain') }}
                          </em>
                      @endif
                      <p class="helper-block">
                          {{ trans('cruds.user.fields.roles_helper') }}
                      </p>        
        </div>
            <div id="div_source_file" class="form-group {{ $errors->has('source_type') ? 'has-error' : '' }} files">               
                <label>Source File </label>
                <input type="file" class="form-control" name="source_file" id="source_file" >
                <span style="color:red;">Note: Supports only .tmx</span>
            </div>
            <div>&nbsp;</div>                   
            <div>
                <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
