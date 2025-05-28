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

    @auth

    <script>
        window.location.href = "/account";
    </script>

    @else
    <div class="auth-container">
    <h2 class="login-header">Login</h2>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <input type="email" name="email" placeholder="Email">
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password">
        </div>
        <button type="submit" class="login-button">Login</button>
    </form>
    <div class="login_links">
        <p>Don't have an account? <a href="/register">Register here</a></p>
    </div>
    </div>

    @endauth
    
    @include('footer')
</body>
</html>