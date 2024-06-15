<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link rel="icon" href="{{ asset('assets/images/narlogo.png') }}" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        
        .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        canvas {
            width: 100vw;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            /* background-color: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); */
        }

        .login-container {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .login-container input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
        }

        .login-container input[type="password"]::placeholder {
            color: white;
        }

        .login-container input[type="password"]:focus {
            outline: none;
            border-color: #85AD99;
        }

        .login-container button {
            padding: 10px 20px;
            background-color: #85AD99;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            /* background-color: #6C957B; */
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <canvas id="canvas"></canvas>
        <div class="login-container">
            <h2>Password Angkatan 24-2</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="hayolo passnya apa">
                </div>
                @if ($errors->has('password'))
                    <div class="error-message mb-4 font-weight-bold">{{ $errors->first('password') }}</div>
                @endif
                <button type="submit" class="btn btn-primary">Meluncur</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/particle.js') }}">
    </script>
</body>

</html>
