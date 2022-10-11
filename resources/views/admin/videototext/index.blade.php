@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.videototext.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.videototext.title_singular') }}
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.videototext.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <!-- <th width="10">

                        </th> -->
                      
                        <th>
                            {{ trans('cruds.videototext.fields.job_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.videototext.fields.target_language') }}
                        </th>
                        <th>
                            {{ trans('cruds.videototext.fields.JSON_file') }}
                            
                        </th>
						<th>
                            {{ trans('cruds.videototext.fields.video_text') }}
                            
                        </th>
						
						<th>
                            {{ trans('cruds.videototext.fields.uploaded_at') }}
                        </th>	
                        
						<th>
                            {{ trans('cruds.videototext.fields.Action') }} 
                        </th>			
						 <th >

                        </th>
						
                    </tr>
                </thead>
                    <tbody>
                     @foreach($videototext as $videotext)
                        <tr >
                           
                            
                            <td>
                                {{ $videotext->job_name ?? '' }}
                            </td>
                            <td>
                                {{  $videotext->target_language ?? '' }}
				@if($videotext->job_status =='completed')
								<a href="{{url('storage/videototext/')}}/{{ $videotext->job_name}}-{{$videotext->target_language}}.srt" alt="download"><i class="fa fa-download"></i></a>
				@endif 
                            </td>
                            <td>
                                {{  $videotext->job_output_json_url ?? '' }}
                                <a href="{{url('storage/videototext/')}}/{{ $videotext->job_output_json_url}} 
" download><i class="fa fa-download"></i></a>
                            </td>
                            <td>
                                {{  $videotext->job_video_media_url ?? '' }}
                                <a href="{{url('storage/videototext/')}}/{{$videotext->job_video_media_url}} 
" download><i class="fa fa-download"></i></a>
                            </td>
                            <td>
                                {{  $videotext->job_created_at ?? '' }}
                            </td>
                            <td>
							@if($videotext->job_status =='completed')
								 <a href="{{url('storage/videototext/')}}/{{$videotext->job_name}}_output.mp4 
" download><i class="fa fa-download"></i></a>
							@endif  
                                {{  $videotext->job_status ?? '' }}
                            </td>
                      
                            <td>
                               @if($videotext->job_status =='completed')
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.videototext.edit', $videotext->job_id) }}">
                                    {{ trans('cruds.videototext.fields.edit_translation') }}
                                </a>
                               @else
								   <marquee style='color:#FFFFFF;height:20px;' bgcolor='#ff6a00'  direction='right'>Subtitles integration is in-progress. Please wait..</marquee>
                                
                              @endif  
                                                       
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
<script>
</script> 
@endsection
