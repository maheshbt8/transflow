@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.emailsettings.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.e_settings.title_singular') }}
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.e_settings.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th>
                            
                        </th>
                        <th>
                            {{ trans('cruds.e_settings.fields.template') }}
                        </th>
                        <th>
                            {{ trans('cruds.e_settings.fields.subject') }}
                        </th>
                        <th>
                            {{ trans('cruds.e_settings.fields.to_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.e_settings.fields.cc_address') }}
                        </th>
						<th>
                            {{ trans('cruds.e_settings.fields.bcc_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.e_settings.fields.organization') }}
                        </th>

						<th class="noExport">
                            {{ trans('cruds.e_settings.fields.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                 
                    @foreach($email_settings as $key => $email)
                         <tr data-entry-id="{{ $email->email_setting_id }}"> 
                            <td>
                                
                            </td>
                            <td>
                                {{ $email->email_template }}
                            </td>
                            <td>
                                {{ $email->email_subject }}
                            </td>
                            <td>
                                {{ $email->email_to_address }}
                            </td>
                            <td>
                            	{{ $email->email_cc_address }}
                            </td>
                            <td>
                                {{ $email->email_bcc_address }}
                            </td>
                            <td>
                            <?php				
								$org_details = DB::table('kptorganizations')->where('org_id', $email->email_org)->get();
							?>
                            
							@if(count($org_details) >0)
							<span class="badge badge-info"><?php echo UCfirst($org_details[0]->org_name);?></span>
                            @endif
                                <!-- {{ $email->email_org}} -->
                                
                            </td>
                            <td>
                                <a href="emailsettings/{{$email->email_setting_id}}/edit" class="btn btn-sm btn-info">Update</a>
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
