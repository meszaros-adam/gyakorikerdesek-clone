<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyakorikérdések klón</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
</head>
<body>
    <div id="app">
        @if(Auth::check())
        <app :user="{{Auth::user()}}"></app>
        @else
        <app :user="false"></app>
        @endif
    </div>
    @vite('resources/js/app.js')
</body>

</html>