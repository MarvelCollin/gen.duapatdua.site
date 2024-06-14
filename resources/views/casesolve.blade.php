@extends('components.navbar')
<title>Case Solving</title>
@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <div class="container mt-4">
        <div class="header mb-4">
            <div class="row justify-content-end align-items-center">
                <div class="col-md-6">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn btn-primary mr-3" id="newCaseSolveBtn" data-toggle="modal"
                            data-target="#newCaseSolveModal">New Case Solve</button>
                        <div class="search-container">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-container">
            <div class="row" id="caseSolves">
                @foreach ($caseSolves->reverse() as $caseSolve)
                    @php
                        $createdTime = \Carbon\Carbon::parse($caseSolve->created_at);
                        $expiredTime = now()->subHours(2);
                        $isActive = $createdTime->greaterThanOrEqualTo($expiredTime);
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div
                                class="card-body {{ $isActive ? 'bg-success text-white' : '' }} animate__animated animate__fadeIn">
                                <h5 class="card-title text-center">{{ $caseSolve->title }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="card-text">Date: {{ $caseSolve->created_at->format('d F Y') }}</p>
                                        <p class="card-text">Subject: {{ $caseSolve->subject }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="card-text mb-0">Session</p>
                                        <p class="card-text font-weight-bold session">{{ $caseSolve->session }}</p>
                                    </div>
                                </div>
                                <div class="d-flex text-center justify-content-between align-items-center mt-3">
                                    <a href="{{ route('casesolve.show', $caseSolve->id) }}" class="btn btn-primary">View
                                        Progress</a>
                                    <button class="btn btn-primary editCaseSolveBtn" data-toggle="modal"
                                        data-target="#editCaseSolveModal_{{ $caseSolve->id }}">Edit Case Solve</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editCaseSolveModal_{{ $caseSolve->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editCaseSolveModalLabel_{{ $caseSolve->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCaseSolveModal">Edit Case Solve</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('casesolve.update', $caseSolve->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Title:</label>
                                                    <input type="text" id="title" name="title" class="form-control"
                                                        value="{{ $caseSolve->title }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="subject">Subject:</label>
                                                    <select id="subject" name="subject" class="form-control"
                                                        value="{{ $caseSolve->subject }}">
                                                        <option value="A&DS">A&DS</option>
                                                        <option value="Java">Java</option>
                                                        <option value="Database">Database</option>
                                                        <option value="Web Design">Web Design</option>
                                                        <option value="Web Programming">Web Programming</option>
                                                        <option value="Networking">Networking</option>
                                                        <option value="Python">Python</option>
                                                        <option value="Computer Vision">Computer Vision</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="session">Session:</label>
                                                    <select id="session" name="session" class="form-control"
                                                        value="{{ $caseSolve->session }}">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subtitles">Subtitles:</label>
                                                    <div id="subtitle-container-edit-{{ $caseSolve->id }}">
                                                        @if ($caseSolveDetails)
                                                            @foreach ($caseSolveDetails as $caseSolveDetail)
                                                                @foreach ($caseSubtitles->unique('subtitle') as $subtitle)
                                                                    @if ($subtitle->case_solve_detail_id == $caseSolveDetail->id && $caseSolveDetail->case_solve_id == $caseSolve->id)
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
                                                    <button type="button" class="btn btn-primary mt-2 add-subtitle-edit"
                                                        data-casesolveid="{{ $caseSolve->id }}">Add New
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

        <div class="modal fade" id="newCaseSolveModal" tabindex="-1" role="dialog"
            aria-labelledby="newCaseSolveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newCaseSolveModalLabel">Create New Case Solve</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('casesolve.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" id="title" name="title" class="form-control"
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
                                            <option value="Computer Vision">Computer Vision</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="session">Session:</label>
                                        <select id="session" name="session" class="form-control" required>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('add-subtitle').addEventListener('click', function() {
                    const newInputGroup = createInputGroup();
                    document.getElementById('subtitle-container').appendChild(newInputGroup);
                });

                document.querySelectorAll('.add-subtitle-edit').forEach(button => {
                    button.addEventListener('click', function() {
                        const caseSolveId = button.dataset.casesolveid;
                        const subtitleContainer = document.getElementById(
                            `subtitle-container-edit-${caseSolveId}`);
                        const newInputGroup = createInputGroup();
                        subtitleContainer.appendChild(newInputGroup);
                    });
                });



                document.querySelectorAll('.remove-subtitle').forEach(button => {
                    button.addEventListener('click', function() {
                        button.closest('.input-group').remove();
                    });
                });

                const searchInput = document.getElementById('searchInput');
                const caseSolvesContainer = document.getElementById('caseSolves');

                function filterCaseSolves() {
                    const searchValue = searchInput.value.trim().toLowerCase();
                    const caseSolves = document.querySelectorAll('.card');

                    caseSolves.forEach(caseSolve => {
                        const title = caseSolve.querySelector('.card-title').textContent.toLowerCase();
                        if (title.includes(searchValue)) {
                            caseSolve.style.display = '';
                        } else {
                            caseSolve.style.display = 'none';
                        }
                    });
                }

                searchInput.addEventListener('input', filterCaseSolves);
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
        </script>
    @endsection
