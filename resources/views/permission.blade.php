    @extends('components.navbar')

    @section('title', 'Permission')

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
        </style>

        <div class="container mt-4">
            <div class="row mb-3">
                <div class="col-md-9">
                    <h3>Permission List</h3>
                </div>
                <div class="col-md-3 text-right">
                    <button class="btn btn-primary" id="newPermissionButton" data-toggle="modal"
                        data-target="#newPermissionModal">New Permission
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @php
                        $lastDate = '';
                    @endphp
                    @forelse ($permissions->reverse() as $permission)
                        @php
                            $currentDate = $permission->created_at->format('d F Y');
                        @endphp

                        @if ($currentDate != $lastDate)
                            @if ($lastDate != '')
                </div>
            </div>
            @endif
            <div class="card mb-3">
                <div class="card-header">
                    <div class="created-at">{{ $currentDate }}</div>
                </div>
                <div class="card-body">
                    @endif

                    <div class="card mb-3 position-relative">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="card-title text-wrap">{{ $permission->trainee_number }}: {{ $permission->reason }}</h5>
                                </div>
                                <form action="{{ route('deletePermission', ['id' => $permission->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                            <div class="d-flex justify-content-end">
                                <p class="card-title text-wrap mb-0">{{ \Carbon\Carbon::parse($permission->created_at)->subHours(14)->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $lastDate = $currentDate;
                    @endphp

                @empty
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">No permissions found.</p>
                        </div>
                    </div>
                    @endforelse

                    @if (!empty($lastDate))
                </div>
            </div>
        </div>
        @endif
        </div>
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
