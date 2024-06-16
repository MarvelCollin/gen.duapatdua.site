@extends('components.navbar')
<title>Case Solve Progress</title>
<style>
    .card {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
</style>

@section('content')
    <div class="container text-center">
        <h1 class="mb-4">{{ $caseSolve->title }}</h1>
        <p class="mb-2">Subject: {{ $caseSolve->subject }}</p>
        <p class="mb-5">Session: {{ $caseSolve->session }}</p>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="mb-5 row">
            <a class="btn btn-secondary col-md-1 mr-3" href="{{ route('casesolve.index') }}">
                Back
            </a>
            <button type="button" class="btn btn-primary col-md-3 mr-3" data-toggle="modal" data-target="#leaderboardModal">
                View Progress Angkatan
            </button>
                <input type="text" id="searchInput" class="form-control col-md-5" placeholder="Search by trainee number or name">
        </div>
        @php
            $sortedDetails = $caseSolveDetails->sortBy(function ($detail) {
                preg_match('/T0(\d+)/', $detail->trainee->trainee_number, $matches);
                return (int) $matches[1];
            });
        @endphp


        <div class="row" id="traineeContainer">
            @foreach ($sortedDetails as $detail)
                @php
                    $totalPercentage = $detail->caseSubtitles->sum('percentage');
                    $totalSubtitleCount = $detail->caseSubtitles->count();
                    $totalPercentageDone =
                        $totalSubtitleCount > 0 ? round(($totalPercentage / ($totalSubtitleCount * 100)) * 100, 2) : 0;
                @endphp
                <div class="col-md-4 mb-4 trainee-card"
                    data-trainee="{{ $detail->trainee->trainee_number }} {{ $detail->trainee->name }}">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-body" style="cursor: pointer;" onclick="openModal('{{ $detail->id }}')">
                            <h5 class="card-title">{{ $detail->trainee->trainee_number }} - {{ $detail->trainee->name }}
                            </h5>
                            <p>Total Progress: {{ $totalPercentageDone }}%</p>
                            <button type="button" class="btn btn-primary"
                                onclick="openModal('{{ $detail->id }}')">Update Progress</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



        @foreach ($sortedDetails as $detail)
            <div class="modal fade" id="subtitleModal{{ $detail->id }}" tabindex="-1" role="dialog"
                aria-labelledby="subtitleModalLabel{{ $detail->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subtitleModalLabel{{ $detail->id }}">
                                {{ $detail->trainee->name }}'s Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('casesolve.edit', $caseSolve->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="trainee_id" value="{{ $detail->trainee->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Subtitle</h5>
                                        @foreach ($detail->caseSubtitles as $subtitle)
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-2"
                                                    id="subtitle_{{ $subtitle->id }}"
                                                    name="subtitles[{{ $subtitle->id }}][subtitle]"
                                                    value="{{ $subtitle->subtitle }}" disabled>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Progress</h5>
                                        @foreach ($detail->caseSubtitles as $subtitle)
                                            <div class="form-group">
                                                <select class="form-control mb-2" id="progress_{{ $subtitle->id }}"
                                                    name="subtitles[{{ $subtitle->id }}][percentage]">
                                                    <option value="0"
                                                        {{ $subtitle->percentage == '0' ? 'selected' : '' }}>0%</option>
                                                    <option value="25"
                                                        {{ $subtitle->percentage == '25' ? 'selected' : '' }}>25%</option>
                                                    <option value="50"
                                                        {{ $subtitle->percentage == '50' ? 'selected' : '' }}>50%</option>
                                                    <option value="75"
                                                        {{ $subtitle->percentage == '75' ? 'selected' : '' }}>75%</option>
                                                    <option value="100"
                                                        {{ $subtitle->percentage == '100' ? 'selected' : '' }}>100%
                                                    </option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="leaderboardModal" tabindex="-1" role="dialog" aria-labelledby="leaderboardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaderboardModalLabel">Progress Angkatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Ranks</th>
                                        <th>Trainee</th>
                                        <th class="pr-2">Name</th>
                                        @foreach ($detail->caseSubtitles as $subtitle)
                                            <th>{{ $subtitle->subtitle }}</th>
                                        @endforeach
                                        <th class="pr-2">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">

                                    @php
                                        $sortedDetails = $sortedDetails->sortByDesc(function ($detail) {
                                            return $detail->caseSubtitles->sum('percentage');
                                        });

                                        $index = 1;
                                    @endphp
                                    @foreach ($sortedDetails as $key => $detail)
                                        @php
                                            $totalPercentage = $detail->caseSubtitles->sum('percentage');
                                            $totalSubtitleCount = $detail->caseSubtitles->count();
                                            $totalPercentageDone =
                                                $totalSubtitleCount > 0
                                                    ? round(($totalPercentage / ($totalSubtitleCount * 100)) * 100, 2)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td style="background-color: #ffffff; color: #000000; width: 150px;">
                                                #{{ $index++ }}</td>
                                            <td style="background-color: #ffffff; color: #000000; width: 150px;">
                                                {{ $detail->trainee->trainee_number }}</td>
                                            <td style="background-color: #ffffff; color: #000000; width: 250px;">
                                                {{ $detail->trainee->name }}</td>
                                            @foreach ($detail->caseSubtitles as $subtitle)
                                                @php
                                                    $percentage = $subtitle->percentage;
                                                    $bgColor = '';
                                                    $fontColor = '';
                                                    if ($percentage == '0') {
                                                        $bgColor = '#ffffff';
                                                        $fontColor = '#000000';
                                                    } elseif ($percentage == '25') {
                                                        $bgColor = '#ff0000';
                                                        $fontColor = '#ffffff';
                                                    } elseif ($percentage == '50') {
                                                        $bgColor = '#ffff00';
                                                        $fontColor = '#000000';
                                                    } elseif ($percentage == '75') {
                                                        $bgColor = '#0000ff';
                                                        $fontColor = '#ffffff';
                                                    } elseif ($percentage == '100') {
                                                        $bgColor = '#00ff00';
                                                        $fontColor = '#000000';
                                                    }
                                                @endphp
                                                <td
                                                    style="background-color: {{ $bgColor }}; color: {{ $fontColor }}; text-align: center; font-weight: bold; width: 150px;">
                                                    {{ $percentage }}%
                                                </td>
                                            @endforeach
                                            <td>{{ $totalPercentageDone }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    <script>
        function openModal(id) {
            $('#subtitleModal' + id).modal('show');
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.trainee-card');

            cards.forEach(function(card) {
                let trainee = card.getAttribute('data-trainee').toLowerCase();
                if (trainee.includes(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
        </div>
    @endsection
