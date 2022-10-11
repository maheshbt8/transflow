@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $SubOrgUsers->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $SubOrgUsers->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $SubOrgUsers->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Roles
                        </th>
                        <td>
                            @foreach($SubOrgUsers->roles()->pluck('name') as $role)
                                <span class="label label-info label-many">{{ $role }}</span>
                            @endforeach
                        </td>
                    </tr>
					<tr>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.created_by') }}
                        </th>
                        <td>
                            <?php
								$user1 = $SubOrgUsers->find($SubOrgUsers->created_by);
								echo $user1->name;
						    ?>
                        </td>
                    </tr>
					
					<tr>
                        <th>
                            {{ trans('cruds.user.fields.org_label') }} / {{ trans('cruds.user.fields.suborg_label') }}
                        </th>
                        <td>
                           <?php								
								$org_details = DB::table('kptorganizations')->join('user_orgizations', 'kptorganizations.org_id', '=', 'user_orgizations.org_id')->where('user_id', $SubOrgUsers->id)->get();
								
								$suborg_details = DB::table('kptsuborganizations')->join('user_orgizations', 'kptsuborganizations.sub_org_id', '=', 'user_orgizations.sub_id')->where('user_id', $SubOrgUsers->id)->get();		
							?>
							<?php echo UCfirst($org_details[0]->org_name); ?><strong> /</strong>
							<?php echo UCfirst($suborg_details[0]->sub_org_name); ?>
                        </td>
                    </tr>
					
					
					
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection