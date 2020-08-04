<!DOCTYPE html>
<html>
<head>
    @include('feed::links')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>This site is under maintenance</title>
    <!-- FontAwesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
    <link href="{{ asset('css/basic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bulma-tooltip.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bulma-divider.min.css') }}" rel="stylesheet">
    <link href="{{asset('css/bulma-checkradio.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bulma-radio-checkbox.min.css')}}" rel="stylesheet">
</head>
<body style="background-color: rgb(207, 177, 121);">
    <div class="container white-bg" style="top:20%;">
        <div class="column bounce-in has-text-centered">
            <h1 class="title post_title">The web-site is under maintenance</h1>
            <span class="icon is=large">
                <i class="fas fa-3x fa-wrench"></i>
            </span>
            <h1 class="subtitle">Come again later</h1>
        </div>
    </div>

</body>
</html>
    
    
    
    
