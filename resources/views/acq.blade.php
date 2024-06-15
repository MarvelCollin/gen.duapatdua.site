@extends('components.navbar')
<title>Acquaintance</title>
@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Kenalan</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-group">
            <form id="sortForm" action="{{ route('showAcq') }}" method="GET">
                <label for="sortSelect">Sort By:</label>
                <select class="form-control" id="sortSelect" name="sort_by" onchange="document.getElementById('sortForm').submit();">
                    <option value="trainee_number" {{ request('sort_by') == 'trainee_number' ? 'selected' : '' }}>Trainee Number</option>
                    <option value="totalAcq" {{ request('sort_by') == 'totalAcq' ? 'selected' : '' }}>Total Acq</option>
                </select>
                <select class="form-control mt-2" id="sortDirection" name="sort_direction" onchange="document.getElementById('sortForm').submit();">
                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending (Kecil -> Besar)</option>
                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Descending (Besar -> Kecil)</option>
                </select>
                <input type="text" id="search" name="search" class="form-control mt-3" placeholder="Search" value="{{ request('search') }}">
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="trainee-table">
                <thead class="thead-light">
                    <tr>
                        <th class="trainee_number">Trainee Number</th>
                        <th>Name</th>
                        <th class="totalAcq">Total Acq</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="trainee-tbody">
                    @foreach($trainees as $trainee)
                        <tr>
                            <td class="trainee_number">{{ $trainee->trainee_number }}</td>
                            <td>{{ $trainee->name }}</td>
                            <td class="totalAcq">
                                <form id="form-{{ $trainee->id }}" action="{{ route('editTotalAcq', $trainee->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-outline-secondary" onclick="changeTotalAcq({{ $trainee->id }}, -1)">-</button>
                                        </div>
                                        <input type="number" name="totalAcq" id="totalAcq-{{ $trainee->id }}" value="{{ $trainee->totalAcq }}" class="form-control text-center" min="0" readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="changeTotalAcq({{ $trainee->id }}, 1)">+</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <button type="submit" form="form-{{ $trainee->id }}" class="btn btn-primary">Update</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            searchTable();
        });

        function searchTable() {
            const input = document.getElementById('search');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('trainee-table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.rows);

            rows.forEach(row => {
                const traineeNumber = row.querySelector('.trainee_number').innerText.toLowerCase();
                const name = row.cells[1].innerText.toLowerCase();
                if (traineeNumber.includes(filter) || name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function changeTotalAcq(id, delta) {
            const input = document.getElementById('totalAcq-' + id);
            let newValue = parseInt(input.value) + delta;
            if (newValue < 0) newValue = 0;
            input.value = newValue;
        }
    </script>
@endsection
