@extends('layouts.admin')
@section('content')
<style>
    .bank {
  
  height: 650px;
  overflow: scroll;
}
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
  <a class="btn btn-primary" href="{{ route('admin.personaldetails.index',[$type_val=>$type_id]) }}" role="button">
    Bank Details
  </a>
  @endif
  <a class="btn btn-default" href="{{ route('admin.personaldetails.address', [$type_val=>$type_id]) }}" role="button">
    Address
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.personal_data', [$type_val=>$type_id]) }}" role="button">
    Personal Data
  </a>
</p>



  
            <form id="form_sms"action="{{ route('admin.personaldetails.bank_details') }}" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
            @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Bank Details</h2>
                    </header>
                    <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="col ">Bank Name<span class="required">*</span></label>
                            <div class="col">
                                <input type="text" name="bank_name" class="form-control" required>
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <br>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col ">Bank Address <span class="required">*</span></label>
                            <div class="col">
                                <input type="text" name="bank_address" class="form-control"  required>
                            </div>
                            <div class="invalid-feedback">System Title ?</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col ">Account Name<span class="required">*</span></label>
                            <div class="col">
                                <input type="text" name="account_name" class="form-control">
                            </div>
                            <div class="invalid-feedback">Account Name?</div>
                        </div>
                   </div>    
                   <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="col ">Account Number<span class="required">*</span></label>
                            <div class="col ">
                                <input type="text" class="form-control" name="account_number" required >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group col-md-4">
                            <label class="col ">IFSC Code<span class="required"></span></label>
                            <div class="col">
                                <input type="text" class="form-control" name="ifsc_code" value="" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group col-md-4">
                            <label class="col ">Routing Number<span class="required"></span></label>
                            <div class="col ">
                                <input type="text" class="form-control" name="routing_number"  >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                        
                       </div>
                       <div class="form-row">
                         <div class="form-group col-md-4">
                            <label class="col">SWIFT Code<span class="required"></span></label>
                            <div class="col ">
                                <input type="text" class="form-control" name="swift_code"   >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group col-md-4">
                            <label class="col">Sort Code<span class="required"></span></label>
                            <div class="col">
                                <input type="text" class="form-control" name="sort_code"  >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group col-md-4">
                            <label class="col ">BIC<span class="required"></span></label>
                            <div class="col">
                                <input type="text" class="form-control" name="bic"  >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                     </div>
                     <input type="hidden" name="user_id" placeholder="Org Name" class="form-control" value="{{ $type_id }}" > 
                         <input type="hidden" name="type" placeholder="Org Name" class="form-control" value="{{ $type_val }}" > 

                        <div class=" justify-content-end">
                            <div class="col-md-6">
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
                        <h2 class="card-title ven">Bank Details List</h2>
                    </header>
                    <div class="card-body">
                        <table class=" table table-bordered table-striped table-hover datatable_table">
                            <tr>
                                <th>ID</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                            <?php $i=1;?>
                            @foreach($bank_details as $bank)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                    <ul style="list-style-type:none;" >
                                    @if(!empty($bank->bank_name))
                                        <li>Bank Name: {{$bank->bank_name}}</li>
                                     @endif
                                     @if(!empty($bank->bank_address))
                                     <li>Bank Address: {{$bank->bank_address}}</li>
                                     @endif
                                     @if(!empty($bank->account_name))
                                        <li>Account Name: {{$bank->account_name}}</li>
                                     @endif
                                     @if(!empty($bank->account_number))
                                        <li>Account Number: {{$bank->account_number}}</li>
                                     @endif
                                        @if(!empty($bank->routing_number))
                                        <li>Routing Number: {{$bank->routing_number}}</li>
                                        @endif
                                        @if(!empty($bank->ifsc_code))
                                        <li>IFSC Code: {{$bank->ifsc_code}}</li>
                                        @endif
                                        @if(!empty($bank->swift_code))
                                        <li>SWIFT Code: {{$bank->swift_code}}</li>
                                        @endif
                                        @if(!empty($bank->sort_code))
                                        <li>Sort Code: {{$bank->sort_code}}</li>
                                        @endif
                                        @if(!empty($bank->bic))
                                        <li>BIC: {{$bank->bic}}</li>
                                        @endif
                                        
                                    </ul>
                                </td>
                                <td><a href="{{ route('admin.personaldetails.edit_bankdetails', [$bank->id]) }}"><i class="fa fa-edit text-info"></i></a> | <a href="{{ route('admin.personaldetails.delete_bank',[$bank->id]) }}" ><i class="fa fa-trash text-danger"></i></a>
                               
                          </form>
                            </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
            
            </section></form>
        </div>

        
               
         



@endsection