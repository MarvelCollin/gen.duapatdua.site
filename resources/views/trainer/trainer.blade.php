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
        <div class="header row">
            <div class="col-md-3">
                <button class="btn btn-primary" id="newTrainerBtn" data-toggle="modal" data-target="#newTrainerModal">New
                    Trainer</button>
            </div>
            <div class="col-md-5">
                <input type="text" id="searchBar" class="form-control" placeholder="Search trainers...">
            </div>

        </div>
        <div class="card-container">
            <h2 class="mt-2">Trainers</h2>
            <div class="row justify-content-center">
                @foreach ($trainers as $trainer)
                    <div class="col-lg-4 col-md-6 mb-4 trainer-card" data-id="{{ $trainer->id }}"
                        data-code="{{ $trainer->code }}" data-name="{{ $trainer->name }}"
                        data-generation="{{ $trainer->generation }}" data-position="{{ $trainer->position }}"
                        data-subject="{{ $trainer->subject }}" data-degree="{{ $trainer->degree }}"
                        data-binusian="{{ $trainer->binusian }}" data-status="{{ $trainer->status }}">
                        <div class="card h-100 active-trainer" data-toggle="modal"
                            data-target="#editTrainerModal{{ $trainer->id }}">
                            <img src="{{ asset('storage/' . $trainer->profile) }}" alt="Profile Image"
                                class="card-img-top img-fluid" style="width: auto; height: 40vh; border-radius: 5%">
                            <div class="card-body text-center">
                                <h3 class="card-title">{{ $trainer->code }} {{ $trainer->generation }}</h3>
                                <h5 class="card-text">{{ $trainer->name }}</h5>
                                <h5 class="card-text">{{ $trainer->position }}</h5>
                                <h6 class="card-text">{{ $trainer->subject }}</h6>
                                <p class="card-text">{{ $trainer->degree }} | {{ $trainer->binusian }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

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
                            <form id="editTrainerForm{{ $trainer->id }}"
                                action="{{ route('trainer.update', $trainer->id) }}" method="POST"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="edit_code{{ $trainer->id }}">Trainer Code / Initial:</label>
                                    <input type="text" id="edit_code{{ $trainer->id }}" name="code"
                                        class="form-control" required value="{{ $trainer->code }}">
                                </div>

                                <div class="form-group">
                                    <label for="edit_name{{ $trainer->id }}">Name:</label>
                                    <input type="text" id="edit_name{{ $trainer->id }}" name="name"
                                        class="form-control" required value="{{ $trainer->name }}"
                                        value="{{ $trainer->name }}">
                                    <div class="invalid-feedback">Please fill in the name.</div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_generation{{ $trainer->id }}">Generation:</label>
                                    <div class="suggestions">
                                        <select class="form-control generation-suggestions" name="generation"
                                            id="edit_generation{{ $trainer->id }}" required>
                                            <option value="">Select a generation</option>
                                            @foreach ($trainers->unique('generation') as $trainerOption)
                                                <option value="{{ $trainerOption->generation }}"
                                                    {{ $trainer->generation == $trainerOption->generation ? 'selected' : '' }}>
                                                    {{ $trainerOption->generation }}
                                                </option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control mt-2 d-none other-generation"
                                        name="other_generation" placeholder="Enter generation">
                                    <div class="invalid-feedback">Please fill in the generation.</div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_position{{ $trainer->id }}">Position:</label>
                                    <div class="suggestions">
                                        <select class="form-control position-suggestions" name="position"
                                            id="edit_position{{ $trainer->id }}" required>
                                            <option value="">Select a position</option>
                                            @foreach ($trainers->unique('position') as $trainerOption)
                                                <option value="{{ $trainerOption->position }}"
                                                    {{ $trainer->position == $trainerOption->position ? 'selected' : '' }}>
                                                    {{ $trainerOption->position }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control mt-2 d-none other-position"
                                        name="other_position" placeholder="Enter position">
                                    <div class="invalid-feedback">Please fill in the position.</div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_subject{{ $trainer->id }}">Subject:</label>
                                    <div class="subject-checkboxes">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="ads{{ $trainer->id }}" value="A&DS">
                                            <label class="form-check-label" for="ads{{ $trainer->id }}">A&DS</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="java{{ $trainer->id }}" value="Java">
                                            <label class="form-check-label" for="java{{ $trainer->id }}">Java</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="database{{ $trainer->id }}" value="Database">
                                            <label class="form-check-label"
                                                for="database{{ $trainer->id }}">Database</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="web_design{{ $trainer->id }}" value="Web Design">
                                            <label class="form-check-label" for="web_design{{ $trainer->id }}">Web
                                                Design</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="web_programming{{ $trainer->id }}" value="Web Programming">
                                            <label class="form-check-label" for="web_programming{{ $trainer->id }}">Web
                                                Programming</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="networking{{ $trainer->id }}" value="Networking">
                                            <label class="form-check-label"
                                                for="networking{{ $trainer->id }}">Networking</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="python{{ $trainer->id }}" value="Python">
                                            <label class="form-check-label"
                                                for="python{{ $trainer->id }}">Python</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subject[]"
                                                id="computer_vision{{ $trainer->id }}" value="Computer Vision">
                                            <label class="form-check-label"
                                                for="computer_vision{{ $trainer->id }}">Computer Vision</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="selectedSubjects{{ $trainer->id }}"></div>

                                <div class="form-group">
                                    <label for="edit_binusian{{ $trainer->id }}">Binusian:</label>
                                    <div class="suggestions">
                                        <select class="form-control binusian-suggestions" name="binusian"
                                            id="edit_binusian{{ $trainer->id }}" required>
                                            <option value="">Select
                                                a binusian</option>
                                            @foreach ($trainers->unique('binusian') as $trainerOption)
                                                <option value="{{ $trainerOption->binusian }}"
                                                    {{ $trainer->binusian == $trainerOption->binusian ? 'selected' : '' }}>
                                                    {{ $trainerOption->binusian }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control mt-2 d-none other-binusian"
                                        name="other_binusian" placeholder="Enter binusian">
                                    <div class="invalid-feedback">Please fill in the binusian.</div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_degree{{ $trainer->id }}">Degree:</label>
                                    <div class="suggestions">
                                        <select class="form-control degree-suggestions" name="degree"
                                            id="edit_degree{{ $trainer->id }}" required>
                                            <option value="">Select
                                                a degree</option>
                                            @foreach ($trainers->unique('degree') as $trainerOption)
                                                <option value="{{ $trainerOption->degree }}"
                                                    {{ $trainer->degree == $trainerOption->degree ? 'selected' : '' }}>
                                                    {{ $trainerOption->degree }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control mt-2 d-none other-degree"
                                        name="other_degree" placeholder="Enter degree">
                                    <div class="invalid-feedback">Please fill in the degree.</div>
                                </div>


                                <div class="form-group">
                                    <label for="profile">Profile Picture</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="profile{{ $trainer->id }}" name="profile"
                                            onchange="updateFileName('profile{{ $trainer->id }}', 'profile_label{{ $trainer->id }}')" required>
                                        <label class="custom-file-label" id="profile_label{{ $trainer->id }}" for="profile">Choose
                                            file...</label>
                                        <div class="invalid-feedback">Please upload a profile picture.</div>
                                    </div>
                                </div>


                                <input type="hidden" id="edit_trainer_id{{ $trainer->id }}" name="trainer_id"
                                    value="{{ $trainer->id }}">

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    var subjectsArray = {!! json_encode(explode(',', $trainer->subject)) !!};

                    subjectsArray.forEach(function(subject) {
                        $('#editTrainerModal{{ $trainer->id }} .subject-checkboxes input[type="checkbox"][value="' +
                            subject.trim() + '"]').prop('checked', true);
                    });

                    $(".position-suggestions").change(function() {
                        var selectedOption = $(this).val();
                        if (selectedOption === "other") {
                            $(this).closest('.form-group').find('.other-position').removeClass('d-none').focus();
                        } else {
                            $(this).closest('.form-group').find('.other-position').addClass('d-none');
                        }
                    });

                    $(".generation-suggestions").change(function() {
                        var selectedOption = $(this).val();
                        if (selectedOption === "other") {
                            $(this).closest('.form-group').find('.other-generation').removeClass('d-none').focus();
                        } else {
                            $(this).closest('.form-group').find('.other-generation').addClass('d-none');
                        }
                    });

                    $(".binusian-suggestions").change(function() {
                        var selectedOption = $(this).val();
                        if (selectedOption === "other") {
                            $(this).closest('.form-group').find('.other-binusian').removeClass('d-none').focus();
                        } else {
                            $(this).closest('.form-group').find('.other-binusian').addClass('d-none');
                        }
                    });

                    $(".degree-suggestions").change(function() {
                        var selectedOption = $(this).val();
                        if (selectedOption === "other") {
                            $(this).closest('.form-group').find('.other-degree').removeClass('d-none').focus();
                        } else {
                            $(this).closest('.form-group').find('.other-degree').addClass('d-none');
                        }
                    });

                    // $('#editTrainerForm{{ $trainer->id }}').submit(function(event) {
                    //     if ($('#edit_generation{{ $trainer->id }}').val() === 'other') {
                    //         $('#edit_generation{{ $trainer->id }}').val($('.other-generation').val());
                    //     }
                    //     if ($('#edit_position{{ $trainer->id }}').val() === 'other') {
                    //         $('#edit_position{{ $trainer->id }}').val($('.other-position').val());
                    //     }
                    //     if ($('#edit_binusian{{ $trainer->id }}').val() === 'other') {
                    //         $('#edit_binusian{{ $trainer->id }}').val($('.other-binusian').val());
                    //     }
                    //     if ($('#edit_degree{{ $trainer->id }}').val() === 'other') {
                    //         $('#edit_degree{{ $trainer->id }}').val($('.other-degree').val());
                    //     }
                    // });
                });
            </script>
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
                                <div class="suggestions">
                                    <select class="form-control generation-suggestions" name="generation" id="generation"
                                        required>
                                        <option value="">Select a generation</option>
                                        @foreach ($trainers->unique('generation') as $trainerOption)
                                            <option value="{{ $trainerOption->generation }}">
                                                {{ $trainerOption->generation }}</option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control mt-2 d-none other-generation"
                                    name="other_generation" placeholder="Enter generation">
                                <div class="invalid-feedback">Please fill in the generation.</div>
                            </div>

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the name.</div>
                            </div>

                            <div class="form-group">
                                <label for="position">Position:</label>
                                <div class="suggestions">
                                    <select class="form-control position-suggestions" name="position" id="position"
                                        required>
                                        <option value="">Select a position</option>
                                        @foreach ($trainers->unique('position') as $trainerOption)
                                            <option value="{{ $trainerOption->position }}">{{ $trainerOption->position }}
                                            </option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control mt-2 d-none other-position"
                                    name="other_position" placeholder="Enter position">
                                <div class="invalid-feedback">Please fill in the position.</div>
                            </div>

                            <div class="form-group">
                                <label for="binusian">Binusian:</label>
                                <div class="suggestions">
                                    <select class="form-control binusian-suggestions" name="binusian" id="binusian"
                                        required>
                                        <option value="">Select a binusian</option>
                                        @foreach ($trainers->unique('binusian') as $trainerOption)
                                            <option value="{{ $trainerOption->binusian }}">{{ $trainerOption->binusian }}
                                            </option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control mt-2 d-none other-binusian"
                                    name="other_binusian" placeholder="Enter binusian">
                                <div class="invalid-feedback">Please fill in the binusian.</div>
                            </div>

                            <div class="form-group">
                                <label for="degree">Degree:</label>
                                <div class="suggestions">
                                    <select class="form-control degree-suggestions" name="degree" id="degree"
                                        required>
                                        <option value="">Select a degree</option>
                                        @foreach ($trainers->unique('degree') as $trainerOption)
                                            <option value="{{ $trainerOption->degree }}">{{ $trainerOption->degree }}
                                            </option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control mt-2 d-none other-degree" name="other_degree"
                                    placeholder="Enter degree">
                                <div class="invalid-feedback">Please fill in the degree.</div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <div class="subject-checkboxes">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="A&DS" name="subject[]"
                                            value="A&DS">
                                        <label class="form-check-label" for="A&DS">A&DS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="java" name="subject[]"
                                            value="Java">
                                        <label class="form-check-label" for="java">Java</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="database" name="subject[]"
                                            value="Database">
                                        <label class="form-check-label" for="database">Database</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="web_design" name="subject[]"
                                            value="Web Design">
                                        <label class="form-check-label" for="web_design">Web Design</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="web_programming"
                                            name="subject[]" value="Web Programming">
                                        <label class="form-check-label" for="web_programming">Web Programming</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="networking" name="subject[]"
                                            value="Networking">
                                        <label class="form-check-label" for="networking">Networking</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="python" name="subject[]"
                                            value="Python">
                                        <label class="form-check-label" for="python">Python</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="computer_vision"
                                            name="subject[]" value="Computer Vision">
                                        <label class="form-check-label" for="computer_vision">Computer Vision</label>
                                    </div>
                                </div>
                                <div id="selectedSubjects"></div>
                            </div>

                            <div class="form-group">
                                <label for="profile">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="profile_create" name="profile"
                                        onchange="updateFileName('profile_create', 'profile_label_create')" required>
                                    <label class="custom-file-label" id="profile_label_create" for="profile">Choose
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
            function updateFileName(inputId, labelId) {
                var input = document.getElementById(inputId);
                var label = document.getElementById(labelId);

                if (input.files.length > 0) {
                    var fileName = input.files[0].name;
                    label.textContent = fileName;
                } else {
                    label.textContent = "Choose file...";
                }
            }

            document.getElementById('searchBar').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let cards = document.querySelectorAll('.trainer-card');

                cards.forEach(function(card) {
                    let code = card.getAttribute('data-code').toLowerCase();
                    let name = card.getAttribute('data-name').toLowerCase();
                    let generation = card.getAttribute('data-generation').toLowerCase();
                    let position = card.getAttribute('data-position').toLowerCase();
                    let subject = card.getAttribute('data-subject').toLowerCase();
                    let degree = card.getAttribute('data-degree').toLowerCase();
                    let binusian = card.getAttribute('data-binusian').toLowerCase();

                    if (code.includes(filter) || name.includes(filter) || generation.includes(filter) ||
                        position.includes(filter) || subject.includes(filter) || degree.includes(filter) ||
                        binusian.includes(filter)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });


            $(document).ready(function() {
                const uniquePositions = @json(array_values(array_unique($trainers->pluck('position')->toArray())));
                const uniqueSubjects = @json(array_values(array_unique($trainers->pluck('subject')->toArray())));
                const uniqueGenerations = @json(array_values(array_unique($trainers->pluck('generation')->toArray())));
                const uniqueBinusians = @json(array_values(array_unique($trainers->pluck('binusian')->toArray())));
                const uniqueDegrees = @json(array_values(array_unique($trainers->pluck('degree')->toArray())));

                $(".position-suggestions").change(function() {
                    var selectedOption = $(this).val();
                    if (selectedOption === "other") {
                        $(this).closest('.form-group').find('.other-position').removeClass('d-none').focus();
                    } else {
                        $(this).closest('.form-group').find('.other-position').addClass('d-none');
                    }
                });

                $(".generation-suggestions").change(function() {
                    var selectedOption = $(this).val();
                    if (selectedOption === "other") {
                        $(this).closest('.form-group').find('.other-generation').removeClass('d-none').focus();
                    } else {
                        $(this).closest('.form-group').find('.other-generation').addClass('d-none');
                    }
                });

                $(".binusian-suggestions").change(function() {
                    var selectedOption = $(this).val();
                    if (selectedOption === "other") {
                        $(this).closest('.form-group').find('.other-binusian').removeClass('d-none').focus();
                    } else {
                        $(this).closest('.form-group').find('.other-binusian').addClass('d-none');
                    }
                });

                $(".degree-suggestions").change(function() {
                    var selectedOption = $(this).val();
                    if (selectedOption === "other") {
                        $(this).closest('.form-group').find('.other-degree').removeClass('d-none').focus();
                    } else {
                        $(this).closest('.form-group').find('.other-degree').addClass('d-none');
                    }
                });

                $('#trainerForm, @foreach ($trainers as $trainer)#editTrainerForm{{ $trainer->id }}, @endforeach')
                    .submit(function(event) {
                        if ($('#generation').val() === 'other') {
                            $('input[name="generation"]').val($('.other-generation').val());
                        }
                        if ($('#position').val() === 'other') {
                            $('input[name="position"]').val($('.other-position').val());
                        }
                        if ($('#binusian').val() === 'other') {
                            $('input[name="binusian"]').val($('.other-binusian').val());
                        }
                        if ($('#degree').val() === 'other') {
                            $('input[name="degree"]').val($('.other-degree').val());
                        }
                    });
            });
        </script>
    </div>
@endsection
