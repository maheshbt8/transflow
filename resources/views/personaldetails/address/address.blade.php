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
  <a class="btn btn-primary" href="{{ route('admin.personaldetails.address', [$type_val=>$type_id]) }}" role="button">
    Address
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.personal_data', [$type_val=>$type_id]) }}" role="button">
    Personal Data
  </a>
</p>



            <form id="form_sms"action="{{ route('admin.personaldetails.add_address') }}" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
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
                            <label class="col-sm-3 text-lg-center ">Address<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <textarea name="address" class="form-control" id="" rows="5"></textarea>
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
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
                        <h2 class="card-title ven">Address List</h2>
                    </header>
                    <div class="card-body">
                        <table class=" table table-bordered table-striped table-hover datatable_table">
                            <tr>
                                <th>ID</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                            <?php $i=1;?>
                            @foreach($address as $item)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                Address: <address><?php echo $item->address;?></address>
                                    <!-- <ul style="list-style-type:none;" >
                                    @if(!empty($item->address))
                                        <li>Address: <address>{{str_replace('<br />>','<br/>\n',$item->address)}}</address></li>
                                     @endif
                                     @if(!empty($item->state_code))
                                        <li>State Code: {{$item->state_code}}</li>
                                     @endif
                                     @if(!empty($item->gst))
                                        <li>PAN: {{$item->gst}}</li>
                                     @endif
                                     @if(!empty($item->pan))
                                        <li>GSTIN: {{$item->pan}}</li>
                                     @endif
                                   

                                  
                                   
                                        
                                    </ul> -->
                                </td>
                                <td><a href="{{ route('admin.personaldetails.edit_address',[$item->id]) }}"><i class="fa fa-edit text-info"></i></a> | <a href="{{ route('admin.personaldetails.delete_address',[$item->id]) }}" ><i class="fa fa-trash text-danger"></i></a>
                               
                     </form>
                            </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
            
            </section></form>
    



@endsection