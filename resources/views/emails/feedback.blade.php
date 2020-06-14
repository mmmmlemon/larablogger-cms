<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
    <link href="{{ asset('css/basic.min.css') }}" rel="stylesheet">
    <title> @if($title != "")- {{$title}} @else Feedback message @endif</title>
</head>
<body>
    <div class="white-bg" style="background-color:rgba(235, 235, 235, 0.3);">
        <h1>Feedback message @if($title != "")- {{$title}} @endif</h1>
        @if($email != "") <h4>From: {{$email}}</h4> @endif
        @if($title != "") <h4>Title: {{$title}}</h4> @endif
        <hr>
        <div>{!!$feedback!!}</div>
        <hr>
        <h6>{{$date}}</h6>
        
    </div>
</body>
</html>

