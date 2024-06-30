@extends('components.navbar')
<title>Daily Task Progress</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .card {
        transition: box-shadow 0.3s ease-in-out;
        margin-bottom: 20px;
        border-radius: 10px;
    }

    .card:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .modal-header, .modal-footer {
        background-color: #f8f9fa;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn-remove {
        color: #dc3545;
        cursor: pointer;
    }

    .btn-remove:hover {
        color: #c82333;
    }

    .input-group-append {
        display: flex;
        align-items: center;
    }

    .alert {
        margin-bottom: 20px;
    }

    .task-list {
        list-style: none;
        padding-left: 0;
    }

    .task-list li {
        background: #f8f9fa;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
    }

    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    .modal-dialog-centered .modal-content {
        width: 100%;
    }

    .modal-dialog-password {
        max-width: 400px;
    }

    .form-check-input {
        transform: scale(1.5);
        margin-right: 10px;
    }

    .search-bar {
        margin-bottom: 20px;
    }
</style>

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Daily Task Progress</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manageTaskModal">Manage Tasks</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewAllProgressModal">View All Progress</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Reset All Tasks</button>
        </div>

        <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by trainee number or name">

        <div class="row" id="traineeContainer">
            @foreach ($dailyTasks as $traineeId => $tasks)
                @php
                    $trainee = $tasks->first()->trainee;
                    $totalTasks = $tasks->count();
                    $completedTasks = $tasks->where('status', 'completed')->count();
                    $totalPercentageDone = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
                @endphp
                <div class="col-md-4 trainee-card" data-trainee="{{ $trainee->trainee_number }} {{ $trainee->name }}">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $trainee->trainee_number }} - {{ $trainee->name }}</h5>
                            <p>Total Progress: <strong>{{ $totalPercentageDone }}%</strong></p>
                            {{-- <ul class="task-list">
                                @foreach ($tasks as $task)
                                    <li>{{ $task->task }} - <strong>{{ ucfirst($task->status) }}</strong></li>
                                @endforeach
                            </ul> --}}
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateTaskModal{{ $trainee->id }}">
                                Update Progress
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Update Task Modal -->
                <div class="modal fade" id="updateTaskModal{{ $trainee->id }}" tabindex="-1" aria-labelledby="updateTaskModalLabel{{ $trainee->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateTaskModalLabel{{ $trainee->id }}">Update Tasks for {{ $trainee->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('updateTraineeTasks', $tasks->first()->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div id="tasksContainer{{ $trainee->id }}">
                                        @foreach ($tasks as $task)
                                            <div class="form-group task-group">
                                                <div class="input-group mb-2">
                                                    <input type="hidden" name="tasks[{{ $loop->index }}][id]" value="{{ $task->id }}">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="tasks[{{ $loop->index }}][status]" {{ $task->status == 'completed' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tasks[{{ $loop->index }}][status]">{{ $task->task }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Progress Modal -->
        <div class="modal fade" id="viewAllProgressModal" tabindex="-1" aria-labelledby="viewAllProgressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewAllProgressModalLabel">All Trainees Progress</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="text-center">
                                    <tr>
                                        <th>Ranks</th>
                                        <th>Trainee</th>
                                        <th class="pr-2">Name</th>
                                        @php
                                            $allTasks = $dailyTasks->flatten()->unique('task')->pluck('task');
                                        @endphp
                                        @foreach ($allTasks as $task)
                                            <th>{{ $task }}</th>
                                        @endforeach
                                        <th class="pr-2">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php
                                        $sortedTrainees = $dailyTasks->sortByDesc(function ($tasks) {
                                            return $tasks->where('status', 'completed')->count() / $tasks->count();
                                        });

                                        $index = 1;
                                    @endphp
                                    @foreach ($sortedTrainees as $traineeId => $tasks)
                                        @php
                                            $trainee = $tasks->first()->trainee;
                                            $totalTasks = $tasks->count();
                                            $completedTasks = $tasks->where('status', 'completed')->count();
                                            $totalPercentageDone = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td>#{{ $index++ }}</td>
                                            <td>{{ $trainee->trainee_number }}</td>
                                            <td>{{ $trainee->name }}</td>
                                            @foreach ($allTasks as $task)
                                                @php
                                                    $taskStatus = $tasks->where('task', $task)->first();
                                                    $status = $taskStatus ? $taskStatus->status : 'not started';
                                                    $bgColor = '';
                                                    $fontColor = '';
                                                    switch ($status) {
                                                        case 'completed':
                                                            $bgColor = '#00ff00';
                                                            $fontColor = '#000000';
                                                            break;
                                                        case 'pending':
                                                            $bgColor = '#ffff00';
                                                            $fontColor = '#000000';
                                                            break;
                                                        case 'not started':
                                                        default:
                                                            $bgColor = '#ff0000';
                                                            $fontColor = '#ffffff';
                                                            break;
                                                    }
                                                @endphp
                                                <td style="background-color: {{ $bgColor }}; color: {{ $fontColor }}; text-align: center; font-weight: bold;">
                                                    {{ ucfirst($status) }}
                                                </td>
                                            @endforeach
                                            <td>{{ $totalPercentageDone }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Task Modal -->
        <div class="modal fade" id="manageTaskModal" tabindex="-1" aria-labelledby="manageTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manageTaskModalLabel">Manage Tasks for Active Trainees</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('createTasks') }}" method="POST" onsubmit="return validateTasks()">
                            @csrf
                            <div id="tasksContainer">
                                @php
                                    $uniqueTasks = $dailyTasks->flatten()->unique('task');
                                @endphp
                                <label for="task">Task</label>
                                @foreach ($uniqueTasks as $task)
                                    <div class="form-group task-group">
                                        <div class="input-group mb-2">
                                            <input type="hidden" name="tasks[{{ $loop->index }}][id]" value="{{ $task->id }}">
                                            <input type="text" class="form-control" name="tasks[{{ $loop->index }}][task]" value="{{ $task->task }}" required>
                                            <div class="input-group-append">
                                                <span class="btn-remove btn btn-danger" onclick="removeTask(this, '{{ $task->id }}')">Remove</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="addTask()">Add More Task</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Password Modal -->   
        <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-password">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetPasswordModalLabel">Confirm Reset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="resetPasswordForm">
                            @csrf
                            <div class="mb-3">
                                <label for="resetPassword" class="form-label">Enter Password to Confirm</label>
                                <input type="password" class="form-control" id="resetPassword" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Confirm Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function addTask() {
                const tasksContainer = document.getElementById('tasksContainer');
                const index = tasksContainer.querySelectorAll('.form-group').length;
                const newTaskGroup = document.createElement('div');
                newTaskGroup.classList.add('form-group', 'task-group');
                newTaskGroup.innerHTML = `
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="tasks[${index}][task]" required>
                        <div class="input-group-append">
                            <span class="btn-remove" onclick="removeTask(this)">Remove</span>
                        </div>
                    </div>
                `;
                tasksContainer.appendChild(newTaskGroup);
            }

            function removeTask(button, taskId = null) {
                if (taskId) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'remove_tasks[]';
                    hiddenInput.value = taskId;
                    button.closest('form').appendChild(hiddenInput);
                }
                button.closest('.task-group').remove();
            }

            function validateTasks() {
                const tasks = document.querySelectorAll('input[name^="tasks["][name$="[task]"]');
                for (let task of tasks) {
                    if (task.value.trim() === "") {
                        alert("Task cannot be empty");
                        return false;
                    }
                }
                return true;
            }

            function resetAllTasks() {
                const password = document.getElementById('resetPassword').value;
                if (password.trim() === "") {
                    alert("Password is required");
                    return;
                }

                fetch('{{ route('resetTasks') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ password: password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Invalid password or an error occurred while resetting tasks.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred while resetting tasks.");
                });
            }

            document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
                event.preventDefault();
                resetAllTasks();
            });

            document.getElementById('searchInput').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let cards = document.querySelectorAll('.trainee-card');

                cards.forEach(function(card) {
                    let trainee = card.getAttribute('data-trainee').toLowerCase();
                    if (trainee.includes(filter)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        </script>
    </div>
@endsection
