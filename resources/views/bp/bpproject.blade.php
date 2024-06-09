@extends('components.navbar')
<title>BP Projects</title>
@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <div class="container mt-4">
        <div class="header mb-4">
            <div class="row justify-content-end align-items-center">
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn btn-primary mr-3" id="newBpprojectBtn" data-toggle="modal"
                            data-target="#newBpprojectModal">New BP Project</button>
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
                                        <p class="card-text">Name: {{ $bpproject->bptitle }}</p>
                                        <p class="card-text">Date: {{ $bpproject->created_at->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="d-flex text-center justify-content-between align-items-center mt-3">
                                    <a href="{{ route('bpprojects.show', $bpproject->id) }}" class="btn btn-primary">View
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
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subtitles">Subtitles:</label>
                                                    <div id="subtitle-container-edit-{{ $bpproject->id }}">
                                                        @if ($bpprojectDetails)
                                                            @foreach ($bpprojectDetails as $bpprojectDetail)
                                                                @if ($bpprojectDetail->bpproject_id == $bpproject->id)
                                                                <div class="input-group mb-2">
                                                                    <input type="text" name="subtitles[]"
                                                                    class="form-control"
                                                                    value="{{ $bpprojectDetail->title }}">
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                        class="btn btn-danger remove-subtitle">Remove</button>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                    </div>
                                                    <button type="button" class="btn btn-primary mt-2 add-subtitle-edit"
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
                        const bpprojectId = button.dataset.bpprojectid;
                        const subtitleContainer = document.getElementById(
                            `subtitle-container-edit-${bpprojectId}`);
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
                const bpprojectsContainer = document.getElementById('bpprojects');

                function filterBpprojects() {
                    const searchValue = searchInput.value.trim().toLowerCase();
                    const bpprojects = document.querySelectorAll('.card');

                    bpprojects.forEach(bpproject => {
                        const title = bpproject.querySelector('.card-title').textContent.toLowerCase();
                        if (title.includes(searchValue)) {
                            bpproject.style.display = '';
                        } else {
                            bpproject.style.display = 'none';
                        }
                    });
                }

                searchInput.addEventListener('input', filterBpprojects);
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
