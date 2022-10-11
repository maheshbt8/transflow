 @extends('layouts.admin')
 @section('content')
 <style>
     .bg-yellow {
        border-radius: 50%;
    font-size: 13px;
    height: 26px;
    left: 18px;
    line-height: 25px;
    position: absolute;
    text-align: center;
    width: 26px;
    margin-top: 7px;
}
    

     .timeline-header {
         border-bottom: 1px solid rgba(0, 0, 0, .125);
         color: #495057;
         font-size: 16px;
         line-height: 1.1;
         margin: 0;
         padding: 10px;
     }

     .time {
         color: #999;
         float: right;
         font-size: 12px;
         padding: 10px;
     }

     .timeline-item {
         box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
         border-radius: 0.25rem;
         background-color: #fff;
         color: #495057;
         margin-left: 60px;
         margin-right: 15px;
         margin-top: 0;
         padding: 0;
         position: relative;
     }
 </style>
 
    
     <?php $user_role = Auth::user()->roles[0]->name;
      $userid = Auth::user()->id;  ?>
      <?php if(($user_role == 'translator' && $get_taget_file->tr_status<100) || ($user_role == 'vendor' && $get_taget_file->v_status<100) || ($user_role == 'qualityanalyst' && $get_taget_file->qa_status<100) || ($user_role == 'proofreader' && $get_taget_file->pr_status<100) ||($user_role=="projectmanager") || ($user_role=="orgadmin")){ $save_btn=0;?>
        <div class="card">
    
     <div class="card-header">
         Upload File
     </div>

     <div class="card-body">
         <div class="d-flex justify-content">
             <form action='{{ route("admin.request.add_comments") }}' method="POST" class="needs-validation" enctype="multipart/form-data" novalidate="">
                 @csrf
                 <?php if ($getstatus->request_status == "tr_inprogress" || $getstatus->request_status == "tr_assign" || $getstatus->request_status == "approve" && $user_role == "projectmanager" || $user_role == "translator" || $user_role == "vendor" ||$user_role == "proofreader" ||$user_role == "qualityanalyst") { $save_btn=1; ?>
                     <div class=" form-group {{ $errors->has('') ? 'has-error' : '' }}">
                         <label for="target_file">Target File: </label>
                         <br>
                         <input type="file" name="target_file" class="form-control" id="target_file">
                         <!-- <div class="invalid-feedback" for="">Must Add The Comments</div> -->
                     </div>
                 <?php } ?>
                 <?php if ($getstatus->request_status == "tr_inprogress" || $getstatus->request_status == "tr_assign" || $getstatus->request_status == "approve" && $user_role == "projectmanager" || $user_role == "translator" || $user_role == "vendor" || $user_role == "proofreader" || $user_role == "qualityanalyst") {  $save_btn=1;?>
                     <div class=" form-group {{ $errors->has('') ? 'has-error' : '' }}">
                         <label class="required" for="comment">Comments: </label>
                         <textarea name="comment" id="id_subject" class="form-control" rows="5" required></textarea>
                         <div class="invalid-feedback" for="">Must Add The Comments</div>
                     </div>
                 <?php } ?>
                 <?php if ($user_role == "translator" || $user_role == "vendor" || $user_role == "qualityanalyst" || $user_role == "proofreader") {  $save_btn=1;?>
                     <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                         <label class="required">Status</label>
                         <select name="status" id="status" class="form-control select2" required>
                             <option value="">Select Status</option>
                             <option value="25">25% Completed</option>
                             <option value="50">50% Completed</option>
                             <option value="75">75% Completed</option>
                             <option value="100">100% Completed</option>
                         </select>
                         <div class="invalid-feedback" for="">Please Select Status</div>
                     </div>
                 <?php } ?>
                 <div>
                     <?php if($save_btn == 1){?>
                     <input type="hidden" value="{{$target_file_id}}" name="target_file_id" />
                     <input type="hidden" value="{{$get_file_id->id}}" name="get_file_id" />
                     <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
                     <?php }?>
                 </div>
             </form>
         </div>
     </div>
     
 </div>
 <?php } ?>
 <div class="card">
     <div class="card-header">
         View Comments
     </div>
     <div class="card-body" style="height:400px; overflow:scroll; overflow-x: hidden">
         <div class="row">
             <div class="col-md-12">
                 <div class="timeline">

                     <div>
                   
                                 @foreach($comments as $cmnt)
                            
                                 <i class="fas fa-comments bg-yellow"></i>
                                 <div class="timeline-item">
                                 <?php $getusername = getusernamebyid($cmnt->user_id, 'name'); ?>
                                 <span class="time"><i class="fas fa-clock"></i> {{$cmnt->created_time}}</span>
                                 <h5 class="timeline-header"><span style="color:steelblue;">{{$getusername}}</span> commented on your Comment</h5>
                                 <div class="timeline-body" style="padding: 10px">
                                     {{isset($cmnt) ? $cmnt->comment : ''}}
                                     <?php 
                                        if(isset($cmnt->file_name) && !empty($cmnt->file_name)){
                                            ?>
                                            <span class="float-right">  <a href="<?php echo env('AWS_CDN_URL') . '/request/comments/'.$cmnt->file_name ;?>" download><i class="fa fa-download"></i>{{isset($cmnt->file_name) ? $cmnt->file_name : ''}}</a> </span>
                                            <?php 
                                        }
                                     ?>
                                     
                                 </div> 
                         </div><br>
                         
                         @endforeach
                     </div>
                 
                 </div>

             </div>
         </div>
     </div>
 </div>

 @endsection
 