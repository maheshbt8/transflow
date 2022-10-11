@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.permission.title_singular') }}
    </div>

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.permissions.store") }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
            @csrf
            <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
                <label for="label" class="required">{{ trans('cruds.permission.fields.title') }}</label>
                <input type="text" id="label" name="label" class="form-control" value="{{ old('label', isset($permission) ? $permission->label : '') }}" required>
                @if($errors->has('label'))
                    <em class="invalid-feedback">
                        {{ $errors->first('label') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.permission.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                <label for="permission" class="required">{{ trans('cruds.role.fields.permissions') }}</label>
                <select name="type" id="type" class="form-control select2" onchange="get_key_param(this.value)" required>
                    <option value="parent" selected> This is parent</option>
                    @foreach($permissions as $per)
                        <option value="{{ $per->id }}">{{ $per->label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <em class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.role.fields.permissions_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}" style="display: none;" id="key_id">
                <label for="name" class="required">{{ trans('cruds.permission.title_singular') }} {{ trans('cruds.permission.fields.key') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($permission) ? $permission->name : '') }}">
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.permission.fields.title_helper') }}
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
function get_key_param(type) {
    if(type == 'parent'){
        $('#key_id').hide();
        $('#name').prop("required", false);
    }else{
        $('#key_id').show();
        $('#name').prop("required", true);
    }
}
</script>
@endsection