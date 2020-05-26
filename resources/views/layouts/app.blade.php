<!--ОСНОВНОЙ ЛЕЙАУТ САЙТА-->
@php
    //получаю название и подзаголовок сайта с соц.сетями для шапки сайта (и другую инфу если она понадобится)
    //так делать нельзя конечно (наверное), но здесь пусть будет (может потом поправлю)
    $settings = App\Settings::all()->first();
    $social_media = App\SocialMedia::whereNotNull('platform_name')->whereNotNull('url')->get();
    $categories = App\Category::where('category_name','!=','blank')->get();
    $settings = App\Settings::get()[0];
 @endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background-image: url({{asset('/storage/'.$settings->bg_image) }})">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/scripts.js') }}" defer></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
  <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bulma_override.css') }}" rel="stylesheet">
  <link href="{{ asset('css/richtext.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/jquery.tag-editor.css') }}" rel="stylesheet">
  <link href="{{ asset('css/plyr.css') }}" rel="stylesheet">
  <link href="{{ asset('css/basic.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
  <link href="{{ asset('css/tags-input.css') }}" rel="stylesheet">




  <!-- Bulma Extensions -->
  <link href="{{ asset('css/bulma-tooltip.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bulma-divider.min.css') }}" rel="stylesheet">
  <link href="{{asset('css/bulma-checkradio.min.css')}}" rel="stylesheet">
  <link href="{{asset('css/bulma-radio-checkbox.min.css')}}" rel="stylesheet">


  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <!-- FontAwesome -->
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>


  <!--Shared Scripts-->
  <script src="{{ asset('js/custom/shared/shared.js') }}"></script>

</head>
<body>
    <section class="hero header">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title web-site-title" style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;">
                  {{$settings->site_title}}
                </h1>
                <h2 class="subtitle web-site-subtitle">
                  {{$settings->site_subtitle}}
                </h2>
            </div>
        </div>
    </section>

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item">

            </a>

            <a role="button" id="nav-toggle" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="nav-menu" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/">
                    Home
                </a>

                @foreach($categories as $categ)
                    <a class="navbar-item" href="/category/{{strtolower($categ->category_name)}}">
                    {{$categ->category_name}}
                    </a>
                @endforeach

                @if($settings->show_about == 1)
                <a class="navbar-item" href="/about">
                    About
                </a>
                @endif

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Links
                    </a>

                    <div class="navbar-dropdown">

                        @foreach($social_media as $item)
                        <a class="navbar-item" target="_blank" href={{$item->url}}>
                            {{$item->platform_name}}
                        </a>
                        @endforeach

                        <hr class="navbar-divider">
                        <a class="navbar-item" id="showModalContact">
                            Contact
                        </a>
                    </div>
                </div>

          
            </div>

            @if(Auth::check())

            <div class="navbar-end">
                <a class="navbar-item has-tooltip-bottom" data-tooltip="It's you! :)">
                    {{Auth::user()->name}}
                </a>
                @if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                <a class="navbar-item" href="/control">
                    Control panel
                </a>
                @endif
                <a class="navbar-item">
                    <span class="icon has-text-info has-tooltip-left" data-tooltip="Logout"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                      </span>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="invisible">
                        @csrf
                    </form>
                </a>
            </div>

            @endif
        </div>

    </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <div class="modal" id="contact-modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <article class="message">
                <div class="message-header">
                  <p>Contact</p>
                </div>
                <div class="message-body"> 
                      @if($settings->contact_text)
                      <p>{{$settings->contact_text}}</p>
                      @endif
                      @if($settings->contact_email !=null)
                      <p>E-mail: {{$settings->contact_email}}</p>
                      @endif
                      @if($settings->contact_text || $settings->contact_email)
                      <div class="is-divider"></div>
                      @endif
                      <div class="field">
                        <div class="control">
                         <input type="email" class="input" placeholder="Your email (optional)">
                        </div>
                      </div>
                      <div class="field">
                        <div class="control">
                          <textarea class="textarea" placeholder="Your message"></textarea>
                        </div>
                      </div>

                      <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link">
                                <span class="icon">
                                  <i class="fa fa-envelope"></i>
                                </span>
                                <span>Submit</span>
                              </button>
                        </div>
                      </div>
                    
                </div>
              </article>
        </div>
        <button class="modal-close is-large" aria-label="close"></button>
      </div>
      @yield('modals')
</body>


    <footer class="footer" id="footer">
        <div class="content has-text-centered">
          <p>
           {!!$settings->footer_text!!}
          </p>
        </div>
      </footer> 


</html>





@stack('scripts')
