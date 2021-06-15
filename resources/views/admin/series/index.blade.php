@extends('layouts.app')

@section('header')

<!-- Header -->
<header class="header header-inverse" style="background-color: #c2b2cd;">
    <div class="container text-center">

    <div class="row">
        <div class="col-12 col-lg-8 offset-lg-2">

        <h1>{{$series->title}}</h1>
        <p class="fs-20 opacity-70">Customise your series lesson</p>

        </div>
    </div>

    </div>
</header>
<!-- END Header -->
    
    
@endsection

@section('content')

<div class="section" style="background-color: black;">
    <div class="container">

        <div class="row gap-y">
            <div class="col-12">
                <vue-lessons default_lessons="{{$series->lessons}}"></vue-lessons>
                
            </div>
        </div>
    </div>
</div>

  @endsection
