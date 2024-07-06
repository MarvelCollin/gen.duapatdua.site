@extends('components.navbar')
<title>Catering</title>

<style>
    .custom-checkbox {
        width: 1.5em;
        height: 1.5em;
    }
    .form-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
    }
    .btn-group {
        margin-bottom: 20px;
    }
    .password-input {
        margin-bottom: 10px;
    }
</style>

@section('content')
    <div class="container form-container mt-4">
        <h2 class="mb-4">Catering Bookings</h2>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="btn-group mb-3">
            <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#resetBookCateringModal">
                Recap Catering (Daily)
            </button>

            <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#resetTotalCateringModal">
                Reset Catering (Weekly)
            </button>

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#traineeDetailsModal">
                View Catering Details
            </button>
        </div>

        <form method="POST" action="{{ route('updateCatering') }}">
            @csrf
            @php
                $sortedTrainees = $trainees->sortBy('trainee_number');
            @endphp
            <div class="container">
                <div class="row">
                    @foreach ($sortedTrainees as $trainee)
                        <div class="col-md-4">
                            <div class="form-check mb-3">
                                <input type="hidden" name="trainee_number[]" value="{{ $trainee->trainee_number }}">
                                <input type="checkbox" class="form-check-input custom-checkbox"
                                    id="trainee{{ $trainee->trainee_number }}" name="bookCatering[{{ $trainee->trainee_number }}]"
                                    value="accept" {{ $trainee->bookCatering === 'accept' ? 'checked' : '' }}>
                                <label class="ml-4 form-check-label" for="trainee{{ $trainee->trainee_number }}">
                                    <h4>{{ $trainee->trainee_number }} - {{ $trainee->name }}</h4>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Catering</button>
        </form>
    </div>

    <div class="modal fade" id="resetBookCateringModal" tabindex="-1" aria-labelledby="resetBookCateringModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('resetBookCatering') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetBookCateringModalLabel">Recap Catering (Daily)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="password" class="form-control" name="reset_password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resetTotalCateringModal" tabindex="-1" aria-labelledby="resetTotalCateringModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('resetTotalCatering') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetTotalCateringModalLabel">Reset Total Catering (Weekly)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="password" class="form-control" name="reset_password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="traineeDetailsModal" tabindex="-1" aria-labelledby="traineeDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="traineeDetailsModalLabel">Catering Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Trainee Number</th>
                                <th>Name</th>
                                <th>Total Catering</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainees as $trainee)
                                <tr>
                                    <td>{{ $trainee->trainee_number }}</td>
                                    <td>{{ $trainee->name }}</td>
                                    <td>{{ $trainee->totalCatering }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
