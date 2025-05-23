<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
</head>
<body>
    @include('header')

    <div class="login_container">
    <h2>Login</h2>
    <form class="login_form" action="/login" method="POST">
        @csrf
        <input name="email" type="email" placeholder="email">
        <input name="password" type="password" placeholder="password">
        <button>Login</button>
    </form>
    <div class="login_links">
        <p>Don't have an account? <a href="/register">Register here</a></p>
    </div>
    </div>
    
    @include('footer')
</body>
</html>