@extends('components.navbar')
<title>BP Projects</title>
@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <div class="container mt-4">
        <div class="header mb-4">
            <div class="row justify-content-end align-items-center">
                <div class="col-md-10">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="row ">
                            <a href="{{ route('rundowns.index') }}" class="btn btn-primary mr-3">Rundowns</a>

                            <button class="btn btn-primary mr-3" id="newBpprojectBtn" data-toggle="modal"
                                data-target="#newBpprojectModal">New BP Project</button>
                            <button class="btn btn-primary mr-3" data-toggle="modal" data-target="#teamModal">Teams</button>
                        </div>
                        <div class="search-container">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-container">
            <div class="row" id="bpprojects">
                @foreach ($bpprojects->reverse() as $bpproject)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body animate__animated animate__fadeIn">
                                <h5 class="card-title text-center">{{ $bpproject->subject }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="card-text">title: <strong>{{ $bpproject->bptitle }}</strong></p>
                                        <p class="card-text">{{ $bpproject->created_at->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="d-flex text-center justify-content-between align-items-center mt-3">
                                    <a href="{{ route('bpprojects.show', $bpproject->id) }}" class="btn btn-success">View
                                        Progress</a>
                                    <button class="btn btn-primary editBpprojectBtn" data-toggle="modal"
                                        data-target="#editBpprojectModal_{{ $bpproject->id }}">Edit BP Project</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editBpprojectModal_{{ $bpproject->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editBpprojectModalLabel_{{ $bpproject->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBpprojectModal">Edit BP Project</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('bpprojects.update', $bpproject->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bptitle">Title:</label>
                                                    <input type="text" id="bptitle" name="bptitle" class="form-control"
                                                        value="{{ $bpproject->bptitle }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="subject">Subject:</label>
                                                    <select id="subject" name="subject" class="form-control"
                                                        value="{{ $bpproject->subject }}">
                                                        <option value="A&DS">A&DS</option>
                                                        <option value="Java">Java</option>
                                                        <option value="Database">Database</option>
                                                        <option value="Web Design">Web Design</option>
                                                        <option value="Web Programming">Web Programming</option>
                                                        <option value="Networking">Networking</option>
                                                        <option value="Python">Python</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bpnotes">Notes:</label>
                                                    <input type="text" id="bpnotes" name="bpnotes" class="form-control"
                                                        required value="{{ $bpproject->bpnotes }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subtitles">Subtitles:</label>
                                                    <div id="subtitle-container">
                                                        @if ($bpprojectDetails)
                                                            @foreach ($bpprojectDetails as $bpprojectDetail)
                                                                @foreach ($bpprojectSubtitles->unique('subtitle') as $subtitle)
                                                                    @if ($bpprojectDetail->bpproject_id == $bpproject->id && $subtitle->bpproject_detail_id == $bpprojectDetail->id)
                                                                        <div class="input-group mb-2">
                                                                            <input type="text" name="subtitles[]"
                                                                                class="form-control"
                                                                                value="{{ $subtitle->subtitle }}">
                                                                            <div class="input-group-append">
                                                                                <button type="button"
                                                                                    class="btn btn-danger remove-subtitle">Remove</button>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        @endif

                                                    </div>
                                                    <button type="button" class="btn btn-primary mt-2" id="add-subtitle"
                                                        data-bpprojectid="{{ $bpproject->id }}">Add New
                                                        Subtitle</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Edit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="modal fade" id="newBpprojectModal" tabindex="-1" role="dialog"
            aria-labelledby="newBpprojectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newBpprojectModalLabel">Create New BP Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('bpprojects.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bptitle">Title:</label>
                                        <input type="text" id="bptitle" name="bptitle" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Subject:</label>
                                        <select id="subject" name="subject" class="form-control" required>
                                            <option value="A&DS">A&DS</option>
                                            <option value="Java">Java</option>
                                            <option value="Database">Database</option>
                                            <option value="Web Design">Web Design</option>
                                            <option value="Web Programming">Web Programming</option>
                                            <option value="Networking">Networking</option>
                                            <option value="Python">Python</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bpnotes">Notes:</label>
                                        <input type="text" id="bpnotes" name="bpnotes" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subtitles">Subtitles:</label>
                                        <div id="subtitle-container">
                                            <div class="input-group mb-2">
                                                <input type="text" name="subtitles[]" class="form-control" required>
                                                <div class="input-group-append">
                                                    <button type="button"
                                                        class="btn btn-danger remove-subtitle">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" id="add-subtitle" class="btn btn-primary mt-2">Add New
                                            Subtitle</button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="teamModal" tabindex="-1" role="dialog" aria-labelledby="teamModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="teamModalLabel">Teams</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($teams as $team)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $team->team_name }}</h5>
                                            <p class="card-text"><strong>Head Trainee:</strong> {{ $team->head_trainee }}
                                            </p>
                                            <p class="card-text"><strong>Trainees:</strong> {{ $team->trainees }}</p>
                                            <div class="d-flex justify-content-between">
                                                <div class="">
                                                    <button class="btn btn-warning" data-toggle="modal"
                                                        data-target="#editTeamModal_{{ $team->id }}">Edit</button>
                                                </div>
                                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="editTeamModal_{{ $team->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="editTeamModalLabel_{{ $team->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editTeamModalLabel_{{ $team->id }}">Edit
                                                    Team</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('teams.update', $team->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="team_name_{{ $team->id }}">Team Name</label>
                                                        <input type="text" class="form-control"
                                                            id="team_name_{{ $team->id }}" name="team_name"
                                                            value="{{ $team->team_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="head_trainee_{{ $team->id }}">Head Trainee</label>
                                                        <input type="text" class="form-control"
                                                            id="head_trainee_{{ $team->id }}" name="head_trainee"
                                                            value="{{ $team->head_trainee }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="trainees_{{ $team->id }}">Trainees</label>
                                                        <textarea class="form-control" id="trainees_{{ $team->id }}" name="trainees" required>{{ $team->trainees }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#createTeamModal">Create Team</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog"
            aria-labelledby="createTeamModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTeamModalLabel">Create Team</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('teams.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="team_name">Team Name</label>
                                <input type="text" class="form-control" id="team_name" name="team_name" required>
                            </div>
                            <div class="form-group">
                                <label for="head_trainee">Head Trainee</label>
                                <input type="text" class="form-control" id="head_trainee" name="head_trainee"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="trainees">Trainees</label>
                                <textarea class="form-control" id="trainees" name="trainees" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Team</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('add-subtitle').addEventListener('click', function() {
                const newInputGroup = createInputGroup();
                document.getElementById('subtitle-container').appendChild(newInputGroup);
            });


            function createInputGroup() {
                const newInputGroup = document.createElement('div');
                newInputGroup.classList.add('input-group', 'mb-2');

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'subtitles[]';
                newInput.classList.add('form-control');
                newInput.required = true;

                const inputGroupAppend = document.createElement('div');
                inputGroupAppend.classList.add('input-group-append');

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.classList.add('btn', 'btn-danger', 'remove-subtitle');
                removeButton.textContent = 'Remove';

                removeButton.addEventListener('click', function() {
                    newInputGroup.remove();
                });

                inputGroupAppend.appendChild(removeButton);
                newInputGroup.appendChild(newInput);
                newInputGroup.appendChild(inputGroupAppend);

                return newInputGroup;
            }

            document.querySelectorAll('.remove-subtitle').forEach(button => {
                button.addEventListener('click', function() {
                    button.closest('.input-group').remove();
                });
            });
        });
    </script>
@endsection
