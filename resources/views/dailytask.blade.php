@extends('components.navbar')
<title>Daily Task Progress</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .card {
        transition: box-shadow 0.3s ease-in-out;
        margin-bottom: 20px;
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

        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#manageTaskModal">Manage Tasks</button>

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
                        <div class="card-body">
                            <h5 class="card-title">{{ $trainee->trainee_number }} - {{ $trainee->name }}</h5>
                            <p>Total Progress: {{ $totalPercentageDone }}%</p>
                            <ul>
                                @foreach ($tasks as $task)
                                    <li>{{ $task->task }} - {{ ucfirst($task->status) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

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
                                @foreach ($dailyTasks->flatten() as $task)
                                    <div class="form-group task-group">
                                        <label for="task">Task</label>
                                        <div class="input-group mb-2">
                                            <input type="hidden" name="tasks[{{ $loop->index }}][id]" value="{{ $task->id }}">
                                            <input type="text" class="form-control" name="tasks[{{ $loop->index }}][task]" value="{{ $task->task }}" required>
                                            <div class="input-group-append">
                                                <span class="btn-remove" onclick="removeTask(this, '{{ $task->id }}')">Remove</span>
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

        <script>
            function addTask() {
                const tasksContainer = document.getElementById('tasksContainer');
                const index = tasksContainer.querySelectorAll('.form-group').length;
                const newTaskGroup = document.createElement('div');
                newTaskGroup.classList.add('form-group', 'task-group');
                newTaskGroup.innerHTML = `
                    <label for="task">Task</label>
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
        </script>
    </div>
@endsection
