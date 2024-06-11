@extends('components.navbar')
<title>Bp Teams Handling</title>
@section('content')
    <div class="container">
        <a href="{{ route('bpprojects.index') }}" class="btn btn-danger mb-2 mt-2">Back</a>
        <div class="card mb-4">
            <div class="card-header">
                <h1>{{ $bpProject->subject }}</h1>
            </div>
            <div class="card-body">
                <p><strong>Title:</strong> {{ $bpProject->bptitle }}</p>
                <p><strong>Notes:</strong> {{ $bpProject->bpnotes }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2>Teams</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($teams as $team)
                        @php
                            $teamAssigned = $bpProjectTeams->where('team_id', $team->id)->isNotEmpty();
                            $teamSubtitles = $bpProjectTeams->where('team_id', $team->id);
                        @endphp
                        <div class="col-md-4 mb-4">
                            <div class="card {{ $teamAssigned ? 'border-success' : 'border-secondary' }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $team->team_name }}</h5>
                                    <p class="card-text"><strong>Head Trainee:</strong> {{ $team->head_trainee }}</p>
                                    <p class="card-text"><strong>Trainees:</strong> {{ $team->trainees }}</p>
                                    @if ($teamAssigned)
                                        <p class="card-text"><strong>Current Task:</strong></p>
                                        <ul class="list-group">
                                            @php
                                                $sortedSubtitles = $teamSubtitles->sortByDesc('percentage');
                                            @endphp
                                            @foreach ($sortedSubtitles as $subtitle)
                                                <li
                                                    class="list-group-item {{ $subtitle->percentage == 100 ? 'bg-success' : '' }}">
                                                    <strong>
                                                        {{ $subtitle->subtitle }} ({{ $subtitle->percentage }}%)
                                                    </strong>
                                                    @if ($subtitle->external_trainee)
                                                        | Helper: {{ $subtitle->external_trainee }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="card-text"><em>No current task assigned</em></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header text-center">
                <h2>Project Details</h2>
                <div class="input-group mt-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                </div>
            </div>
            <div class="card-body">

                <div class="row align-items-center mt-3 mb-2">
                    <div class="col-md-3">
                        <strong>Subtitle</strong>
                    </div>
                    <div class="col-md-2">
                        <strong>Percentage</strong>
                    </div>
                    <div class="col-md-5">
                        <strong>External Trainee</strong>
                    </div>
                    <div class="col-md-2">
                        <strong>Teams</strong>
                    </div>
                </div>
                @foreach ($bpProjectTeams as $projectTeam)
                    <li class="list-group-item mb-3">
                        <form action="{{ route('bpprojectTeams.update', $projectTeam->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <strong>{{ $projectTeam->subtitle }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="percentage" required>
                                        <option value="0" {{ $projectTeam->percentage == '0' ? 'selected' : '' }}>0%
                                        </option>
                                        <option value="25" {{ $projectTeam->percentage == '25' ? 'selected' : '' }}>25%
                                        </option>
                                        <option value="50" {{ $projectTeam->percentage == '50' ? 'selected' : '' }}>50%
                                        </option>
                                        <option value="75" {{ $projectTeam->percentage == '75' ? 'selected' : '' }}>75%
                                        </option>
                                        <option value="100" {{ $projectTeam->percentage == '100' ? 'selected' : '' }}>
                                            100%</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="external_trainee_{{ $projectTeam->id }}"
                                        name="external_trainee" value="{{ $projectTeam->external_trainee }}"
                                        placeholder="External Trainee (Format : T0XX)">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" id="team_id_{{ $projectTeam->id }}" name="team_id">
                                        <option value="" {{ $projectTeam->team_id ? '' : 'selected' }}>Select a team
                                        </option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}"
                                                {{ $projectTeam->team_id == $team->id ? 'selected' : '' }}>
                                                {{ $team->team_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <label for="notes" class="form-label">Notes:</label>
                                    <textarea class="form-control" name="notes" id="notes" rows="6">{{ $projectTeam->notes }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2 ">Save</button>

                        </form>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                $('.list-group-item').each(function() {
                    var text = $(this).text().toLowerCase();
                    if (text.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>



    
@endsection
