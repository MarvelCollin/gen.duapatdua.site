@extends('components.navbar')
<title>Home</title>
@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12 mb-4 text-center">
                    <h1 class="display-4 font-weight-bold" id="welcome"></h1>
            </div>
            <div class="col-md-6 mb-4">
                <a href="{{ route('rundowns.index') }}" class="card-link text-decoration-none">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <h2>Latest Rundown</h2>
                        </div>
                        <div class="card-body">
                            @if ($latestRundown)
                                <h1 class="text-center">{{ $latestRundown->subject }}</h1>
                                <h3 class="text-muted text-center">DAY {{ $latestRundown->day }}</h3>
                                <hr>
                                <h5 class="text-center">Details</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach ($rundownDetails as $detail)
                                        <li class="list-group-item">
                                            <h5 class="mb-0">
                                                <strong>{{ $detail->start }} - {{ $detail->end }}:</strong>
                                                {{ $detail->activity }}
                                            </h5>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted text-center">Silahkan buat rundownya dulu kak.</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 mb-4">
                @if ($latestCaseSolves->isEmpty())
                    <div class="card shadow-lg text-center border-0">
                        <div class="card-header bg-danger text-white">
                            <h3>Active Case Solve</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Tidak ada case yang aktif.</p>
                        </div>
                    </div>
                @else
                    @foreach ($latestCaseSolves as $caseSolve)
                        <a href="{{ route('casesolve.show', $caseSolve->id) }}" class="card-link text-decoration-none">
                            <div class="card shadow-lg text-center mb-4 border-0">
                                <div class="card-header bg-success text-white">
                                    <h3>Active Case Solve</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <h2 class="font-weight-bold">{{ $caseSolve->title }}</h2>
                                            <h4 class="mb-1"><strong>{{ $caseSolve->subject }} | {{ $caseSolve->session }}</strong></h4>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     var typed = new Typed('#welcome', {
        //         strings: ["Welcome, Angkatan 24-2 Tercinta !", "Welcome, Angkatan 24-2 Tergacor !", "Welcome, Angkatan 24-2 Tersolid !"],
        //         typeSpeed: 90,
        //         backSpeed: 60,
        //         showCursor: false,
        //         smartBackspace: true,
        //         loop: true
        //     });
        // });
    </script>
@endsection
