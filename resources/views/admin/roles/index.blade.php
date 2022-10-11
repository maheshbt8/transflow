@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
    @if(checkpermission('roles','add'))
        <a class="btn btn-success" href="{{ route("admin.roles.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>@endif
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                        <!-- <th>
                            {{ trans('cruds.role.fields.permissions') }}
                        </th> -->
                        <th class="noExport">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $role->id ?? '' }}
                            </td>
                            <td>
                                {{ $role->label ?? '' }}
                            </td>
                            <!-- <td style="width:50%;">
                                @foreach($role->permissions()->pluck('name') as $permission)
                                    <span class="badge badge-info">{{ $permission }}</span>
                                @endforeach
                            </td> -->
                            <td> 
                                <a class="btn btn-xs" href="{{ route('admin.roles.show', $role->id) }}">
                                    <i class="fa fa-eye text-success"></i>
                                </a>
                              
                                <a class="btn btn-xs" href="{{ route('admin.roles.edit', $role->id) }}">
                                    <i class="fa fa-edit text-info"></i>
                                </a>
                                 
                                 @if(checkpermission('roles','delete'))
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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