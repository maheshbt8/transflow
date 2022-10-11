@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
    {{ trans('cruds.locrequest.fields.add_tms') }}
    </div>

    <div class="card-body">
      <div class="d-flex justify-content">
        <form method="POST" enctype="multipart/form-data" id="data_tr_" class="add_tms">
            @csrf 
            <div class="form-group">
                <label for="source_language" class="required">Source Language</label>
             <select name="source_language" id="source_language" class="form-control select2" required >
             <option value="" >Select Source Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
            </div>

            <div class="form-group ">
                <label for="source_language" class="required">Target Language</label>
             <select name="target_language" id="target_language" class="form-control select2" required >
             <option value="" >Select Target Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
            </div>
        <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
            <label for="domain" class="required">Domain</label>
             <select name="domain_id" id="domain_id" class="form-control select2" required >
             <option value="" >Select Domain</option>
              @foreach($domains as $domain)
                              <option value="{{$domain->domain_id }}" >{{$domain->domain_name}}</option>
              @endforeach
                                        
                    </select>
              
               @if($errors->has('domain'))
                          <em class="invalid-feedback">
                              {{ $errors->first('domain') }}
                          </em>
                      @endif
                      <p class="helper-block">
                          {{ trans('cruds.user.fields.roles_helper') }}
                      </p>        
        </div>   
            

        <input type="hidden" name="created_userid">
            
            <div id="div_source_text" class="form-group ">               
                <label>{{ trans('cruds.locrequest.fields.source_text') }} </label>
                <textarea type="text" class="form-control" name="source_text" id="source_text" > </textarea>
                
            </div>
            <div id="div_target_text" class="form-group ">               
                <label>{{ trans('cruds.locrequest.fields.target_text') }} </label>
               
                <textarea type="text" class="form-control" name="target_text" id="target_text" > </textarea>
                   
            </div>
            <div class="form-group ">
                <input type="button" class="btn btn-success" name=""  id="save" value="Submit">

             

            </div>
          </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
$('body').on('click', '#save', function() {
          
                //var id = $(this).data('id');
                var source_lang=$('#source_language').val();
                var traget_lang=$('#target_language').val(); 
               
                var source_text=$('#source_text').val();
                var traget_text=$('#target_text').val();
                var domain=$('#domain_id').val();
            //    alert(source_lang);
            //    alert(traget_lang);
            //    alert(domain);
            //    alert(source_text);
            //    alert(traget_text);
              
              
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.translationmemory.store_tms');?>",
                    headers: {'x-csrf-token': _token},
                    data: {
                        'source_language':source_lang,
                        'target_language':traget_lang,
                        'domain_id':domain,
                        'source_text': source_text,
                        'target_text':traget_text,
                    },
                     
                    success: function(res) {
                      if(res == 1){
                       alert('Updated Successfully');
                      }else{
                        alert('Not Updated');
                      }
                    }
                });
        
        });

</script>

@endsection