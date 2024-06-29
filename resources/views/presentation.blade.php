@extends('components.navbar')

@section('title', 'Presentations')

@section('content')
    <style>
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-size: 1.25rem;
            padding: 0.75rem;
        }

        .card-body {
            padding: 1rem;
        }

        .card-body .card-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .card-body .card-text {
            color: #666;
        }

        .btn-delete {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .created-at {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .status-passed {
            color: green;
        }

        .status-not-passed {
            color: red;
        }

        .comments-list {
            margin-top: 10px;
            padding-left: 20px;
        }
    </style>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-9">
                <h3>Presentations List</h3>
            </div>
            <div class="col-md-3 text-right">
                <button class="btn btn-primary" id="newPresentationButton" data-toggle="modal"
                        data-target="#newPresentationModal">New Presentation
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @forelse ($presentations->reverse() as $presentation)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="created-at">{{ $presentation->subject }} | {{ $presentation->material }}</div>
                        </div>
                        <div class="card-body">
                            <div class="card mb-3 position-relative">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $presentation->trainee->trainee_number }} -
                                        {{ $presentation->trainee->name }}

                                        @if ($presentation->status === 'Passed')
                                            <span class="status-passed">Passed</span>
                                        @else
                                            <span class="status-not-passed">Not Passed</span>
                                        @endif

                                    </h5>
                                    <p class="card-text">
                                        <strong>Comments:</strong><br>
                                        <ul class="comments-list">
                                            @foreach (explode("\n", $presentation->comments) as $comment)
                                                @if (!empty(trim($comment)))
                                                    <li>{{ trim($comment) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </p>
                                    <form action="{{ route('deletePresentation', ['id' => $presentation->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">No presentations found.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade" id="newPresentationModal" tabindex="-1" role="dialog" aria-labelledby="newPresentationLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPresentationLabel">New Presentation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('createPresentation') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="trainee">Trainee Number</label>
                            <input type="text" class="form-control" id="trainee" placeholder="Trainee Number"
                                name="trainee">
                        </div>
                        <div class="form-group">
                            <label>Status</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="status_passed" name="status"
                                    value="Passed">
                                <label class="form-check-label" for="status_passed">Passed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="status_not_passed" name="status"
                                    value="Not Passed">
                                <label class="form-check-label" for="status_not_passed">Not Passed</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject" placeholder="Subject" name="subject">
                        </div>
                        <div class="form-group">
                            <label for="material">Material</label>
                            <textarea class="form-control" id="material" placeholder="Material"
                                name="material"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <textarea class="form-control" id="comments" placeholder="Comments"
                                name="comments"></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
