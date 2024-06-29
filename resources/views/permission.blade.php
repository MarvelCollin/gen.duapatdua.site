@extends('components.navbar')

@section('title', 'Permission')

@section('content')
    <style>
        .grayscale {
            filter: grayscale(100%);
        }

        .inactive-trainee .card {
            background-color: #f0f0f0;
        }

        img {
            object-fit: cover;
        }

        .inactive-trainee .card-body {
            background-color: #f0f0f0;
            color: #999;
        }
    </style>

    <div class="container mt-4">
        <div class="header row mb-3">
            <div class="col-md-9">
                <h3>Permission List</h3>
            </div>
            <div class="col-md-3 text-right">
                <button class="btn btn-primary" id="newPermissionButton" data-toggle="modal"
                        data-target="#newPermissionModal">New Permission
                </button>
            </div>
        </div>

        <div class="card-container row">
            @if ($permissions)
                @foreach ($permissions as $permission)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="card-title">{{ $permission->trainee_number }}</h5>
                                    <p class="card-text">{{ $permission->reason }}</p>
                                </div>
                                <form action="{{ route('deletePermission', ['id' => $permission->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <p>No permissions found.</p>
                </div>
            @endif
        </div>

        <div class="modal fade" id="newPermissionModal" tabindex="-1" role="dialog" aria-labelledby="newPermissionLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newPermissionLabel">New Permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('createPermission') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="traineeNumber">Trainee</label>
                                <input type="text" class="form-control" id="traineeNumber" placeholder="Trainee Number"
                                       name="trainee_number">
                            </div>
                            <div class="form-group">
                                <label for="permissionReason">Reason</label>
                                <input type="text" class="form-control" id="permissionReason" placeholder="Reason"
                                       name="reason">
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
