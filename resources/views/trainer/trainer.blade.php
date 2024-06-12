@extends('components.navbar')
<title>Trainer List</title>
@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/trainer.css') }}">
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
    </style>
    <div class="container mt-4">
        <div class="header">
            <button class="btn btn-primary" id="newTrainerBtn" data-toggle="modal" data-target="#newTrainerModal">New
                Trainer</button>
        </div>
        <div class="card-container">
            <h2 class="mt-2">Active Trainers</h2>
            <div class="row justify-content-center">
                @foreach ($trainers as $trainer)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 active-trainer" onclick="showEditModal(this)" data-id="{{ $trainer->id }}"
                            data-code="{{ $trainer->code }}" data-name="{{ $trainer->name }}"
                            data-generation="{{ $trainer->generation }}" data-position="{{ $trainer->position }}"
                            data-subject="{{ $trainer->subject }}"
                            data-profile="{{ asset('storage/' . $trainer->profile) }}" data-status="{{ $trainer->status }}">
                            <img src="{{ asset('storage/' . $trainer->profile) }}" alt="Profile Image"
                                class="card-img-top img-fluid" style="width: auto; height: 40vh; border-radius: 5%">
                            <div class="card-body text-center">
                                <h3 class="card-title">
                                    {{ $trainer->code }}
                                </h3>
                                <h5 class="card-text">{{ $trainer->name }}</h5>
                                <h5 class="card-text">{{ $trainer->generation }} | {{ $trainer->position }}</h5>
                                <div class="row justify-content-between">
                                    <p style="font-size: 1vw" class="card-text">Subject: {{ $trainer->subject }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="modal fade" id="newTrainerModal" tabindex="-1" role="dialog" aria-labelledby="newTrainerModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                                <div class="invalid-feedback">Please start with T0.</div>
                            </div>

                            <div class="form-group">
                                <label for="generation">Generation:</label>
                                <input type="text" id="generation" name="generation" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the generation.</div>
                            </div>

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the name.</div>
                            </div>

                            <div class="form-group">
                                <label for="position">Position:</label>
                                <input type="text" id="position" name="position" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the position.</div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <input type="text" id="subject" name="subject" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the subject.</div>
                            </div>

                            <div class="form-group">
                                <label for="profile">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="profile" name="profile"
                                        onchange="updateFileName()">
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

        <div class="modal fade" id="editTrainerModal" tabindex="-1" role="dialog"
            aria-labelledby="editTrainerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTrainerModalLabel">Edit Trainer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editTrainerForm" action="{{ route('trainer.update', $trainer->id) }}" method="POST"
                            enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="edit_code">Trainer Code / Initial:</label>
                                <input type="text" id="edit_code" name="code" class="form-control" required>
                                <div class="invalid-feedback">Please start with T0.</div>
                            </div>

                            <div class="form-group">
                                <label for="edit_name">Name:</label>
                                <input type="text" id="edit_name" name="name" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the name.</div>
                            </div>

                            <div class="form-group">
                                <label for="edit_generation">Generation:</label>
                                <input type="text" id="edit_generation" name="generation" class="form-control"
                                    required>
                                <div class="invalid-feedback">Please fill in the generation.</div>
                            </div>

                            <div class="form-group">
                                <label for="edit_position">Position:</label>
                                <input type="text" id="edit_position" name="position" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the position.</div>
                            </div>

                            <div class="form-group">
                                <label for="edit_subject">Subject:</label>
                                <input type="text" id="edit_subject" name="subject" class="form-control" required>
                                <div class="invalid-feedback">Please fill in the subject.</div>
                            </div>

                            <div class="form-group">
                                <label for="edit_profile">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="edit_profile" name="profile"
                                        onchange="updateFileName()">
                                    <label class="custom-file-label" id="edit_profile_label" for="edit_profile">Choose
                                        file...</label>
                                    <div class="invalid-feedback">Please upload a profile picture.</div>
                                </div>
                            </div>


                            <input type="hidden" id="edit_trainer_id" name="trainer_id">

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-danger" id="deleteTrainerBtn">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function showEditModal(card) {
                const trainerId = card.getAttribute('data-id');
                const code = card.getAttribute('data-code');
                const name = card.getAttribute('data-name');
                const generation = card.getAttribute('data-generation');
                const position = card.getAttribute('data-position');
                const subject = card.getAttribute('data-subject');
                const profile = card.getAttribute('data-profile');

                document.getElementById('edit_code').value = code;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_generation').value = generation;
                document.getElementById('edit_position').value = position;
                document.getElementById('edit_subject').value = subject;
                document.getElementById('edit_trainer_id').value = trainerId;

                $('#editTrainerModal').modal('show');
            }

            function updateFileName() {
                const input = document.getElementById('edit_profile');
                const label = document.getElementById('edit_profile_label');
                const fileName = input.files[0].name;
                label.innerText = fileName;
            }
        </script>
    </div>
@endsection
