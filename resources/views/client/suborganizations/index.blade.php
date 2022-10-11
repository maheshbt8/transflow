@extends('layouts.admin')
@section('content')
@if ( checkpermission('client_suborg_manage','add'))

<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.clientsuborg.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.kptsuborganization.title_singular') }}
        </a>
    </div>
</div>
@endif


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
        {{ trans('cruds.kptsuborganization.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.kptsuborganization.fields.sub_org_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptsuborganization.fields.sub_org_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptsuborganization.fields.sub_org_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptsuborganization.fields.created_by') }}
                        </th>
                       
                        <th>
                            {{ trans('cruds.user.fields.org_label') }} 
                        </th>
                      
                       
                        <th class="noExport">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientsuborg as $key => $clientsuborgs)
                    <tr data-entry-id="{{ $clientsuborgs ?? ''->client_suborg_id}}">
                    <td >
                          
							
                            </td>
                            <td>
                            {{ $clientsuborgs->client_suborg_id ?? '' }}
                            </td>
                            <td>
                            {{ $clientsuborgs->client_suborg_name ?? '' }}
                                
                            </td>
                            <td>
                            <?php
								if ($clientsuborgs->client_suborg_status == 1)
									echo "Active";
								elseif($clientsuborgs->client_suborg_status == 0)
									echo "Inactive";
                                ?>  
                            </td>
                            <td>
								<?php
							$user_data = $user->find($clientsuborgs->created_by);
                                if (!empty($user_data)) {
                                    echo $user_data->name;
                                }
								?>
								
                            </td>
                            <td>
                            <?php				
								$org_details = DB::table('clientorganizations')->where('org_id', $clientsuborgs->client_org_id)->get();
								
								// $suborg_details = DB::table('kptsuborganizations')->join('user_orgizations', 'kptsuborganizations.sub_org_id', '=', 'user_orgizations.sub_id')->where('user_id', $user->id)->get();		
							?>
                            
							@if(count($org_details) >0)
							<span class="badge badge-info"><?php echo UCfirst($org_details[0]->org_name); ?></span>
                            @endif
                            </td>
                            
                            <td style=" width:15% !important;">
                        <a class="btn btn-xs" href="{{ route('admin.clientsuborg.show', $clientsuborgs->client_suborg_id) }}">
                            <i class="fa fa-eye text-success"></i>
                        </a>
                        @if(checkpermission('client_suborg_manage','update'))
                        <a class="btn btn-xs" href="{{ route('admin.clientsuborg.edit', $clientsuborgs->client_suborg_id) }}">
                            <i class="fa fa-edit text-info"></i>
                        </a>
                         @endif
                         @if(checkpermission('client_suborg_manage','delete'))
                        <form action="{{ route('admin.clientsuborg.destroy', $clientsuborgs->client_suborg_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-xs"><i class='fa fa-trash text-danger'></i></button>
                        </form>
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