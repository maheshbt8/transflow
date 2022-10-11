@extends('layouts.admin')
@section('content')

</style>
<div class="row">

  <div class="col-md-6">
            <form id="form_sms"action="{{ route('admin.personaldetails.update_address',[$edit_address->id]) }}" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
            @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Address Details</h2>
                    </header>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 ">Address<span class="required">*</span></label>
                            <div class="col-sm-9">
                            <textarea name="address" class="form-control" id="" cols="10" rows="5"><?php echo  old('name', isset($edit_address->address) ? str_replace('<br />','',$edit_address->address) : '') ?> </textarea>
                              
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="{{$edit_address->id}}">
                                <input type="hidden" name="type" value="{{$edit_address->type}}">
                                <br>
                        </div>
                        
                         <input type="text" name="user_id" placeholder="Org Name" class="form-control" value="{{ old('name', isset($edit_address->user_id) ? $edit_address->user_id : '') }}" hidden> 
                         
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>

                    </div>
            
            </section>
           
        </form>
    </div>

   
</div>


@endsection