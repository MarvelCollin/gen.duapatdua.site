@extends('components.navbar')
<title>Trainer List</title>
@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/trainer.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        .grayscale {
            filter: grayscale(100%);
        }

        .inactive-trainer .card {
            background-color: #f0f0f0;
        }

        .inactive-trainer .card-body {
            background-color: #f0f0f0;
            color: #999;
        }

        .suggestions {
            margin-top: 5px;
        }

        .suggestion-item {
            cursor: pointer;
            padding: 5px;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
    </style>
    <div class="container mt-4">
        <div class="header">
            <button class="btn btn-primary" id="newTrainerBtn" data-toggle="modal" data-target="#newTrainerModal">New
                Trainer</button>
        </div>
        <div class="card-container">
            <h2 class="mt-2">Trainers</h2>
            <div class="row justify-content-center">
                @foreach ($trainers as $trainer)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 active-trainer" data-toggle="modal"
                            data-target="#editTrainerModal{{ $trainer->id }}" data-id="{{ $trainer->id }}"
                            data-code="{{ $trainer->code }}" data-name="{{ $trainer->name }}"
                            data-generation="{{ $trainer->generation }}" data-position="{{ $trainer->position }}"
                            data-subject="{{ $trainer->subject }}"
                            data-profile="{{ asset('storage/' . $trainer->profile) }}"
                            data-status="{{ $trainer->status }}">
                            <img src="{{ asset('storage/' . $trainer->profile) }}" alt="Profile Image"
                                class="card-img-top img-fluid" style="width: auto; height: 40vh; border-radius: 5%">
                            <div class="card-body text-center">
                                <h3 class="card-title">{{ $trainer->code }} {{ $trainer->generation }}</h3>
                                <h5 class="card-text">{{ $trainer->name }}</h5>
                                <h5 class="card-text">{{ $trainer->position }}</h5>
                                <h6 class="card-text">{{ $trainer->subject }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Edit Trainer Modals -->
        @foreach ($trainers as $trainer)
            <div class="modal fade" id="editTrainerModal{{ $trainer->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editTrainerModalLabel{{ $trainer->id }}" aria-hidden="true" data-backdrop="static"
                data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTrainerModalLabel{{ $trainer->id }}">Edit Trainer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editTrainerForm{{ $trainer->id }}" action="{{ route('trainer.update', $trainer->id)}}" method="POST" enctype="multipart/form-data"
                                class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="edit_code{{ $trainer->id }}">Trainer Code / Initial:</label>
                                    <input type="text" id="edit_code{{ $trainer->id }}" name="code"
                                        class="form-control" required value="{{ $trainer->code }}">
                                    <div class="invalid-feedback">Please start with T0.</div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_name{{ $trainer->id }}">Name:</label>
                                    <input type="text" id="edit_name{{ $trainer->id }}" name="name"
                                        class="form-control" required value="{{ $trainer->name }}">
                                    <div class="invalid-feedback">Please fill in the name.</div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_generation{{ $trainer->id }}">Generation:</label>
                                    <input type="text" id="edit_generation{{ $trainer->id }}" name="generation"
                                        class="form-control" required value="{{ $trainer->generation }}">
                                    <div class="invalid-feedback">Please fill in the generation.</div>
                                    <div class="suggestions">
                                        <select class="form-control edit-generation-suggestions"
                                            data-target-input="#edit_generation{{ $trainer->id }}">
                                            <option value="">Select a generation</option>
                                            @foreach ($trainers->unique('generation') as $trainerOption)
                                                <option value="{{ $trainerOption->generation }}">
                                                    {{ $trainerOption->generation }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_position{{ $trainer->id }}">Position:</label>
                                    <input type="text" id="edit_position{{ $trainer->id }}" name="position"
                                        class="form-control" required value="{{ $trainer->position }}">
                                    <div class="invalid-feedback">Please fill in the position.</div>
                                    <div class="suggestions">
                                        <select class="form-control position-suggestions"
                                            data-target-input="#edit_position{{ $trainer->id }}">
                                            <option value="">Select a position</option>
                                            @foreach ($trainers->unique('position') as $trainerOption)
                                                <option value="{{ $trainerOption->position }}">
                                                    {{ $trainerOption->position }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_subject{{ $trainer->id }}">Subject:</label>
                                    <div class="subject-checkboxes">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="ads{{ $trainer->id }}" value="A&DS">
                                            <label class="form-check-label" for="ads{{ $trainer->id }}">A&DS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="java{{ $trainer->id }}" value="Java">
                                            <label class="form-check-label" for="java{{ $trainer->id }}">Java</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="database{{ $trainer->id }}" value="Database">
                                            <label class="form-check-label" for="database{{ $trainer->id }}">Database</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="web_design{{ $trainer->id }}" value="Web Design">
                                            <label class="form-check-label" for="web_design{{ $trainer->id }}">Web Design</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="web_programming{{ $trainer->id }}" value="Web Programming">
                                            <label class="form-check-label" for="web_programming{{ $trainer->id }}">Web Programming</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="networking{{ $trainer->id }}" value="Networking">
                                            <label class="form-check-label" for="networking{{ $trainer->id }}">Networking</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="python{{ $trainer->id }}" value="Python">
                                            <label class="form-check-label" for="python{{ $trainer->id }}">Python</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]" id="computer_vision{{ $trainer->id }}" value="Computer Vision">
                                            <label class="form-check-label" for="computer_vision{{ $trainer->id }}">Computer Vision</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="selectedSubjects{{ $trainer->id }}"></div>

                                <div class="form-group">
                                    <label for="profile">Profile Picture</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="profile" name="profile"
                                            onchange="updateFileName('profile', 'profile_label')" required>
                                        <label class="custom-file-label" id="profile_label" for="profile">Choose
                                            file...</label>
                                        <div class="invalid-feedback">Please upload a profile picture.</div>
                                    </div>
                                </div>
                                

                                <input type="hidden" id="edit_trainer_id{{ $trainer->id }}" name="trainer_id"
                                    value="{{ $trainer->id }}">

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-danger" id="deleteTrainerBtn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="newTrainerModal" tabindex="-1" role="dialog"
            aria-labelledby="newTrainerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newTrainerModalLabel">Create New Trainer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="trainerForm" action="{{ route('trainer.store') }}" method="POST"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="code">Trainer Code / Initial:</label>
                                <input type="text" id="code" name="code" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="generation">Generation:</label>
                                <input type="text" id="generation" name="generation" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the generation.</div>
                                <div class="suggestions">
                                    <select class="form-control generation-suggestions">
                                        <option value="">Select a generation</option>
                                        @foreach ($trainers->unique() as $trainerOption)
                                            <option value="{{ $trainerOption->generation }}">
                                                {{ $trainerOption->generation }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the name.</div>
                            </div>

                            <div class="form-group">
                                <label for="position">Position:</label>
                                <input type="text" id="position" name="position" class="form-control position-input"
                                    required>
                                <div class="invalid-feedback">Please fill in the position.</div>
                                <div class="suggestions">
                                    <select class="form-control position-suggestions">
                                        <option value="">Select a position</option>
                                        @foreach ($trainers->unique() as $trainerOption)
                                            <option value="{{ $trainerOption->position }}">{{ $trainerOption->position }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <div class="subject-checkboxes">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="database" name="subject[]" value="Database">
                                        <label class="form-check-label" for="database">Database</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="web_design" name="subject[]" value="Web Design">
                                        <label class="form-check-label" for="web_design">Web Design</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="web_programming" name="subject[]" value="Web Programming">
                                        <label class="form-check-label" for="web_programming">Web Programming</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="networking" name="subject[]" value="Networking">
                                        <label class="form-check-label" for="networking">Networking</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="python" name="subject[]" value="Python">
                                        <label class="form-check-label" for="python">Python</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="computer_vision" name="subject[]" value="Computer Vision">
                                        <label class="form-check-label" for="computer_vision">Computer Vision</label>
                                    </div>
                                </div>
                                <div id="selectedSubjects"></div>
                            </div>
                            

                            <div class="form-group">
                                <label for="profile">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="profile" name="profile"
                                        onchange="updateFileName('profile', 'profile_label')" required>
                                    <label class="custom-file-label" id="profile_label" for="profile">Choose
                                        file...</label>
                                    <div class="invalid-feedback">Please upload a profile picture.</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                var subjectsArray = {!! json_encode(explode(',', $trainer->subject)) !!};
        
                subjectsArray.forEach(function(subject) {
                    $('#editTrainerModal{{ $trainer->id }} .subject-checkboxes input[type="checkbox"][value="' + subject.trim() + '"]').prop('checked', true);
                });
            });
        </script>
        <script>
            @foreach ($trainers as $trainer)
                $('#editTrainerModal{{ $trainer->id }}').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); 
                    var trainerId = button.data('id');
                    var code = button.data('code');
                    var name = button.data('name');
                    var generation = button.data('generation');
                    var position = button.data('position');
                    var subject = button.data('subject');

                    var modal = $(this);
                    modal.find('#edit_code{{ $trainer->id }}').val(code);
                    modal.find('#edit_name{{ $trainer->id }}').val(name);
                    modal.find('#edit_generation{{ $trainer->id }}').val(generation);
                    modal.find('#edit_position{{ $trainer->id }}').val(position);
                    modal.find('#edit_trainer_id{{ $trainer->id }}').val(trainerId);

                    var subjectsArray = subject.split(',').map(function(item) {
                        return item.trim();
                    });

                    subjectsArray.forEach(function(subject) {
                        modal.find('.subject-checkboxes input[type="checkbox"][value="' + subject + '"]').prop(
                            'checked', true);
                    });
                });

                window.updateFileName = function(inputId, labelId) {
                    const input = document.getElementById(inputId);
                    const label = document.getElementById(labelId);
                    const fileName = input.files[0].name;
                    label.innerText = fileName;
                };
            @endforeach

            $(document).ready(function() {
                const uniquePositions = @json(array_values(array_unique($trainers->pluck('position')->toArray())));
                const uniqueSubjects = @json(array_values(array_unique($trainers->pluck('subject')->toArray())));
                const uniqueGenerations = @json(array_values(array_unique($trainers->pluck('generation')->toArray())));

                $("#position, #edit_position").autocomplete({
                    source: uniquePositions
                });

                $("#subject, #edit_subject").autocomplete({
                    source: uniqueSubjects
                });

                $("#generation, #edit_generation").autocomplete({
                    source: uniqueGenerations
                });

                $('.position-suggestions, .subject-suggestions, .generation-suggestions, .edit-generation-suggestions, .edit-subject-suggestions')
                    .change(function() {
                        var selectedOption = $(this).val();
                        var input = $(this).closest('.form-group').find('input');
                        if (selectedOption === 'other') {
                            input.val('');
                            input.prop('disabled', false).focus();
                        } else {
                            input.val(selectedOption).prop('disabled', true);
                        }
                    });

                $('.position-input, #edit_position, #edit_subject, #edit_generation').on('input', function() {
                    var inputValue = $(this).val();
                    var select = $(this).closest('.form-group').find('select');
                    if (inputValue.trim() !== '') {
                        select.val('other');
                    }
                });

            
            });
        </script>
    </div>
@endsection
