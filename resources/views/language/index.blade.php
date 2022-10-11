@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
    
    
      <h3>All Languages</h3>
    </div>

<div class="card-body">
    <div class="row">
 
       <div class="col-sm-6">
          <form action="{{ route('admin.languages.store') }}" method="post"  enctype="multipart/form-data">
          @csrf
         <div class="form-group">
             <lable> {{ trans('cruds.Languages.fields.Language_name') }}</lable>
             <input type="text" class="form-control" name="lang_name">
         </div>
         <div class="form-group">
             <lable> {{ trans('cruds.Languages.fields.Language_code') }}</lable>
             <input type="text" class="form-control" name="lang_code">
         </div>
         
         <br>

         <button class="btn btn-primary" type="submit">submit</button>
        </form>

         </div>
      <div class="col-sm-6">
          
          </div>
     </div>
     <br>
     <div class="card-header text-center bg-dark">
      <h4 >Languages</h4>
      </div>
      <br>
     <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th>
                          id  
                        </th>
                        <th>
                        {{ trans('cruds.Languages.fields.Language_name') }}
                        </th>
                        <th>
                        {{ trans('cruds.Languages.fields.Language_code') }}
                           
                        </th>
                        <th >
                        {{ trans('cruds.Languages.fields.lang_status') }}
                           
                        
                        </th>
                        <th >
                           Options
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;?>
                  @foreach($languages as $language)
                  
                   <tr>
                <form action="{{ route('admin.languages.update',[$language->lang_id]) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                @csrf
                    @method('PUT')   
                   <td>{{ $i}}</td>
                    <td>
                    <input type="hidden" name="id" value="{{ $language->lang_id }}"/>
                    <label style="display: none;">{{ $language->lang_name }}</label>
                    <input type="text" name="lang_name" value="{{ $language->lang_name }}" autocomplete="off" required/>
                    </td>
                    <td>
                    <label style="display: none;">{{ $language->lang_code }}</label>
                      <input type="text" name="lang_code" value="{{ $language->lang_code }}" autocomplete="off" required/>
                    </td>
                    <td>
                      <select name="lang_status">
                        <option value="ACTIVE" {{(($language->lang_status == 'ACTIVE')? 'selected':'')}}>Active</option>
                        <option value="INACTIVE" {{(($language->lang_status == 'INACTIVE')? 'selected':'')}}>Inactive</option>
                      </select>
                    </td>
                    <td><input class="btn btn-sm btn-success" type="submit" value="Save"></td>
                   </form>
                   </tr>
                   
                   <?php $i++;?>
                  @endforeach
                </tbody>
            </table>

</div>
</div>
@endsection
@section('scripts')
@parent
<script>
</script>
@endsection
