<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>The SaaS - Header Image</title>

    <!-- Styles -->
    <link href="{{ asset('assets/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/thesaas.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">

    @yield('scripts')
  </head>

  <body>
    <div id="app">


    <!-- Topbar -->

    <nav class="topbar topbar-inverse topbar-expand-md topbar-sticky">
      <div class="container">
        
        <div class="topbar-left">
          <button class="topbar-toggler">&#9776;</button>

          <a href="index.html" class="topbar-brand">
            <img src="{{asset('assets/img/logo.png')}}" alt="logo" class="logo-default">
            <img src="{{asset('assets/img/logo-light.png')}}" alt="logo" class="logo-inverse">
          </a>
          
        </div>


        <div class="topbar-right">
          <ul class="topbar-nav nav">
            <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
            
            @auth
              Hey {{auth()->user()->name}}
            @else 
              <li class="nav-item"><a class="nav-link" type="button" href="javascript:;" data-toggle="modal" data-target="#loginModal">Login</a></li>
            @endauth
          </ul>
        </div>

      </div>
    </nav>
    <!-- END Topbar -->



    <!-- Header -->
    @yield('header')
    <!-- END Header -->





    <!-- Main container -->
    <main class="main-content">

      @yield('content')

    </main>
    <!-- END Main container -->

    <!-- or if(!auth()->check()) -->
    @guest
      <vue-login></vue-login>
    @endguest

    <!-- Footer -->
    <footer class="site-footer">
      <div class="container">
        <div class="row gap-y justify-content-center">
          <div class="col-12 col-lg-6">
            <ul class="nav nav-primary nav-hero">
              <li class="nav-item hidden-sm-down">
                <a class="nav-link" href="/">LmsSass</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
    <!-- END Footer -->
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/core.min.js') }}"></script>
    <script src="{{ asset('assets/js/thesaas.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>