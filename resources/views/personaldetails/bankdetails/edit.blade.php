@extends('layouts.admin')
@section('content')

<div class="row">

  <div class="col-md-6">
            <form id="form_sms"action="{{ route('admin.personaldetails.update_bankdetails',[$edit_bank->id])}}" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
            @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <?php //echo "<pre>"; print_r($edit_bank);die; ?>
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven"> Edit Bank Details</h2>
                    </header>
                    <div class="card-body">
                   
                        <div class="form-group row">
                            <label class="col-sm-3 ">Bank Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="bank_name" class="form-control" value="{{ old('name', isset($edit_bank->bank_name) ? $edit_bank->bank_name : '') }}" >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <br>
                                <input type="hidden" name="id" value="{{$edit_bank->id}}">
                                <input type="hidden" name="type" value="{{$edit_bank->type}}">
                        </div>
                      
                       
                        <div class="form-group row">
                            <label class="col-sm-3 ">Bank Address <span class="required"></span></label>
                            <div class="col-sm-9">
                                <input type="text" name="bank_address" class="form-control" value="{{ old('name', isset($edit_bank->bank_address) ? $edit_bank->bank_address : '') }}"  >
                            </div>
                            <div class="invalid-feedback">System Title ?</div>
                        </div>
                     
                        <div class="form-group row">
                            <label class="col-sm-3 ">Account Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="account_name" class="form-control" value="{{ old('name', isset($edit_bank->account_name) ? $edit_bank->account_name : '') }}">
                            </div>
                            <div class="invalid-feedback">Account Name?</div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Account Number<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="account_number"  value="{{ old('name', isset($edit_bank->account_number) ? $edit_bank->account_number : '') }}">
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group row">
                            <label class="col-sm-3 ">Routing Number<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="routing_number" value="{{ old('name', isset($edit_bank->routing_number) ? $edit_bank->routing_number : '') }}" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group row">
                            <label class="col-sm-3 ">IFSC Code<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="ifsc_code" value="{{ old('name', isset($edit_bank->ifsc_code) ? $edit_bank->ifsc_code : '') }}" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>

                         <div class="form-group row">
                            <label class="col-sm-3 ">SWIFT Code<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="swift_code" value="{{ old('name', isset($edit_bank->swift_code) ? $edit_bank->swift_code : '') }}"  >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group row">
                            <label class="col-sm-3 ">Sort Code<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="sort_code" value="{{ old('name', isset($edit_bank->sort_code) ? $edit_bank->sort_code : '') }}" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <div class="form-group row">
                            <label class="col-sm-3 ">BIC<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="bic" value="{{ old('name', isset($edit_bank->bic) ? $edit_bank->bic : '') }}"  >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                         </div>
                         <input type="text" name="user_id" placeholder="Org Name" class="form-control" value="{{ old('name', isset($edit_bank->user_id) ? $edit_bank->user_id : '') }}" hidden> 

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>

                    </div>
            
            </section>
           
        </form>
        </div>
@endsection