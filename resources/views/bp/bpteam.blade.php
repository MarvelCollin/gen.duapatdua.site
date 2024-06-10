@extends('components.navbar')

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
        <h1 class="mb-4">Teams</h1>
        <div class="mb-5">
            <a class="btn btn-secondary" href="{{ route('bpprojects.index') }}">Back to Projects</a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#teamModal">Create Team</button>
        </div>

        <div class="row">
            @foreach ($teams as $team)
                <div class="col-md-4 mb-4">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-body" style="cursor: pointer;"
                            onclick="openModal('editTeamModal{{ $team->id }}')">
                            <h5 class="card-title">{{ $team->team_name }}</h5>
                            <p>Head Trainee: {{ $team->head_trainee }}</p>
                            <p>Trainees: {{ $team->trainees }}</p>
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#editTeamModal{{ $team->id }}">Edit Team</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal fade" id="teamModal" tabindex="-1" role="dialog" aria-labelledby="teamModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="teamModalLabel">Team Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="teamForm" action="{{ route('teams.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="team_name">Team Name</label>
                                <input type="text" class="form-control" id="team_name" name="team_name" required>
                            </div>
                            <div class="form-group">
                                <label for="head_trainee">Head Trainee</label>
                                <input type="text" class="form-control" id="head_trainee" name="head_trainee" required>
                            </div>
                            <div class="form-group">
                                <label for="trainees">Trainees</label>
                                <input type="text" class="form-control" id="trainees" name="trainees" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Team</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($teams as $team)
            <div class="modal fade" id="editTeamModal{{ $team->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editTeamModalLabel{{ $team->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTeamModalLabel{{ $team->id }}">Edit Team:
                                {{ $team->team_name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editTeamForm{{ $team->id }}" action="{{ route('teams.update', $team->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="team_name_{{ $team->id }}">Team Name</label>
                                    <input type="text" class="form-control" id="team_name_{{ $team->id }}"
                                        name="team_name" value="{{ $team->team_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="head_trainee_{{ $team->id }}">Head Trainee</label>
                                    <input type="text" class="form-control" id="head_trainee_{{ $team->id }}"
                                        name="head_trainee" value="{{ $team->head_trainee }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="trainees_{{ $team->id }}">Trainees</label>
                                    <input type="text" class="form-control" id="trainees_{{ $team->id }}"
                                        name="trainees" value="{{ $team->trainees }}" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Team</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="container text-center">
        <h1 class="mb-4">Bpproject Teams</h1>

        <div class="row">
            @foreach ($bpprojectTeams as $bpprojectTeam)
                <div class="col-md-4 mb-4">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-body">
                            <h5 class="card-title">Bpproject Team ID: {{ $bpprojectTeam->id }}</h5>
                            <p>Subtitle: {{ $bpprojectTeam->subtitle }}</p>
                            <p>Percentage: {{ $bpprojectTeam->percentage }}</p>
                            <p>Notes: {{ $bpprojectTeam->notes }}</p>
                            <a href="{{ route('bpprojectTeams.edit', $bpprojectTeam->id) }}" class="btn btn-primary">Edit Team</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#teamModal').on('show.bs.modal', function() {
                $('#teamForm').trigger('reset');
            });

            function openModal(modalId) {
                $('#' + modalId).modal('show');
            }

            $('#teamForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('teams.store') }}',
                    data: $(this).serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });

            $('.editTeamForm').submit(function(e) {
                e.preventDefault();
                var teamId = $(this).attr('id').replace('editTeamForm', '');
                $.ajax({
                    type: 'PUT',
                    url: '/teams/' + teamId,
                    data: $(this).serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection
