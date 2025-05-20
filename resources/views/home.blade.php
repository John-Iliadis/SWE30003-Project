<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">

</head>
<body>

    @include('header')

    <div id="spacing_div" style="height: 100px"></div>

    <main>
        <h1 id="welcome_header">Welcome to AWE Electronics</h1>
        <a href="/#" id="enter_store">Enter Store</a>
{{--        <button id="enter_store">Enter Store</button>--}}
    </main>

    @include('footer')

</body>
</html>
