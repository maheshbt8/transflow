@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.e_settings.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
            <a class="nav-link {{$type == 'text'? 'active' : ''}}" href="translation/text">Text</a>
            </li>
            <li class="nav-item">
            <a class="nav-link {{$type == 'doc'? 'active' : ''}}" href="translation/doc">Document</a>
            </li>
            <li class="nav-item">
            <a class="nav-link {{$type == 'slide'? 'active' : ''}}" href="translation/slide">Translate Slide</a>
            </li>
            <li class="nav-item">
            <a class="nav-link {{$type == 'word'? 'active' : ''}}" href="translation/word">Word-2-Vec</a>
            </li>
        </ul>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    @if($type == 'text')
                    text
                    @elseif($type == 'doc')
                    doc
                    @elseif($type == 'slide')
                    slide
                    @elseif($type == 'word')
                    word
                    @else
                    No Page Available
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
