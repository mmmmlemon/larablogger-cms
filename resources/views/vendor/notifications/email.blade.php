<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/basic.min.css') }}" rel="stylesheet">
    <title>Reset Password</title>
    <style>
        .button{
            background-color: #3273dc;
            border-radius: 20px;
            color: #fff;
            padding-bottom: calc(0.5em - 1px);
            padding-left: 1em;
            padding-right: 1em;
            padding-top: calc(0.5em - 1px);
            text-align: center;

        }
    </style>
</head>
<body>
    <div class="container white-bh">
        <div class="column">
            <h1 class="title">Reset Password</h1>
            @foreach ($introLines as $line)
            <p>{{ $line }}</p>
            @endforeach

        <a class="button" href="{{$actionUrl}}">Reset Password</a>
        <br><br>
        <p>Link (in case the button above doesn't work)</p>
        <a href="{{$actionUrl}}">{{$actionUrl}}</a>
        </div>
    </div>
</body>
</html>