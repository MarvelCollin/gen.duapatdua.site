@extends('components.navbar')
<title>Bp Details</title>
<style>
    .card {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
</style>

@section('content')
    <div class="container text-center mt-5">
        <div class="bg-light p-4 rounded shadow-sm mb-2">
            <h1 class="mb-3">{{ $bpproject->bptitle }}</h1>
            <p class="lead mb-3">Subject: <strong>{{ $bpproject->subject }}</strong></p>
            <p class="text-muted">{{ $bpproject->bpnotes }}</p>
        </div>
        <div class="mb-5 row">
            <div class="col-md-auto mb-3">
                <a class="btn btn-secondary" href="{{ route('bpprojects.index') }}">
                    Back
                </a>
            </div>
            <div class="col-md-auto mb-3">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#overallProgressModal">
                    View Leaderboard
                </button>
            </div>
            <div class="col-md-auto mb-3">
                <a href="{{ route('bpproject.details', $bpproject->id) }}" class="btn btn-info">View Project Details</a>
            </div>
            <div class="col-md mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by trainee number or name">
            </div>
        </div>

        @php
            $sortedDetails = $bpprojectDetails->sortBy(function ($detail) {
                preg_match('/T0(\d+)/', $detail->trainee->trainee_number, $matches);
                return (int) $matches[1];
            });
        @endphp

        <div class="row">
            @foreach ($sortedDetails as $detail)
                @php
                    $totalPercentage = $detail->subtitles->sum('percentage');
                    $totalSubtitleCount = $detail->subtitles->count();
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
                            <form action="{{ route('bpprojects.edit', $bpproject->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="trainee_id" value="{{ $detail->trainee->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Subtitle</h5>
                                        @foreach ($detail->subtitles as $subtitle)
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
                                        @foreach ($detail->subtitles as $subtitle)
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

        <div class="modal fade" id="overallProgressModal" tabindex="-1" role="dialog"
            aria-labelledby="overallProgressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="overallProgressModalLabel">Overall Progress</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Trainee</th>
                                        <th>Name</th>
                                        @foreach ($detail->subtitles as $subtitle)
                                            <th>{{ $subtitle->subtitle }}</th>
                                        @endforeach
                                        <th>Total Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php
                                        $sortedDetails = $sortedDetails->sortByDesc(function ($detail) {
                                            return $detail->subtitles->sum('percentage');
                                        });
                                        $index = 1;
                                    @endphp
                                    @foreach ($sortedDetails as $key => $detail)
                                        @php
                                            $totalPercentage = $detail->subtitles->sum('percentage');
                                            $totalSubtitleCount = $detail->subtitles->count();
                                            $totalPercentageDone =
                                                $totalSubtitleCount > 0
                                                    ? round(($totalPercentage / ($totalSubtitleCount * 100)) * 100, 2)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>#{{ $index++ }}</td>
                                            <td>{{ $detail->trainee->trainee_number }}</td>
                                            <td>{{ $detail->trainee->name }}</td>
                                            @foreach ($detail->subtitles as $subtitle)
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
                                                    style="background-color: {{ $bgColor }}; color: {{ $fontColor }};">
                                                    {{ $percentage }}%</td>
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
    @endsection
