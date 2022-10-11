@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.kptsuborganization.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.kptsuborganization.fields.sub_org_id') }}
                        </th>
                        <td>
                            {{ $KptSubOrganizations->sub_org_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.kptsuborganization.fields.sub_org_name') }}
                        </th>
                        <td>
                            {{ $KptSubOrganizations->sub_org_name }}
                        </td>
                    </tr>
					
					 <tr>
                        <th>
                             {{ trans('cruds.kptsuborganization.fields.sub_org_status') }}
                        </th>
                        <td>
                           <?php
								if ($KptSubOrganizations->sub_org_status == 1)
									echo "Active";
								elseif($KptSubOrganizations->sub_org_status == 0)
									echo "Inactive";
                           ?>
                        </td>
                    </tr>
					
					
					 <tr>
                        <th>
                           {{ trans('cruds.kptsuborganization.fields.created_by') }}
                        </th>
                        <td>
                            <?php
								$user = $user->find($KptSubOrganizations->created_by);
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