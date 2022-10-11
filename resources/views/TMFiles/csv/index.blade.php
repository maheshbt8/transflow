@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.translationcsvfile.approve_translations") }}">
            Approve Data
        </a>
    </div>
</div>
@if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>		
@endif

@if(Session::has('flash_message_error'))
        <div class="alert alert-danger">
            {{ Session::get('flash_message_error') }}
        </div>		
@endif


<div class="card">
    <div class="card-header">
       File Translation
     </div>
	<?php	
		/*$arr_languages = array("en"=>"English","fr"=>"French","de"=>"German","es"=>"Spanish","ko"=>"Korea","te"=>"Telugu","hi"=>"Hindi","th"=>"Thai","ta"=>"Tamil","bn"=>"Bengali","gu"=>"Gujarati","kn"=>"Kannada","ml"=>"Malayalam","mr"=>"Marathi","pa"=>"Punjabi","ur"=>"Urdu","as"=>"Assamese","or"=>"Oriya","si"=>"Sinhala","ar"=>"Arabic","en_au"=>"Austria English","cs"=>"Czech","da"=>"Danish","nl"=>"Dutch","pt"=>"Portuguese","fi"=>"Finnish","hu"=>"Hungarian","es_mx"=>"Mexican Spanish","no"=>"Norwegian","pl"=>"Polish","sk_sk"=>"Slovak","uk"=>"Ukrainian");
		asort($arr_languages);*/
					
	
	?>

    <div class="card-body">
		<div class="d-flex justify-content">
		<form action="{{ route("admin.translationcsvfile.file_translation_memory") }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
            @csrf	
		<div class="row">
			<div class="col-md-3">
			<div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
			<label for="domain" class="required">Project Name</label>
            <input class="form-control" name="project_name" placeholder="Project Name" required>
			<div class="invalid-feedback" for="">Please Enter ProjectName </div>		
		</div>

			</div>	
			<div class="col-md-3">		
			<div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
			<label for="domain" class="required">Domain</label>
			 <select name="domain" id="domain" class="form-control js-example-basic-single" required >
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
				<div class="invalid-feedback" for="">Must select the domain </div>				
			 </div>
			 </div>
			<!-- Source Language block -->
			<div class="col-md-3">
		 <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
			<label for="roles" class="required">Source Language</label>
			 <select name="sourcelanguage" id="source_language source_language_0" class="form-control js-example-basic-single" required >
			 <option value="" >Select Source Language</option>
			  @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_id }}" >{{ $lang->lang_name }}</option>
               @endforeach                 
              </select>
				
				 @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
				<div class="invalid-feedback" for="">Must select Source Language</div>				
			 </div>
			 </div>
		
			
			
			<!-- target block -->
		<div class="col-md-3">
			<div class="form-group {{ $errors->has('suborganization') ? 'has-error' : '' }}">
				<label for="target languages" class="required">Target Language</label>
				 <select name="targetlanguages" id="targetlanguages" class="form-control  js-example-basic-single" required >
				 <option value="" >Select Target Language</option>
					@foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_id }}" >{{ $lang->lang_name }}</option>
               		@endforeach  
				</select>					
					 @if($errors->has('suborganization'))
						<em class="invalid-feedback">
							{{ $errors->first('suborganization') }}
						</em>
					@endif
					<p class="helper-block">
						{{ trans('cruds.user.fields.roles_helper') }}
					</p>
					<div class="invalid-feedback" for="">Must select Target Language</div>				
			 </div>
		 


          </div>
		  <div class="col-md-3">
			<div class="form-group {{ $errors->has('suborganization') ? 'has-error' : '' }}">			
				<label  for="file"  class="required">Upload Your File</label>
				<input type="file" class="form-control" name="translation_csv_file" id="translation_csv_file" required >
				@if($errors->has('suborganization'))
						<em class="invalid-feedback">
							{{ $errors->first('suborganization') }}
						</em>
					@endif
					<p class="helper-block">
						{{ trans('cruds.user.fields.roles_helper') }}
					</p>
					<div class="invalid-feedback" for="">Must choose the File</div>
				 <span style="color:red; font-size:14px;">Note: Supports <b>.csv, .xlsx, .xls, .pdf, .docx, .doc, .txt</b></span>
			</div>	
		  </div>	
		  
			</div>
			<input type="hidden" name="user_id" placeholder="Org Name" class="form-control" value="" > 
			<div>&nbsp;</div>					
            <div > 
                <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
            </div>
	 
        </form> 
        </div>       
    </div>
</div>

            <div class="card">
                    <header class="card-header">
                       
                        <h2 class="card-title ven">File  Details</h2>
                    </header>
				
                <div class="card-body">
					<div class="table-responsive">
                        <table class="  table table-bordered table-striped table-hover datatable_table">
						<thead>
						<tr>
                                <th>ID</th>
                                <th>Project Name</th>
								<th>File Name</th>
                                <th>Domain</th>
                                <th>Language pair</th>
                                <th>Word Count</th>
                                <th>Repeated Words</th>
                                <th>character count</th>
                                <th>segment count</th>
                              
                                
							
                                <th>Action</th>
                            </tr>
                      </thead>
                            <?php $i=1;?>
                            @foreach($file_data as $item)
						   <?php $domain_data=DB::connection('mysql2')->table('tm_domains')->where('domain_id',$item->domain_id)->select('domain_name','domain_id')->first();?>
                            <tr>

                                <td>{{$i++}}</td>
                                <td>{{$item->project_name}}</td>
                                <td>{{$item->files_name}}</td>
                                <td>{{ $domain_data->domain_name }}</td>
                                <td>{{ getlangbyid($item->source_lang_id)}} - {{ getlangbyid($item->target_lang_id)}}</td>
                                <td>{{ $item->word_count}}</td>
                                <td>{{ $item->repeated_words}}</td>
                                <td>{{ $item->character_count}}</td>
                                <td>{{ $item->segment_count}}</td>
                               <td> <a class="btn btn-success btn-sm"  href="{{ route('admin.translationcsvfile.get_file_data_content',[$item->id])}}" >Translate</a></td>
                            </tr>
                            @endforeach
                        </table>
					</div>
                </div>
            
           </div>



@endsection
@section('scripts')
@parent
<script>

function selectRefresh() {
       $('.select2').select2({
           tags: true,
           /*placeholder: "Select an Option"*/
       });
   }


   $(document).ready(function() {
    $('.js-example-basic-single').select2();
});


$('.file-upload').file_upload();
</script>
@endsection