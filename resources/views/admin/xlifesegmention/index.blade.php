@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
    <a class="btn btn-success" href="{{ route("admin.xliff.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.xliffsegmentation.title_singular') }}
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
    {{ trans('cruds.xliffsegmentation.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                    <th width="10">

                     </th>
                        <th>
                             {{ trans('cruds.xliffsegmentation.fields.Refrence_Code') }}
                        </th>
                        <th>
                            {{ trans('cruds.xliffsegmentation.fields.Uploaded_file') }}
                        </th>
                        <th>{{ trans('cruds.xliffsegmentation.fields.Status') }}
                        </th>
                        <th>
                            {{ trans('cruds.xliffsegmentation.fields.Uploaded_on') }}
                        </th>
                       
                        
                    
						<th>
                           {{ trans('cruds.xliffsegmentation.fields.Completed_On') }}
                        </th>

						
						
                        <!-- <th style="width:15% !important;">
                            &nbsp;
                        </th> -->
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection
