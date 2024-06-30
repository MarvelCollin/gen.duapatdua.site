@extends('components.navbar')
<title>Daily Task Progress</title>
<style>
    .card {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
</style>

@section('content')
    <div class="container text-center">
        <h1 class="mb-4">Daily Task Progress</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="mb-5 row">
            <button type="button" class="btn btn-primary col-md-3 mr-3" data-toggle="modal" data-target="#leaderboardModal">
                View Progress Angkatan
            </button>
                <input type="text" id="searchInput" class="form-control col-md-5" placeholder="Search by trainee number or name">
        </div>
        <div class="row" id="traineeContainer">
            @foreach ($dailyTasks as $task)
                @php
                    $totalTasks = $task->count();
                    $completedTasks = $task->where('status', 'completed')->count();
                    $totalPercentageDone = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
                @endphp
                <div class="col-md-4 mb-4 trainee-card"
                    data-trainee="{{ $task->trainee->trainee_number }} {{ $task->trainee->name }}">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-body" style="cursor: pointer;" onclick="openModal('{{ $task->id }}')">
                            <h5 class="card-title">{{ $task->trainee->trainee_number }} - {{ $task->trainee->name }}</h5>
                            <p>Total Progress: {{ $totalPercentageDone }}%</p>
                            <button type="button" class="btn btn-primary"
                                onclick="openModal('{{ $task->id }}')">Update Progress</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @foreach ($dailyTasks as $task)
            <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1" role="dialog"
                aria-labelledby="taskModalLabel{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="taskModalLabel{{ $task->id }}">
                                {{ $task->trainee->name }}'s Tasks</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('dailytask.update', $task->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="trainee_id" value="{{ $task->trainee->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Task</h5>
                                        @foreach ($task->dailyTasks as $taskDetail)
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-2"
                                                    id="task_{{ $taskDetail->id }}"
                                                    name="tasks[{{ $taskDetail->id }}][task]"
                                                    value="{{ $taskDetail->task }}" disabled>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Status</h5>
                                        @foreach ($dailyTasks as $taskDetail)
                                            <div class="form-group">
                                                <select class="form-control mb-2" id="status_{{ $taskDetail->id }}"
                                                    name="tasks[{{ $taskDetail->id }}][status]">
                                                    <option value="pending"
                                                        {{ $taskDetail->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="completed"
                                                        {{ $taskDetail->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="leaderboardModal" tabindex="-1" role="dialog" aria-labelledby="leaderboardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaderboardModalLabel">Progress Angkatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Ranks</th>
                                        <th>Trainee</th>
                                        <th class="pr-2">Name</th>
                                        @foreach ($dailyTasks as $taskDetail)
                                            <th>{{ $taskDetail->task }}</th>
                                        @endforeach
                                        <th class="pr-2">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">

                                    @foreach ($dailyTasks as $key => $task)
                                        @php
                                            $totalTasks = $task->dailyTasks->count();
                                            $completedTasks = $task->dailyTasks->where('status', 'completed')->count();
                                            $totalPercentageDone =
                                                $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td style="background-color: #ffffff; color: #000000; width: 150px;">
                                                #{{ $index++ }}</td>
                                            <td style="background-color: #ffffff; color: #000000; width: 150px;">
                                                {{ $task->trainee->trainee_number }}</td>
                                            <td style="background-color: #ffffff; color: #000000; width: 250px;">
                                                {{ $task->trainee->name }}</td>
                                            @foreach ($task->dailyTasks as $taskDetail)
                                                @php
                                                    $status = $taskDetail->status;
                                                    $bgColor = $status == 'completed' ? '#00ff00' : '#ff0000';
                                                    $fontColor = $status == 'completed' ? '#000000' : '#ffffff';
                                                @endphp
                                                <td
                                                    style="background-color: {{ $bgColor }}; color: {{ $fontColor }}; text-align: center; font-weight: bold; width: 150px;">
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

    <script>
        function openModal(id) {
            $('#taskModal' + id).modal('show');
        }

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
