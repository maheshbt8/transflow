@extends('layouts.admin')
@section('content')
<style>
    .heading{
        color:#0066ff;
    }
</style>
        
<div class="card">
    <div class="card-header">
      <p class="">Video to Text Tool</p>
    </div>
</div>
<div class="card">
    <div class="card-body text-center">
      <h3 class="pt-2 pb-2">Transflow Samples</h3>
    </div>
</div>

<div class="card">
    <div class="card-header text-center ">
      <h5 class="heading">Translation Text</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <div class="">
                <p class="helper-block">
                 Sentence 1: <b> Hi, How are you ?</b> </p>
                 <p class="helper-block">
				 Sentence 2: <b>Shall we go to movie today? </b> </p>
                </p>
            </div>                      
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header text-center ">
    <h5 class="heading">Translation File</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <div class="">
                <p class="helper-block">
                Download Translation file. Please <a href="https://dev.transflowtms.com/assets/uploadfiles/tmsdemo/translatefile.xlsx" target="_blank">click here</a>
                </p>
            </div>                      
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header text-center ">
    <h5 class="heading">LQAT</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <div class="">
                <p class="helper-block">
                Download proof reader file for translation verification. Please <a href="https://dev.transflowtms.com/assets/uploadfiles/tmsdemo/lqat_sample.xlsx" target="_blank">click here</a>
                </p>
            </div>                      
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header text-center ">
    <h5 class="heading">Speech to Speech Tool</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <div class="">
                <p class="helper-block">
                Download the video file and upload it.. Please <a href="https://dev.transflowtms.com/ratecard/speechtospeechvideodownload/1" target="_blank">click here</a>
                </p>
                <p class="helper-block">
                Download the video file and upload it.. Please <a href="https://dev.transflowtms.com/ratecard/speechtospeechvideodownload/2" target="_blank">click here</a>
                </p>
                <p class="helper-block">
                Download the video file and upload it.. Please <a href="https://dev.transflowtms.com/ratecard/speechtospeechvideodownload/3" target="_blank">click here</a>
                </p>
                <p class="helper-block">
                Download the video file and upload it.. Please <a href="https://dev.transflowtms.com/ratecard/speechtospeechvideodownload/4" target="_blank">click here</a>
                </p>
            </div>                      
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header text-center">
    <h5 class="heading">Video Subtitle Tool</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <div class="">
                <p class="helper-block">
                Download the video file and upload it. <a href="https://dev.transflowtms.com/ratecard/videodownload" target="_blank">click here</a>
                </p>
            </div>                      
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header text-center">
    <h5 class="heading">Video to Text Tool</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content">
            <div class="">
                <p class="helper-block">
                Download the video file and upload it. <a href="https://dev.transflowtms.com/ratecard/videodownload" target="_blank">click here</a>
                </p>
            </div>                      
        </div>
    </div>
</div>
@endsection