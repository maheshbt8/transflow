@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href='{{ route("admin.ocrpdf.create")}}'>
            {{ trans('global.add') }} {{ trans('cruds.ocrpdf.title_singular') }}
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ocrpdf.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                    <th width="10">

                     </th>
                        <th>
                             {{ trans('cruds.ocrpdf.fields.reference_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.ocrpdf.fields.uploaded_files') }}
                        </th>
                       
                        <th>{{ trans('cruds.ocrpdf.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.ocrpdf.fields.uploaded_on') }}
                        </th>
						<th>
                           {{ trans('cruds.ocrpdf.fields.completed_date') }}
                        </th>

						
						
                        <!-- <th style="width:15% !important;">
                            &nbsp;
                        </th> -->
                    </tr>
                </thead>
                <tbody>
                   <?php //print_r($marketing_campaign);die;?>
                    @foreach($marketing_campaign as $mcampaign)
                    <tr data-entry-id="{{ $mcampaign->mk_campign_id }}">
                        <td>

                        </td>
                        <td>{{$mcampaign->kpt_reference_code}}</td>
                        <td>{{$mcampaign->org_upload_file_name}} 
                            <a href="{{url('storage/ocrpdf/')}}/{{$mcampaign->org_upload_file_name}} 
" download><i class="fa fa-download"></i></a>
                        </td>
                        <td>{{$mcampaign->uploaded_status}}</td>
                        <td>{{$mcampaign->uploaded_date}}</td>
                        <td>{{$mcampaign->completed_date}}</td>
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
