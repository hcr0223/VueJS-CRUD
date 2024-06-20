<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" value="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel with Vue') }}</title>
    
</head>
<body>
    
    <div id="app"></div>
    @vite('resources/js/app.js')
</body>
</html>