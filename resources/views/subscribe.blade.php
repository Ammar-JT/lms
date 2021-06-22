@extends('layouts.app')

@section('header')
<header class="header header-inverse" style="background-color: #1ac28d">
  <div class="container text-center">

    <div class="row">
      <div class="col-12 col-lg-8 offset-lg-2">

        <h1>Subscribe to our awesome site</h1>
      </div>
    </div>

  </div>
</header>
@stop

@section('content')
<section class="section" id="section-vtab">
    <div class="container text-center">
        @if (auth()->user()->subscribed('yearly') || auth()->user()->subscribed('monthly'))
          <h4>
            You already subscribed to 
            <span class="badge badge-success">{{auth()->user()->subscriptions->first()->name}}</span>
            plan 
          </h4>
          <a class="btn btn-info btn-lg mt-3" href="{{route('profile', auth()->user()->username)}}">Change the Plan</a>

        @else
          <vue-stripe email="{{ auth()->user()->email }}"></vue-stripe>
        @endif
    </div>
</section>    

@endsection


@section('script')
    <script src="https://checkout.stripe.com/checkout.js"></script>
@endsection