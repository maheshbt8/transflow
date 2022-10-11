@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.translation.title_singular') }} 
        {{ trans('cruds.translation.fields.text') }} 
    </div>
    <div class="card-body">
        <!-- <form action="{{ route("admin.request.store") }}" method="POST" enctype="multipart/form-data">
            @csrf -->

        	<div class="row">
                <div class="col-md-12">
                    <input type="button" class="btn btn-success float-right" id="translate" value="Translate"/>
                </div>
        		<div class="col-md-6">
                    <div class="form-group">
                        <label for="detect_language" class="">Detected Language</label>&nbsp;<button class="btn btn-sm btn-info"><b>English</b></button>
                    </div>
                    <div class="form-group">
                        <textarea rows="4" cols="50" name="detect_language" id="detect_language" class="form-control" ></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="dropdown">
                            <label for="target_language" class="">Target Language</label>
  <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b>
    <span id="selectlang">French</span></b>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
     <div class="row">
        <?php $i=0;$j=1;?>
    @foreach($loc_languages as $key => $lang)
    <div class="col-md-3">
      <a class="dropdown-item select_lang" href="#" data-name="{{ $lang->lang_name }}" data-code="{{ $lang->lang_code }}">{{ $lang->lang_name }}</a>
  </div>
      <?php $i++;?>
    @endforeach
</div>
  </div>
</div>
</div>
<div class="form-group">
  <input type="hidden" id="optedlanguage" value="fr" >
                        <textarea rows="4" cols="50" name="target_language" id="target_language" class="form-control" readonly=""></textarea>
                    </div>
                    <div class="fl-copy"><a href="#"><i class="fa fa-copy">Copy</i></a></div>
                </div>
                <input type="hidden" name="_method" value="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
        	</div>
        <!-- </form> -->
    </div>
</div>
<style type="text/css">
.dropdown-menu{
    transform: translate3d(-500px, 30px, 0px)!important;
    width: 960px;
    height: 500px;
    overflow-x: hidden;
}
.fl-copy {
    display: none;
    float: right;
    position: relative;
    right: 10%;
    top: 3px;
    cursor:pointer;
}
</style>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
    $('.select_lang').click(function() {        
    var code = $(this).data("code");
    var name = $(this).data("name");
        $('#selectlang').html(name);
        $('#optedlanguage').val(code);
    });
    $("#translate").click(function(){
        var text= $("textarea#detect_language").val();
        var opt_lg = $("#optedlanguage").val();
        if(opt_lg == ''){
            $('#ajax_message').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>Select the target language</div>');
            return false;
        }
        if(text){
            var request = new Request("{{ config('constants.translate_api') }}",{
                    method: 'POST', // or 'PUT'
                    body: JSON.stringify({"text":text, "dest_lg":opt_lg}), // data can be `string` or {object}!
                    headers:{
                    'Content-Type': 'application/json'
                    }
                    })
                    fetch(request)
                    .then(resp => resp.text())
                    .then(function(response){
                        $('#target_language').html(response);
                        $("#target_language").focus();
                        $(".fl-copy").show();
                    })
                    .catch(function(er){
                        console.log(er);
                    })
        }else{
            $('#ajax_message').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>Please enter something</div>');
        }
    });
    $(".fl-copy").click(function(){
        var textarea = document.getElementById("target_language");
        textarea.select();              
        document.execCommand("copy");
        //alert("Copied the text: " + textarea.value);
        $('#ajax_message').html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Copied the text</div>');
    });
</script>
@endsection
