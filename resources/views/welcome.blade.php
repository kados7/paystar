<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" value="{{ csrf_token() }}" />
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
<div id="app">
    {{-- <h1>Laravel and Vue3</h1> --}}
    <Header-component></Header-component>
</div>
@vite('resources/js/app.js')

</body>
</html>
