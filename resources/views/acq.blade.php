@extends('components.navbar')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Kenalan</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Select option for sorting -->
        <div class="form-group">
            <label for="sortSelect">Sort By:</label>
            <select class="form-control" id="sortSelect">
                <option value="0">Trainee Number</option>
                <option value="2">Total Acq</option>
            </select>
        </div>

        <input type="text" id="search" class="form-control mb-3" placeholder="Search">

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="trainee-table">
                <thead class="thead-light">
                    <tr>
                        <th>
                            <a href="#" onclick="sortTable('trainee-table')">Trainee Number</a>
                        </th>
                        <th>Name</th>
                        <th>
                            <a href="#" onclick="sortTable('trainee-table')">Total Acq</a>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="trainee-tbody">
                    @foreach($trainees as $trainee)
                        <tr>
                            <td>{{ $trainee->trainee_number }}</td>
                            <td>{{ $trainee->name }}</td>
                            <td>
                                <form id="form-{{ $trainee->id }}" action="{{ route('trainees.editTotalAcq', $trainee->id) }}" method="POST">
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
        document.getElementById('search').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#trainee-tbody tr');

            rows.forEach(row => {
                const traineeNumber = row.cells[0].innerText.toLowerCase();
                const name = row.cells[1].innerText.toLowerCase();
                if (traineeNumber.includes(filter) || name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function changeTotalAcq(id, delta) {
            const input = document.getElementById('totalAcq-' + id);
            let currentValue = parseInt(input.value);
            const newValue = currentValue + delta;
            if (newValue >= 0) {
                input.value = newValue;
            }
        }

        function sortTable(tableId) {
            const table = document.getElementById(tableId);
            const sortSelect = document.getElementById('sortSelect');
            const n = parseInt(sortSelect.value); // Get the selected option value
            let switching = true;
            let dir = "asc";
            let switchcount = 0;

            while (switching) {
                switching = false;
                const rows = table.rows;
                for (let i = 1; i < (rows.length - 1); i++) {
                    let shouldSwitch = false;
                    const x = rows[i].getElementsByTagName("TD")[n];
                    const y = rows[i + 1].getElementsByTagName("TD")[n];
                    let cmpX = x.innerHTML.toLowerCase();
                    let cmpY = y.innerHTML.toLowerCase();

                    if (n === 0) { 
                        cmpX = parseInt(cmpX.substring(1));
                        cmpY = parseInt(cmpY.substring(1));
                    } else if (n === 2) { 
                        cmpX = parseInt(cmpX);
                        cmpY = parseInt(cmpY);
                    }

                    if (dir === "asc") {
                        if (cmpX > cmpY) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if (cmpX < cmpY) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount === 0 && dir === "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
@endsection
