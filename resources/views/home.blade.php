@extends('components.navbar')
<title>Home</title>
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('rundowns.index') }}" class="card-link ">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                    </div>
                    <div class="card-body">
                        @if($latestRundown)
                            <h1 class="card-title">{{ $latestRundown->subject }}</h1>
                            <h3 class="text-muted">DAY {{ $latestRundown->day }}</h3>
                            <hr>
                            <h6>Details</h6>
                            <ul class="list-group list-group-flush">
                                @foreach($rundownDetails as $detail)
                                    <li class="list-group-item">
                                        <span class="font-weight-bold">{{ $detail->start }} - {{ $detail->end }}:</span> {{ $detail->activity }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Buat dulu kak rundownnya.</p>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <div class="home flex-center">
                <div class="container">
                    <p class="display-4 text-center">Welcome, Angkatan Tercinta</p>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
