<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/components/navbar.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">24-2</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('trainee.index') }}">Trainee List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Trainer List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('casesolve.index') }}">Case Solving</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bpprojects.index') }}">BP Project</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href={{ route('showForum') }}>Forum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Presentation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Acquaintance</a>
                </li>
            </ul>
            
        </ul>
        <ul class="navbar-nav navbar-nav-no-margin">
            <a class="nav-link" id="typed-text" style="color:white !important;"></a>
        </ul>
        </div>
    </nav>

    <div class="contents-filler" style="padding-top: 56px;">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var typed = new Typed('#typed-text', {
                strings: ["", "Semangat!", "Yuk bisa yuk!","Kalau sedih, curhatnya ke angkatan ya !", "Lets go perfect core !!",
                    "Angkatan paling gacor", "Jangan TB TB", "24-2 Pasti semua lulus", "Jangan lupa saling bantu",
                    "Peduli angkatannya kak !!", "Jangan Quit ya ^^", "Jangan ngemeng kak", "Fokus Kak"
                ],
                typeSpeed: 40,
                backSpeed: 20,
                loop: true
            });
        });
    </script>
</body>

</html>
