@extends('layouts.admin')
<style>
 .percent {
                position:absolute;
                display:inline-block; 
                left:50%;
                color: #040608;
            }
            .progress { 
                position:relative;
                width:100%;
            }
            .bar { 
                background-color: #00ff00;
                width:0%;
                height:20px;
            }
</style>
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.create') }}
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <form   action="{{ route("admin.speechtospeech.store") }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <!-- Source Language block -->
                <div class="form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                    <label for="source_language" class="required">Source Language</label>
                    <select name="source_language" id="source_language" class="form-control select2" required>
                        <option value="">Select Video Speech Language</option>

                        @foreach($loc_languages as $key => $lang)
                        @if($lang->lang_code=='HI')
                        <option value="{{ $lang->lang_code }}">{{ $lang->lang_name }}</option>
                       @endif
                        @endforeach

                    </select>

                    @if($errors->has('source_language'))
                    <em class="invalid-feedback">
                        {{ $errors->first('source_language') }}
                    </em>
                    @endif
                </div>

               
              
        <div class="form-group {{ $errors->has('destination_language') ? 'has-error' : '' }}">
            <label for="destination_language" class="required">Target Language</label>
            <select name="destination_language" id="destination_language" class="form-control select2" required>
                <option value="">Select Target Language</option>

                @foreach($loc_languages as $key => $lang)
                @if($lang->lang_code=='EN')
                <option value="{{ $lang->lang_code }}">{{ $lang->lang_name }}</option>
                @endif
                @endforeach

            </select>
            @if($errors->has('destination_language'))
            <em class="invalid-feedback">
                {{ $errors->first('destination_language') }}
            </em>
            @endif
        </div>
        <div  class="form-group {{ $errors->has('source_type') ? 'has-error' : '' }} files">
            <label>Source File </label>
            <input type="file" class="form-control" name="source_file" id="source_file" id="uploadForm">
           <span style="color:red;">Note: Supports only .mp4 file.</span>
           <br>
           <div class="progress">
<div class="bar"></div >
<div class="percent">0%</div >
</div>
        </div>
        <div>&nbsp;</div>
       

        <div>
            <input class="btn btn-danger" id="videototext_submit" type="submit" value="{{ trans('global.submit') }}">
       
        
        </form>
       
    </div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
        <script type="text/javascript">
            var SITEURL = "{{URL('/')}}";
            $(function () {
                $(document).ready(function () {
                    var bar = $('.bar');
                    var percent = $('.percent');
                    $('#uploadForm').ajaxForm({
                        beforeSend: function () {
                            var percentVal = '0%';
                            bar.width(percentVal);
                            percent.html(percentVal);
                        },
                        uploadProgress: function (event, position, total, percentComplete) {
                            var percentVal = percentComplete + '%';
                            bar.width(percentVal)
                            percent.html(percentVal);
                        },
                        complete: function (xhr) {
                            alert('File Has Been Uploaded Successfully');
                            window.location.href = SITEURL + "/admin/speechtospeech";
                            //  return redirect()->route("admin.speechtospeech.index"); 

                        }
                    });
                });
            });
        </script>
@endsection