@extends('layouts.admin')
@section('content')
<?php 
$inr_per=$currency_type='';
if($service_type != ''){
    $inr_per=gettabledata('loc_service','type',['id'=>$service_type]);
}
if($currency != ''){
    $currency_type=gettabledata('currencies','currency_code',['id'=>$currency]);
}
?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
              Rate card
            </div>
        	
        	@if(Session::has('flash_message'))
                  <div class="alert alert-success">
                      {{ Session::get('flash_message') }}
                  </div>
        	@endif
           <!--  -->
            <div class="card-body">
            <div class="justify-content">
                <form action="" method="GET" enctype="multipart/form-data">
        			<!-- Organization block -->
        			 
        			<!-- Organization block -->
                    <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                     <?php
                        if(get_user_org('name')== "administrator"){
                      ?>
        			  <label for="roles" class="required">Organization</label>
        			 <select name="organization" id="roles" class="form-control select2 select_org_list" required >
        			 <option value="" >Select Organization</option>
                            @foreach($kptorganizations as $org)
                                <option value="{{ $org->org_id }}" {{ $organization == $org->org_id? 'selected' : '' }} >{{ $org->org_name }}</option>
                            @endforeach
                        </select>
        				
        				 @if($errors->has('roles'))
                            <em class="invalid-feedback">
                                {{ $errors->first('roles') }}
                            </em>
                        @endif
                        <div class="invalid-feedback" for="">Must select  your organization</div>
                        <?php
                        }else{
                    ?> 
                    <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="organization"/>
                    <?php }?> 
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.roles_helper') }}
                        </p>				
        			 </div>
        			<!-- Organization block -->
        			<!-- languages -->
                    <div class="form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                        <label for="source_language" class="required">Source Language</label>
                                        <select name="source_language" id="source_language" class="form-control select2" required>
                                            <option value="">Select Source Language</option>
                                            @foreach($loc_languages as $key => $lang)
                                            <option value="{{ $lang->lang_id }}" {{ $source_language == $lang->lang_id? 'selected' : '' }}>{{ $lang->lang_name }}</option>
                                            @endforeach
                                        </select>

                                        @if($errors->has('source_language'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('source_language') }}
                                        </em>
                                        @endif
                                        <div class="invalid-feedback" for="">Must Select your Source Language</div>
                    </div>
        			
        			
        			<!-- Sub Organization block -->

                    <!----services ---->

                    <div class="form-group {{ $errors->has('service_type') ? 'has-error' : '' }}">
                                    <label for="service_type" class="required">Service Type</label>
                                    <select name="service_type" id="service_type" class="form-control select2" required >
                                <option value="">Select Service</option>  
                                    @foreach($loc_services as $service)
                                        <option value="{{$service->id}}" {{ $service_type == $service->id? 'selected' : '' }}>{{$service->service_type}}</option>
                                        @endforeach
                                    </select>
                                   
                                    @if($errors->has('service_type'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('service_type') }}
                                    </em>
                                    @endif
                                    <div class="invalid-feedback" for="">Must Select your source Type</div>
                    </div>
                    
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Currency" class="required">Currency:</label>
                        <select name="currency" id="currency" class="form-control select2" required>
                        @foreach($currency_list as $cl)
                        <option value="{{$cl->id}}" {{ $currency == $cl->id? 'selected' : '' }}>{{$cl->currency_name .' - '. $cl->currency_code}}</option>
                        @endforeach
                        </select>
                    </div>	
        			
                    <div>
                        <input class="btn btn-danger" type="submit" class="store" value="{{ trans('global.search') }}">
                    </div>
                </form>
                </div>

          
            </div>
        </div>
    </div>
    <?php  
    if($organization != '' && $source_language != '' && $service_type != ''){?>
     
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
              Rate card <a href="{{ route('admin.ratecard.sampledownload') }}?organization={{$organization}}&source_language={{$source_language}}&service_type={{$service_type}}&currency={{$currency}}" class="btn btn-xs btn-info float-right">Download Sample</a>
            </div>
            @if(Session::has('flash_message'))
                  <div class="alert alert-success">
                      {{ Session::get('flash_message') }}
                  </div>
            @endif
           
           <!--  -->
            <div class="card-body">
            <div class="d-flex justify-content">
                <form action="{{ route('admin.ratecard.updateratecard') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Organization block -->
                     
                    <!-- Organization block -->
                    <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                     <?php
                        if(get_user_org('name')== "administrator"){
                      ?>
                      <label for="roles" class="required">Organization</label>
                     <select name="organization" id="roles" class="form-control select2 select_org_list" required >
                     <option value="" >Select Organization</option>
                            @foreach($kptorganizations as $org)
                                <option value="{{ $org->org_id }}" {{ $organization == $org->org_id? 'selected' : '' }} >{{ $org->org_name }}</option>
                            @endforeach
                        </select>
                        
                         @if($errors->has('roles'))
                            <em class="invalid-feedback">
                                {{ $errors->first('roles') }}
                            </em>
                        @endif
                        <div class="invalid-feedback" for="">Must select  your organization</div>
                        <?php
                        }else{
                    ?> 
                    <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="organization"/>
                    <?php }?> 
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.roles_helper') }}
                        </p>                
                     </div>
                    <!-- Organization block -->
                    <!-- languages -->
                    <div class="form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                        <label for="source_language" class="required">Source Language</label>
                        <select name="source_language" id="source_language" class="form-control select2" required>
                            <option value="">Select Source Language</option>
                            @foreach($loc_languages as $key => $lang)
                            <option value="{{ $lang->lang_id }}" {{ $source_language == $lang->lang_id? 'selected' : '' }}>{{ $lang->lang_name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('source_language'))
                            <em class="invalid-feedback">
                                {{ $errors->first('source_language') }}
                            </em>
                        @endif
                        <div class="invalid-feedback" for="">Must Select your Source Language</div>
                    </div>
                    <div class="form-group {{ $errors->has('service_type') ? 'has-error' : '' }}">
                        <label for="service_type" class="required">Service Type</label>
                        <select name="service_type" id="service_type" class="form-control select2" required >
                            <option value="">Select Service</option>  
                            @foreach($loc_services as $service)
                            <option value="{{$service->id}}" {{ $service_type == $service->id? 'selected' : '' }}>{{$service->service_type}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('service_type'))
                            <em class="invalid-feedback">
                                {{ $errors->first('service_type') }}
                            </em>
                        @endif
                        <div class="invalid-feedback" for="">Must Select your source Type</div>
                    </div>
                    <div class="form-group {{ $errors->has('service_type') ? 'has-error' : '' }}">
                        <label for="service_type" class="required">Upload Ratecard <span class="error">(.csv)</span></label>
                        <input type="file" name="ratecard_file" value="" required="" class="form-control">
                        @if($errors->has('ratecard_file'))
                            <em class="invalid-feedback">
                                {{ $errors->first('ratecard_file') }}
                            </em>
                        @endif
                    </div>  
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Currency" class="required">Currency:</label>
                        <select name="currency" id="currency" class="form-control select2" required>
                        @foreach($currency_list as $cl)
                        <option value="{{$cl->id}}" {{ $currency == $cl->id? 'selected' : '' }}>{{$cl->currency_name .' - '. $cl->currency_code }}</option>
                        @endforeach
                        </select>
                    </div>  
                    <div>
                        <input class="btn btn-danger" type="submit" class="store" value="{{ trans('global.submit') }}">
                    </div>
                </form>
                </div>

          
            </div>
        </div>
    </div>
    <?php }?>
</div>
<?php if($organization != '' && $source_language != '' && $service_type != ''){?>
<div class="card">
    <div class="card-header">
        <h5>Rate card List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>{{ trans('cruds.Languages.fields.Language_name') }}</th>
                        <?php if($inr_per == 'slab_minute'){
                            $t_array=[15,30,45,60];
                            $tl_array=['1-15 minutes','16-30 minutes','31-45 minutes','46-60 minutes'];
                            $l_array=['minute_cost_15','minute_cost_30','minute_cost_45','minute_cost_60'];
                        }elseif($inr_per == 'page'){
                            $t_array=[''];
                            $tl_array=['Per Page'];
                            $l_array=['page_cost'];
                        }elseif($inr_per == 'minute'){
                            $t_array=[''];
                            $tl_array=['Per Minute'];
                            $l_array=['minute_cost'];
                        }else{
                            $t_array=[''];
                            $tl_array=['Per Word'];
                            $l_array=['word_cost'];
                        }
                        for ($ci=0; $ci < count($t_array); $ci++) { 
                        ?>
                        <th>{{ $currency_type != ''? ucwords($currency_type) : ''}} {{ $inr_per!= ''? ucwords('('.$tl_array[$ci].')') : ''}}</th>
                        <?php }?>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;?>
                    @foreach($loc_languages as $language)
                    <tr>
                        <td>{{ $i}}</td>
                        <td>{{ $language->lang_name }}</td>
                        <?php 
                        $target_price='';
                        if($organization != '' && $source_language != '' && $service_type != ''){
                            $target_price=$loc_ratecard->get_target_price($organization,$service_type,$currency,$source_language,$language->lang_id);
                        }
                        for ($cl=0; $cl < count($l_array); $cl++) {
                            $input_type=$l_array[$cl];
                        ?>
                        <td><?php 
                        if (checkpermission('ratecard','add')) {
                            if($inr_per == 'slab_minute'){
                                $price_field_id='target_price_'.$language->lang_id.'_'.$t_array[$cl];
                            }else{
                                $price_field_id='target_price_'.$language->lang_id;
                            }
                            if($target_price){
                                ?>
                                <input type="text" value="{{ $target_price->$input_type }}" name="target_price" id="{{ $price_field_id }}"/>
                                <?php 
                            }else{
                                ?>
                                <input type="text" value="0" name="target_price" id="{{ $price_field_id }}"/>
                                <?php
                            }
                        }else{
                           if($target_price){
                                echo $target_price->$input_type;
                           }else{   
                            echo 0;
                           } 
                        }
                        ?>
                        </td>
                        <?php }?>
                        <td>
                            <?php if (checkpermission('ratecard','add')) {?>
                                <a href="#" class="btn btn-sm btn-info store" data-id="{{ $language->lang_id }}">Save</a>
                            <?php }?>
                        </td>
                        <?php $i++; ?>
                    </tr>
                    @endforeach
                </tbody>
            </table>  
        </div> 
     </div>
 </div>
<?php }?>
@endsection
@section('scripts')
@parent
<script>

$('body').on('click', '.store', function() {
    if (confirm("Are You Sure Want Update?") == true) {
        var target_id = $(this).data('id');
        <?php 
        if($inr_per == 'slab_minute'){
        ?>
        var target_price_15=$('#target_price_'+target_id+'_15').val();
        var target_price_30=$('#target_price_'+target_id+'_30').val();
        var target_price_45=$('#target_price_'+target_id+'_45').val();
        var target_price_60=$('#target_price_'+target_id+'_60').val();
        if(target_price_15 > 0 || target_price_30 > 0 || target_price_45 > 0 || target_price_60 > 0){    
            $.ajax({
                type: "post",
                url: "<?php echo route('admin.ratecard.store');?>",
                headers: {'x-csrf-token': _token},
                data: {
                    'organization':'<?php echo $organization;?>',
                    'source_language':'<?php echo $source_language;?>',
                    'target_language':target_id,
                    'service_type':'<?php echo $service_type;?>',
                    'currency':'<?php echo $currency;?>',
                    'target_price_15':target_price_15,
                    'target_price_30':target_price_30,
                    'target_price_45':target_price_45,
                    'target_price_60':target_price_60
                },
                success: function(res) {
                    alert('Updated Successfully.');
                }
            });
        }else{

        }
        <?php 
        }else{?>
        var target_price=$('#target_price_'+target_id).val();
        if(target_price > 0){    
            $.ajax({
                type: "post",
                url: "<?php echo route('admin.ratecard.store');?>",
                headers: {'x-csrf-token': _token},
                data: {
                    'organization':'<?php echo $organization;?>',
                    'source_language':'<?php echo $source_language;?>',
                    'target_language':target_id,
                    'service_type':'<?php echo $service_type;?>',
                    'currency':'<?php echo $currency;?>',
                    'target_price':target_price
                },
                success: function(res) {
                    alert('Updated Successfully.');
                }
            });
        }else{

        }
    <?php }?>
    }
});
</script>        
@endsection
