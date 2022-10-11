@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}
    </div>

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.roles.update", [$role->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.role.fields.title') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($role) ? $role->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.role.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                <label for="permission" class="required">{{ trans('cruds.role.fields.permissions') }}</label>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <!-- <th>Sl.No</th> -->
                            <th>{{ trans('cruds.role.fields.permissions') }}</th>
                            <th><!-- <input type="checkbox" value="view" onclick="checkall('view')" id="selectview" checked=""> --> {{ trans('cruds.role.fields.view') }}</th>
                            <th><!-- <input type="checkbox" value="add" onclick="checkall('add')" id="selectadd" checked=""> --> {{ trans('cruds.role.fields.add') }}</th>
                            <th><!-- <input type="checkbox" value="update" onclick="checkall('update')" id="selectupdate" checked=""> --> {{ trans('cruds.role.fields.update') }}</th>
                            <th><!-- <input type="checkbox" value="delete" onclick="checkall('delete')" id="selectdelete" checked=""> --> {{ trans('cruds.role.fields.delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                @csrf
                <input type="hidden" name="role_id" value="{{$role->id}}">
                @foreach($permissions as $par)
                <tr>
                  <td><b>{{$par->label}}</b></td>
                  <td>
                    <input type="checkbox" value="{{$par->id}}" onclick="checkall('{{$par->id}}','view')" id="selectview{{$par->id}}">
                  </td>
                  <td>
                    <input type="checkbox" value="{{$par->id}}" onclick="checkall('{{$par->id}}','add')" id="selectadd{{$par->id}}">
                  </td>
                  <td>
                    <input type="checkbox" value="{{$par->id}}" onclick="checkall('{{$par->id}}','update')" id="selectupdate{{$par->id}}">
                  </td>
                  <td>
                    <input type="checkbox" value="{{$par->id}}" onclick="checkall('{{$par->id}}','delete')" id="selectdelete{{$par->id}}">
                  </td>
                </tr>
                @foreach($par->childs as $child)
                <tr>
                  <td>{{$child->name}}</td>
                  <td>
                    <input type="checkbox" value="{{$child->id}}" onclick="single('{{$par->id}}','view')" class="caseview caseview{{$par->id}}" name="view_permissions[{{$child->id}}]"  {{(checkrolepermission($role->id,$child->id,'view')? 'checked' : '')}}>
                  </td>
                  <td>
                    <input type="checkbox" value="{{$child->id}}" class="caseadd caseadd{{$par->id}}" name="add_permissions[{{$child->id}}]" onclick="single('{{$par->id}}','add')" {{(checkrolepermission($role->id,$child->id,'add')? 'checked' : '')}}>
                  </td>
                  <td>
                    <input type="checkbox" value="{{$child->id}}" onclick="single('{{$par->id}}','update')" class="caseupdate caseupdate{{$par->id}}" name="update_permissions[{{$child->id}}]"  {{(checkrolepermission($role->id,$child->id,'update')? 'checked' : '')}}>
                  </td>
                  <td>
                    <input type="checkbox" value="{{$child->id}}" onclick="single('{{$par->id}}','delete')" class="casedelete casedelete{{$par->id}}" name="delete_permissions[{{$child->id}}]"  {{(checkrolepermission($role->id,$child->id,'delete')? 'checked' : '')}}>
                  </td>
                </tr>
                @endforeach
                @endforeach
              </tbody>
                </table>
                @if($errors->has('permission'))
                    <em class="invalid-feedback">
                        {{ $errors->first('permission') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.role.fields.permissions_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
function checkall(id,type) {
  if ($('#select'+type+id).is(':checked')) {
    $('.case'+type+id).prop('checked', true);
        }
        else {
        $('.case'+type+id).prop('checked', false);
        }
}
function single(id,type) {
  if($(".case"+type+id).length == $(".case"+type+id+":checked").length) {
      $("#select"+type+id).prop("checked", true);
    } else {
      $("#select"+type+id).prop("checked", false);
    }
}
</script>
@endsection