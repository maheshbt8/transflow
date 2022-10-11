@extends('layouts.admin')
@section('content')

</style>
<p>
    @if($type_val == 'org')
  <a class="btn btn-default" href="{{ route('admin.org.edit',$type_id) }}" role="button">
    Organization
  </a>
  @elseif($type_val == 'user')
  <a class="btn btn-default" href="{{ route('admin.authenticatedusers.edit',$type_id) }}" role="button">
    Users
  </a>
  @elseif($type_val == 'client_org')
  <a class="btn btn-default" href="{{ route('admin.clientorg.edit',$type_id) }}" role="button">
    Client
  </a>
  @endif
  @if($type_val != 'client_org')
  <a class="btn btn-default" href="{{ route('admin.personaldetails.index',[$type_val=>$type_id]) }}" role="button">
    Bank Details
  </a>
  @endif
  <a class="btn btn-default" href="{{ route('admin.personaldetails.address', [$type_val=>$type_id]) }}" role="button">
    Address
  </a>
  <a class="btn btn-primary" href="{{ route('admin.personaldetails.personal_data', [$type_val=>$type_id]) }}" role="button">
    Personal Data
  </a>
</p>




            <form id = "form_sms"action = "{{ route('admin.personaldetails.add_personal') }}" class="needs-validation" novalidate=""  method = "post" enctype = "multipart/form-data">
            @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">personal Details</h2>
                    </header>
                    <div class="card-body">

                    <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">Register Number</label>
                            <div class="col-sm-6">
                             
                                <input type="text" name="register_no" class="form-control" id="" required >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">GST Number<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <input type="text" name="gst" class="form-control" id="" required >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">PAN Number<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <input  type="text" name="pan" class="form-control" id="pan" required >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">State Code<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <input name="state_code" class="form-control" id="" >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                        <div class="form-group row {{ $errors->has('pan_img') ? 'has-error' : '' }}">               
                        <label class="col-sm-3 text-lg-center "> Upload Pan Card <span class="required">*</span></label>
                     <div class="col-sm-6">
                     <input type="file" class="form-control" name="pan_img"  >
                     <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                           </div>
                     </div>
                   
                     <div class="form-group row">               
                        <label class=" col-sm-3 text-lg-center">Upload MSME Registration Certificate. <span class="required">*</span> </label>
                        <div class="col-sm-6">
                        <input type="file" class="form-control" name="msme_file">
                        <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                        <div class="invalid-feedback">Please Upload MSME Registration Certificate.?</div>
                        </div>
                    </div>
                   
                        <input type="hidden" name="user_id" placeholder="Org Name" class="form-control" value="{{ $type_id }}" > 
                         <input type="hidden" name="type" placeholder="Org Name" class="form-control" value="{{ $type_val }}" > 
                         
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>

                    </div>
            
            </section>
           
        </form>
       


   
            <form id="form-smtp" action="" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
            @csrf  
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">personal List</h2>
                    </header>
                    <div class="card-body">
                        <table class=" table table-bordered table-striped table-hover datatable_table">
                            <tr>
                                <th>ID</th>
                                <th>image</th>
                                <th>MSME Certificate. </th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                           
                            <?php $i=1;?>
                       
                            @foreach($details as $data)
                          
                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                <?php if($data->pan_file_path != ''){ ?>
                                    <?php $get_ext=explode('.',$data->pan_file_path);
                                    $ext=end($get_ext);
                                    if(in_array(strtolower($ext),['jpg','jpeg','png','gif','svg'])){
                                    ?>
                            <img src="<?php echo env('AWS_CDN_URL') . '/pan/'.$data->pan_file_path ;?>" height="100" width="100"/>
                            <a type="button" href="<?php echo env('AWS_CDN_URL') . '/pan/'.$data->pan_file_path ;?>" target="_blank" download><i class="fa fa-eye"></i></a>
                               <?php }else{?>
                                <div class="embed-responsive embed-responsive-16by9">
  <iframe class="embed-responsive-item" src="<?php echo env('AWS_CDN_URL') . '/pan/'.$data->pan_file_path ;?>" allowfullscreen></iframe>
</div>
<a type="button" href="<?php echo env('AWS_CDN_URL') . '/pan/'.$data->pan_file_path ;?>" target="_blank" download><i class="fa fa-eye"></i></a>
                               <?php }?>
                               <?php } ?>
                            </td>
                            <td> 
                            <?php if($data->msme_file_path != ''){ ?>
                           
                                    <?php $get_ext=explode('.',$data->msme_file_path);
                                    $ext=end($get_ext);
                                    if(in_array(strtolower($ext),['jpg','jpeg','png','gif','svg'])){
                                    ?>
                            <img src="<?php echo env('AWS_CDN_URL') . '/msme_file/'.$data->msme_file_path ;?>" height="100" width="100"/>
                            <a type="button" href="<?php echo env('AWS_CDN_URL') . '/msme_file/'.$data->msme_file_path ;?>" target="_blank" download><i class="fa fa-eye"></i></a>
                               <?php }else{?>
                                <div class="embed-responsive embed-responsive-16by9">
  <iframe class="embed-responsive-item" src="<?php echo env('AWS_CDN_URL') . '/msme_file/'.$data->msme_file_path ;?>" allowfullscreen></iframe>
</div>
<a type="button" href="<?php echo env('AWS_CDN_URL') . '/msme_file/'.$data->msme_file_path ;?>" target="_blank" download><i class="fa fa-eye"></i></a>
                               <?php }?>
                               <?php } ?>
                            </td>
                           
                                <td>
                                <ul style="list-style-type:none;" >
                                    <li>Register No: {{$data->register_no}}</li>
                                        
                                       <li>GST No: {{$data->gst}}</li>
                                   
                                   
                                    
                                        <li>PAN No: {{$data->pan}}</li>
                                   
                                    
                                        <li>State Code: {{$data->state_code}}</li>
                                  
                                    
                                </ul>
                                </td>
                                <td><a href="{{ route('admin.personaldetails.edit_personal',[$data->id]) }}"><i class="fa fa-edit text-info"></i></a> | <a href="{{ route('admin.personaldetails.delete_data',[$data->id]) }}" ><i class="fa fa-trash text-danger"></i></a>
                               
                     </form>
                            </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
            
            </section></form>
    


<script>
    var input = document.getElementById('pan');

input.onkeyup = function(){
    this.value = this.value.toUpperCase();
}
</script>
@endsection
