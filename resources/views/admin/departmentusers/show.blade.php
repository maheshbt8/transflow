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
                            {{ $DepartmentUsers->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $DepartmentUsers->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $DepartmentUsers->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Roles
                        </th>
                        <td>
                            @foreach($DepartmentUsers->roles()->pluck('name') as $role)
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
								$user1 = $DepartmentUsers->find($DepartmentUsers->created_by);
								echo $user1->name;
						  ?>
                        </td>
                    </tr>
					
					<tr>
                        <th>
                            {{ trans('cruds.user.fields.suborg_label') }} / {{ trans('cruds.user.fields.dept_label') }}
                        </th>
                        <td>
                            <?php								
								$suborg_details = DB::table('kptsuborganizations')->join('user_orgizations', 'kptsuborganizations.sub_org_id', '=', 'user_orgizations.sub_id')->where('user_id', $DepartmentUsers->id)->get();

								$dept_details = DB::table('kptdepartments')->join('user_orgizations', 'kptdepartments.department_id', '=', 'user_orgizations.sub_sub_id')->where('user_id', $DepartmentUsers->id)->get();									
							?>
							<?php echo UCfirst($suborg_details[0]->sub_org_name); ?><strong> / </strong>
							<?php echo  UCfirst($dept_details[0]->department_name); ?>	
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