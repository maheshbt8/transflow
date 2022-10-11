@extends('layouts.admin')

@section('content')
<div class="content">
<?php 
 $user_role = Auth::user()->roles[0]->name;?>

<!-- <div class="card"> -->
<!--   <div class="card-header">
  <h3 class="card-title">Dashboard</h3>
  </div> -->
  <!-- <div class="card-body"> -->
  <div class="row">
    <?php if($user_role == 'administrator'){?>
      <div class="col-12 col-sm-6 col-md-3">
        <a  href="{{ route("admin.org.index") }}" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-building"></i></span>
            
            <div class="info-box-content">
              <span class="info-box-text">Organizations</span>
              <span class="info-box-number">
                {{ $orgcount }}
              </span>
             
            </div>
          
          </div>
        </a>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <a  href="{{ route("admin.authenticatedusers.index") }}" class="small-box-footer"> 
          <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">Users</span>
              <span class="info-box-number">
                {{ $usercount }}
              </span>
            </div>
          
          </div>
       </a> 
      </div>
        <!-- <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $orgcount }}</h3>

              <p>Organizations</p>
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a  href="{{ route("admin.org.index") }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div> -->
        <!-- ./col -->
        <!-- <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
            <h3>{{$usercount}}</h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="{{ route("admin.authenticatedusers.index") }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div> -->

        <?php } elseif($user_role == 'sales'){?>
       
      <div class="col-12 col-sm-6 col-md-3">
       <a href="{{ route("admin.quotegeneration.index") }}" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
             <div class="info-box-content">
              <span class="info-box-text">Assign Quotes</span>
              <span class="info-box-number">
                {{ $assign_quotes }}
              </span>
            </div>
          </div>
        </a>  
      </div>
        <!-- ./col -->

      <div class="col-12 col-sm-6 col-md-3">
        <a href="{{ route("admin.quotegeneration.index") }}" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tasks"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">Pending Quotes</span>
              <span class="info-box-number">
              {{$pending_quotes}}
              </span>
            </div>
           </div>
        </a>
      </div>
           <!-- small box -->

      <div class="col-12 col-sm-6 col-md-3">
        <a href="{{ route("admin.quotegeneration.index") }}" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">Total Quotes</span>
              <span class="info-box-number">
              {{$total_quotes}}
              </span>
            </div>
           
          </div>
        </a>
      </div>
        


       
      <?php } ?>
      <?php if($user_role != 'administrator' && $user_role != 'sales'){?>
        <!-- <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $active_count ?? 0 }}</h3>

              <p>Active</p>
            </div>
            <div class="icon">
              <i class="fa fa-list"></i>
            </div>
            <a href="{{route('admin.request.todoactivities')}}/?status=new" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div> -->
      <div class="col-12 col-sm-6 col-md-3">
        <a href="{{route('admin.request.todoactivities')}}/?status=new" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">Active</span>
              <span class="info-box-number">
                {{ $active_count ?? 0 }}
              </span>
            </div>
          </div>
          </a> 
      </div>
          <!-- small box -->
      <div class="col-12 col-sm-6 col-md-3">
        <a href="{{route('admin.request.todoactivities')}}/?status=inprogress" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tasks"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">In-Progress</span>
              <span class="info-box-number">
              {{ $inprogress_count ?? 0 }}
              </span>
            </div>
           
          </div>
         </a> 
      </div>




        <!-- <div class="col-lg-3 col-6">
        
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $inprogress_count ?? 0 }}</h3>

              <p>In-Progress</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="{{route('admin.request.todoactivities')}}/?status=inprogress" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div> -->
         <!-- small box -->



         <div class="col-12 col-sm-6 col-md-3">
         <a href="{{route('admin.request.todoactivities')}}/?status=completed" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">Completed</span>
              <span class="info-box-number">
              {{ $completed_count ?? 0 }}
              </span>
            </div>
           
          </div>
          </a> 
        </div>







        <!-- <div class="col-lg-3 col-6">
         
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $completed_count ?? 0 }}</h3>

              <p>Completed</p>
            </div>
            <div class="icon">
              <i class="fa fa-check"></i>
            </div>
            <a href="{{route('admin.request.todoactivities')}}/?status=completed" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div> -->
        <?php if($user_role == 'orgadmin'){?>
          <div class="col-12 col-sm-6 col-md-3">
          <a href="{{ route("admin.client.index") }}" class="font">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i> </span>
           
            <div class="info-box-content">
              <span class="info-box-text">Client users</span>
              <span class="info-box-number">
              {{ $client_users ?? 0 }}
              </span>
            </div>
          
          </div>
          </a> 
        </div>


        <div class="col-12 col-sm-6 col-md-3">
        <a href="{{ route("admin.authenticatedusers.index") }}" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
           
            <div class="info-box-content">
              <span class="info-box-text">Users</span>
              <span class="info-box-number">
              {{ $authenticated_users}}
              </span>
            </div>
           
          </div>
          </a> 
        </div>



        <div class="col-12 col-sm-6 col-md-3">
        <a href="{{ route("admin.clientorg.index") }}" class="small-box-footer">
          <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-building"></i></span>
            
            <div class="info-box-content">
              <span class="info-box-text">Client organization</span>
              <span class="info-box-number">
              {{$clientorganizations}}
              </span>
            </div>
           
          </div>
          </a> 
        </div>
        <?php } ?>
        <?php  }?>
  </div>
   <!--  </div>
  </div> -->
<?php if($user_role == 'orgadmin' || $user_role == 'projectmanager' || $user_role == 'finance' || $user_role == 'sales'){?>
<!-- <div class="card">
  <div class="card-header">
     Client Invoice List
  </div>
    <div class="card-body">
      <div class="table-responsive">
          <table class=" table table-bordered table-striped table-hover">
              <thead>
                  <tr>
                    <th>id</th>
                    <th>Quote Id / Invoice No.</th>
                    <th>Create Date</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="">Action</th>
                  </tr>
              </thead>
             
            
            <tbody>
              <?php $i = 0;$j=0;
             
              ?>
              @foreach($last15days as $civ)
              <?php  $client_inv_data=$loc_request->getpendingivoice($civ->req_id);
              
              ?>
              <?php ?>
              @if($client_inv_data['pending'] != '0')
              <?php $i++ ?>
                <tr>

                  <td>{{$i}}</td>
                  <td><a href="{{ route('admin.request.requestupdate',['refid'=>$civ->reference_id]) }}" >{{ $civ->reference_id ?? '' }}
                              </a></td>
                  <td>{{ date("j M, Y",strtotime($civ->created_time)) }}</td>
                  <td>{{date('d-m-Y',strtotime($civ->created_time. ' + 15 days'))}}</td>
                 
                  <td><?php echo "Booked: <b>".$client_inv_data['booked'].'</b>';?>
                      </td>
                 
                  <td><b>{{strtoupper($civ->client_amnt_status)}}</b></td>
                  
                  <td></td>
                </tr>
                <?php $invoice_list=$loc_request->getreqinvoices($civ->req_id);?>
              
                @foreach($invoice_list as $cil)
                <?php $j++ ?>
                <tr>
                <td>{{$i.'.'.$j}}</td>
                <td>{{ $cil->invoice_no }}</td>
                <td>{{ $cil->invoice_date }}</td>
                <td>{{ date('Y-m-d',strtotime($cil->invoice_date. ' + 30 days')) }}</td>
                <td class="text-right">{{ number_format($cil->invoicing_total,2) }}</td>
                <td>{{ $cil->invoice_status? ucwords(str_replace('_',' ',$cil->invoice_status)) : 'Pending' }}</td>
                <td></td>
              </tr>
                @endforeach
              @endif
              @endforeach
            

                    
            </tbody>
          </table>
      </div>
    </div>
</div>  -->
<div class="row">
          <!-- <div class="col-md-3">
            <div class="sticky-top mb-3">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Draggable Events</h4>
                </div>
                <div class="card-body">
                  <div id="external-events">
                    <div class="external-event bg-success">Lunch</div>
                    <div class="external-event bg-warning">Go home</div>
                    <div class="external-event bg-info">Do homework</div>
                    <div class="external-event bg-primary">Work on UI design</div>
                    <div class="external-event bg-danger">Sleep tight</div>
                    <div class="checkbox">
                      <label for="drop-remove">
                        <input type="checkbox" id="drop-remove">
                        remove after drop
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Create Event</h3>
                </div>
                <div class="card-body">
                  <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <ul class="fc-color-picker" id="color-chooser">
                      <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                      <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                      <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                      <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                      <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                    </ul>
                  </div>
                  <div class="input-group">
                    <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                    <div class="input-group-append">
                      <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
<div class="card collapsed-card">
    <div class="card-header border-transparent">
      <h3 class="card-title">Client Invoice List</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-plus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0" style="display: none;">
      <div class="table-responsive">
        <table class="table m-0">
          <thead>
          <tr>
            <th>id</th>
            <th>Quote Id / Invoice No.</th>
            <th>Create Date</th>
            <th>Due Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th class="">Action</th>
          </tr>
          </thead>
          <tbody>
              <?php $i = 0;$j=0;
             
              ?>
              @foreach($last15days as $civ)
              <?php  $client_inv_data=$loc_request->getpendingivoice($civ->req_id);
              
              ?>
              <?php ?>
              @if($client_inv_data['pending'] != '0')
              <?php $i++ ?>
                <tr>

                  <td>{{$i}}</td>
                  <td><a href="{{ route('admin.request.requestupdate',['refid'=>$civ->reference_id]) }}" >{{ $civ->reference_id ?? '' }}
                              </a></td>
                  <td>{{ date("j M, Y",strtotime($civ->created_time)) }}</td>
                  <td>{{date('d-m-Y',strtotime($civ->created_time. ' + 15 days'))}}</td>
                 
                  <td><?php echo "Booked: <b>".$client_inv_data['booked'].'</b>';?>
                      </td>
                 
                  <td><b>{{strtoupper($civ->client_amnt_status)}}</b></td>
                  
                  <td></td>
                </tr>
                <?php $invoice_list=$loc_request->getreqinvoices($civ->req_id);?>
              
                @foreach($invoice_list as $cil)
                <?php $j++ ?>
                <tr>
                <td>{{$i.'.'.$j}}</td>
                <td>{{ $cil->invoice_no }}</td>
                <td>{{ $cil->invoice_date }}</td>
                <td>{{ date('Y-m-d',strtotime($cil->invoice_date. ' + 30 days')) }}</td>
                <td class="text-right">{{ number_format($cil->invoicing_total,2) }}</td>
                <td>{{ $cil->invoice_status? ucwords(str_replace('_',' ',$cil->invoice_status)) : 'Pending' }}</td>
                <td></td>
              </tr>
                @endforeach
              @endif
              @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
  </div>
<div class="card collapsed-card">
    <div class="card-header border-transparent">
      <h3 class="card-title">{{ getrolename('vendor') }}  Invoice List</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-plus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0" style="display: none;">
      <div class="table-responsive">
        <table class="table m-0">
          <thead>
          <tr>
            <th>id</th>
            <th>Quote Id / Invoice No.</th>
            <th>{{ getrolename('vendor') }}  Name</th>
            <th>Create Date</th>
            <th>Due Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th class="">Action</th>
          </tr>
          </thead>
          <tbody>
            <?php $i = 0;$j=0;
               
                ?>
                @foreach($last15days as $vin)
               
                <?php  $get_profit=$loc_request->getvendortotalprofit($vin->req_id);
                 
                  ?>
                  <?php //print_r($get_profit);die;?>
                
             
                <?php $i++ ?>
                  <tr>
                    <td>{{$i}}</td>
                    <td><a href="{{ route('admin.request.requestupdate',['refid'=>$civ->reference_id]) }}" >{{ $civ->reference_id ?? '' }}
                                </a></td>
                    <td></td>
                    
                    <td>{{ $vin->created_time }}</td>
                    <td></td>
                    <td>
                      <?php 
                      if($get_profit['client_pending']>0){
                      $profit=($get_profit['vendor_pending']/$get_profit['client_pending'])*100;
                      }else{
                        $profit=0;
                      }
                      $margin =100 - $profit;
                      ?>
                      <button data-booked="<?php echo $get_profit['client_pending'];?>" data-vendor="<?php echo $get_profit['vendor_pending'];?>" data-profit="<?php echo number_format($margin,2);?>" data-request="{{ $civ->reference_id ?? '' }}" class="btn btn-xs show_profit_data"><i class="fa fa-eye text-success"></i></button>
                    </td>
                    <td ><b>{{strtoupper($vin->vendor_amnt_status)}}</b></td>
                    <td></td>
                  </tr>
                 
                
                  <?php $v_invoice_list=$loc_request->getreqvendorinvoice($vin->req_id);?>
                  @foreach($v_invoice_list as $cil)
                  <?php $j++ ?>
                  <tr>
                  <td>{{$i.'.'.$j}}</td>
                  <td>{{ $cil->invoice_no }}</td>
                  <td>{{ getusernamebyid($cil->vendor_id)}}</td>
                  <td>{{ $cil->invoice_date }}</td>
                  <td>{{ date('Y-m-d',strtotime($cil->invoice_date. ' + 30 days')) }}</td>
                  <td class="text-right">{{ number_format($cil->invoicing_total,2) }}</td>
                  
                  <td>{{ $cil->invoice_status? ucwords(str_replace('_',' ',$cil->invoice_status)) : 'Pending' }}</td>
                  <td></td>
                </tr>
                  @endforeach
                @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
  </div>
<!---vendor invoice-->
<!-- <div class="card">
    <div class="card-header">
    Linguist Invoice List
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                      <th>id</th>
                      <th>Quote Id / Invoice No.</th>
                      <th>Linguist Name</th>
                      <th>Create Date</th>
                      <th>Due Date</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th class="">Action</th>
                    </tr>
                </thead>
               
              
              <tbody>
             
                <?php $i = 0;$j=0;
               
                ?>
                @foreach($last15days as $vin)
               
                <?php  $get_profit=$loc_request->getvendortotalprofit($vin->req_id);
                 
                  ?>
                  <?php //print_r($get_profit);die;?>
                
             
                <?php $i++ ?>
                  <tr>
                    <td>{{$i}}</td>
                    <td><a href="{{ route('admin.request.requestupdate',['refid'=>$civ->reference_id]) }}" >{{ $civ->reference_id ?? '' }}
                                </a></td>
                    <td></td>
                    
                    <td>{{ $vin->created_time }}</td>
                    <td></td>
                    <td>
                      <?php 
                      if($get_profit['client_pending']>0){
                      $profit=($get_profit['vendor_pending']/$get_profit['client_pending'])*100;
                      }else{
                        $profit=0;
                      }
                      $margin =100 - $profit;
                      ?>
                      <button data-booked="<?php echo $get_profit['client_pending'];?>" data-vendor="<?php echo $get_profit['vendor_pending'];?>" data-profit="<?php echo number_format($margin,2);?>" data-request="{{ $civ->reference_id ?? '' }}" class="btn btn-xs btn-info show_profit_data">View</button>
                    </td>
                    <td ><b>{{strtoupper($vin->vendor_amnt_status)}}</b></td>
                    <td></td>
                  </tr>
                 
                
                  <?php $v_invoice_list=$loc_request->getreqvendorinvoice($vin->req_id);?>
                  @foreach($v_invoice_list as $cil)
                  <?php $j++ ?>
                  <tr>
                  <td>{{$i.'.'.$j}}</td>
                  <td>{{ $cil->invoice_no }}</td>
                  <td>{{ getusernamebyid($cil->vendor_id)}}</td>
                  <td>{{ $cil->invoice_date }}</td>
                  <td>{{ date('Y-m-d',strtotime($cil->invoice_date. ' + 30 days')) }}</td>
                  <td class="text-right">{{ number_format($cil->invoicing_total,2) }}</td>
                  
                  <td>{{ $cil->invoice_status? ucwords(str_replace('_',' ',$cil->invoice_status)) : 'Pending' }}</td>
                  <td></td>
                </tr>
                  @endforeach
                @endforeach
              
              </tbody>
            </table>
        </div>
    </div>
</div>  -->
<?php }?>

<!----end vendor invoice--->
<?php if($user_role != 'administrator' && $user_role != 'sales'){?>
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Pie Chart</h3>
      </div>
      <div class="card-body">
        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Top 10 Active Records</h3>
      </div>
      <div class="card-body">
        <div class="chart">
          <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- STACKED BAR CHART -->

<?php  }?>
</div>
<div id="show_profit_data_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="show_profit_data_modal_header"></h3>
            </div>
            <div class="modal-body message_body">
                <div class="row">
                  <div class="span9" id="error_on_header"></div>
                    <div class="col-md-12 col-xs-12 col-sm-12">
                      <div class="row">
                        <ul>
                          <li>
                            Booked Amount: <b id="booked_data"></b>
                          </li>
                          <li>
                          Linguist Charges: <b id="vendor_data"></b>
                          </li>
                          <li>
                            Profit Margin: <b id="profit_data"></b>%
                          </li>
                        </ul>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" data-dismiss="modal" >Cancel </button>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('scripts')
<!-- fullCalendar -->
<link rel="stylesheet" href="{{ asset('admin') }}/plugins/fullcalendar/main.css">
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('admin') }}/plugins/fullcalendar/main.js"></script>
@parent
<style type="text/css">
	.bg-info, .bg-info>a {
    color: #fff!important;
}
.bg-info {
    background-color: #17a2b8!important;
}
.small-box {
    border-radius: .25rem;
    box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
    display: block;
    margin-bottom: 20px;
    position: relative;
}
.small-box>.inner {
    padding: 10px;
}
.small-box .icon {
    color: rgba(0,0,0,.15);
    z-index: 0;
}
*, ::after, ::before {
    box-sizing: border-box;
}
@media (min-width: 1200px){
.col-lg-3 .small-box h3, .col-md-3 .small-box h3, .col-xl-3 .small-box h3 {
    font-size: 2.2rem;
}
@media (min-width: 992px){
.col-lg-3 .small-box h3, .col-md-3 .small-box h3, .col-xl-3 .small-box h3 {
    font-size: 1.6rem;
}
.small-box h3, .small-box p {
    z-index: 5;
}
.small-box h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 10px;
    padding: 0;
    white-space: nowrap;
}
.small-box p {
    font-size: 1rem;
}
.small-box .icon>i.fa, .small-box .icon>i.fab, .small-box .icon>i.fad, .small-box .icon>i.fal, .small-box .icon>i.far, .small-box .icon>i.fas, .small-box .icon>i.ion {
    font-size: 70px;
    top: 20px;
}
.small-box .icon>i {
    font-size: 90px;
    position: absolute;
    right: 15px;
    top: 15px;
    transition: -webkit-transform .3s linear;
    transition: transform .3s linear;
    transition: transform .3s linear,-webkit-transform .3s linear;
}
.small-box>.small-box-footer {
    background-color: rgba(0,0,0,.1);
    color: rgba(255,255,255,.8);
    display: block;
    padding: 3px 0;
    position: relative;
    text-align: center;
    text-decoration: none;
    z-index: 10;
}
@media (max-width: 767.98px){
.small-box .icon {
    display: none;
}
.small-box {
    text-align: center;
}
.small-box p {
    font-size: 12px;
}
}

</style>
<script src="{{ asset('js/chart.js/Chart.js') }}"></script>
<script type="text/javascript">
	$(function () {
		var donutData        = {
      labels: [
          'New',
          'In-Progress',
          'Completed'
      ],
      datasets: [
        {
          data: <?php echo $pie_chat_data ?? '[]';?>,
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
	var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
      tooltips: {
        enabled: true,
        mode: 'single',
        callbacks: {
          label: function(tooltipItems, data) {
            return data.labels[tooltipItems.index]+': '+data.datasets[0].data[tooltipItems.index] + '%';
          }
        }
      }
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

   
    /*Sticky header*/
     var ls = {
      labels  : <?php echo $linechat_status ?? '[]';?>
  }
    var areaChartData = {
      labels  : <?php echo $linechat_label ?? '[]';?>,
      datasets: [
        {
          label               : 'Status',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : <?php echo $linechat_data ?? '[]';?>,
        }
      ]
    }
    var barChartData = $.extend(true, {}, areaChartData)
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      tooltips: {
        enabled: true,
        mode: 'single',
        callbacks: {
          label: function(tooltipItems, data) {
            return ls.labels[tooltipItems.index]+': '+data.datasets[0].data[tooltipItems.index] + '%';
          }
        }
      },
      scales: {
        xAxes: [{
          stacked: true,
           type: 'category',
           categories:  ["$0", "$23.63"],
           tick: {
                multiline:false,
                culling: {
                    max: 1
                },
            },
          scaleLabel: {
	        display: true,
	        labelString: "Reference Id's",
	        fontStyle: 'bold',
	      },
        }],
        yAxes: [{
          stacked: true,
          scaleLabel: {
	        display: true,
	        labelString: 'Request Status',
	        fontStyle: 'bold',
	      },
	      ticks: {
                beginAtZero: true,
                stepSize:20,
                max: 100 
            }
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
    /*Sticky header*/
    });


$('.show_profit_data').on('click', function () {
  var vendor=$(this).data('vendor');
  var booked=$(this).data('booked');
  var profit=$(this).data('profit');
  var request=$(this).data('request');
  jQuery('#show_profit_data_modal').modal('show', {backdrop: 'true'});
  jQuery('#vendor_data').html(vendor);
  jQuery('#booked_data').html(booked);
  jQuery('#profit_data').html(profit);
  jQuery('#show_profit_data_modal_header').html(request);
  //show_popup(fileid,trAssign,req_id,req_lang_id);
});
</script>
@endsection