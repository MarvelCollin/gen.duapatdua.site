@extends('components.navbar')
<title>Trainee List</title>
@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/trainee.css') }}">
    <style>
        .grayscale {
            filter: grayscale(100%);
        }

        .inactive-trainee .card {
            background-color: #f0f0f0;
        }

        .inactive-trainee .card-body {
            background-color: #f0f0f0;
            color: #999;
        }
    </style>
    <div class="container mt-4">
        <div class="header">
            <button class="btn btn-primary" id="newCaseSolveBtn" data-toggle="modal" data-target="#newCaseSolveModal">New
                Trainee</button>
        </div>
        <div class="card-container">
                @php
                    $sortedTrainees = $trainee->sortBy(function ($t) {
                        return intval(str_replace('T0', '', $t->trainee_number));
                    });
                @endphp
            <h2 class="mt-2">Active Trainees</h2>
            <div class="row justify-content-center">
                @foreach ($sortedTrainees as $trainee)
                    @if ($trainee->status == 'active')
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 active-trainee" onclick="showTraineeModal(this)"
                                data-id="{{ $trainee->id }}" data-number="{{ $trainee->trainee_number }}"
                                data-name="{{ $trainee->name }}" data-degree="{{ $trainee->degree }}"
                                data-binusian="{{ $trainee->binusian }}"
                                data-profile="{{ asset('storage/' . $trainee->profile) }}"
                                data-status="{{ $trainee->status }}">
                                <img src="{{ asset('storage/' . $trainee->profile) }}" alt="Profile Image"
                                    class="card-img-top img-fluid" style="width: auto; height: 40vh; border-radius: 5%">
                                <div class="card-body text-center">
                                    <h3 class="card-title">
                                        {{ $trainee->trainee_number }}
                                    </h3>
                                    <h5 class="card-text">{{ $trainee->name }}</h5>
                                    <h5 class="card-text">{{ $trainee->degree }} | {{ $trainee->binusian }}</h5>
                                    <div class="row justify-content-between">
                                        <p style="font-size: 1vw" class="card-text">Forum : {{ $trainee->totalForum }}
                                        </p>
                                        <p style="font-size: 1vw" class="card-text">Acq : {{ $trainee->totalAcq }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <h2>Inactive Trainees</h2>
            <div class="row justify-content-center">
                @foreach ($sortedTrainees as $trainee)
                    @if ($trainee->status == 'unactive')
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 inactive-trainee" onclick="showTraineeModal(this)"
                                data-id="{{ $trainee->id }}" data-number="{{ $trainee->trainee_number }}"
                                data-name="{{ $trainee->name }}" data-degree="{{ $trainee->degree }}"
                                data-binusian="{{ $trainee->binusian }}"
                                data-profile="{{ asset('storage/' . $trainee->profile) }}"
                                data-status="{{ $trainee->status }}">
                                <img src="{{ asset('storage/' . $trainee->profile) }}" alt="Profile Image"
                                    class="card-img-top img-fluid grayscale"
                                    style="width: auto; height: 40vh; border-radius: 5%">
                                <div class="card-body text-center">
                                    <h3 class="card-title text-muted">
                                        {{ $trainee->trainee_number }}
                                    </h3>
                                    <h5 class="card-text text-muted">{{ $trainee->name }}</h5>
                                    <h5 class="card-text text-muted">{{ $trainee->degree }} | {{ $trainee->binusian }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>


        <div class="modal fade" id="newCaseSolveModal" tabindex="-1" role="dialog"
            aria-labelledby="newCaseSolveModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newCaseSolveModalLabel">Create New Trainee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="traineeForm" action="{{ route('trainee.store') }}" method="POST"
                            enctype="multipart/form-data" class="needs-validation" novalidate
                            data-update-url="{{ route('trainee.update', ':id') }}"
                            data-delete-url="{{ route('trainee.destroy', ':id') }}">
                            @csrf
                            <div class="form-group">
                                <label for="trainee_number">Trainee Number (Format T0XX):</label>
                                <input type="text" id="trainee_number" name="trainee_number" class="form-control"
                                    required>
                                <div class="invalid-feedback">Yaelah dah dibilang starts with T0, FOKUS KAKK!!!.</div>
                            </div>

                            <div class="form-group">
                                <label for="name">Long Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                                <div class="invalid-feedback">Minimal isi kak.</div>
                            </div>

                            <div class="form-group">
                                <label for="degree">Degree:</label>
                                <select id="degree" name="degree" class="form-control" required>
                                    <option value="">Select Degree</option>
                                    <option value="CS">Computer Science</option>
                                    <option value="CSMT">Computer Science (Master Track)</option>
                                    <option value="CSMATH">Computer Science (Mathematics)</option>
                                    <option value="DS">Data Science</option>
                                    <option value="GAT">Game Application & Technology</option>
                                    <option value="CSEC">Cyber Security</option>
                                    <option value="IS">Information System</option>
                                    <option value="ISMT">Information System (Master Track)</option>
                                    <option value="BA">Business Analytics</option>
                                </select>
                                <div class="invalid-feedback">Minimal dipilih dulu lah.</div>
                            </div>

                            <div class="form-group">
                                <label for="binusian">Binusian:</label>
                                <select id="binusian" name="binusian" class="form-control" required>
                                    <option value="">Select Binusian</option>
                                    <option value="B24">B24</option>
                                    <option value="B25">B25</option>
                                    <option value="B26">B26</option>
                                    <option value="B27">B27</option>
                                </select>
                                <div class="invalid-feedback">Pilih lahh, jangan kosong.</div>
                            </div>

                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="unactive">Unactive</option>
                                    <option value="active">Active</option>
                                </select>
                                <div class="invalid-feedback">Pilih lahh, jangan kosong.</div>
                            </div>

                            <div class="form-group">
                                <label for="image">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="profile"
                                        required>
                                    <label class="custom-file-label" for="image">Choose file...</label>
                                    <div class="invalid-feedback">Jangan lupa gambar ya.</div>
                                </div>
                            </div>

                            <input type="hidden" id="trainee_id" name="trainee_id">

                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" id="editBtn" class="btn btn-success" style="display:none;">Save
                                Changes</button>
                            <button type="button" id="deleteBtn" class="btn btn-danger"
                                style="display:none;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/trainee.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const traineeCards = document.querySelectorAll('.trainee-card');

                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.trim().toLowerCase();

                    traineeCards.forEach(card => {
                        const traineeName = card.querySelector('.card-title').innerText.toLowerCase();
                        const traineeNumber = card.querySelector('.card-text:nth-child(2)').innerText
                            .toLowerCase();
                        const degree = card.querySelector('.card-text:nth-child(3)').innerText
                            .toLowerCase();
                        const binusian = card.querySelector('.card-text:nth-child(4)').innerText
                            .toLowerCase();

                        const containsQuery = traineeName.includes(query) || traineeNumber.includes(
                            query) || degree.includes(query) || binusian.includes(query);

                        if (containsQuery) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        </script>
    @endsection
