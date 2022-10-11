@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.kptdepartment.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.dept_id') }}
                        </th>
                        <td>
                            {{ $clientdept->client_dpt_id }}
                        </td>
                    </tr>
					
                    <tr>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.dept_name') }}
                        </th>
                        <td>
                            {{ $clientdept->client_dpt_name }}
                        </td>
                    </tr>
					
					 <tr>
                        <th>
                           {{ trans('cruds.kptdepartment.fields.dept_status') }}
                        </th>
                        <td> 						
							<?php
								if ($clientdept->client_dpt_status == 1)
									echo "Active";
								elseif($clientdept->client_dpt_status == 0)
									echo "Inactive";
                            ?>							
                        </td>
                    </tr>
					
					<tr>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.created_by') }}
                        </th>
                        <td>
                            <?php
								$user = $user->find($clientdept->created_by);
								echo $user->name;
						    ?>
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