@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.kptorganization.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.kptorganization.fields.org_id') }}
                        </th>
                        <td>
                            {{ $clientorganization->org_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.kptorganization.fields.org_name') }}
                        </th>
                        <td>
                            {{ $clientorganization->org_name }}
                        </td>
                    </tr>
					
					<tr>
                        <th>
                             {{ trans('cruds.kptorganization.fields.org_status') }}
                        </th>
                        <td>
                            <?php
								if ($clientorganization->org_status == 1)
									echo "Active";
								elseif($clientorganization->org_status == 0)
									echo "Inactive";
                             ?>
                        </td>
                    </tr>
					
					
					<tr>
                        <th>
                            {{ trans('cruds.kptorganization.fields.created_by') }}
                        </th>
                        <td>
                            <?php
								$user = $user->find($clientorganization->created_by);
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