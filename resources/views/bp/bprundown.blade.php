@extends('components.navbar')
@section('content')
    <div class="container">
        <h2 class="mb-4">Rundown Management</h2>

        <!-- Button to trigger modal for creating new Rundown -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createRundownModal">
            Add New Subject
        </button>
        <div class="form-group">
            <label for="filter">Filter:</label>
            <select class="form-control" id="filter">
                <option value="all">All</option>
                @foreach ($rundowns->unique('subject') as $rundown)
                    <option value="{{ $rundown->subject }}">{{ $rundown->subject }}</option>
                @endforeach
            </select>
        </div>

        @php
            $printedSubjects = [];
        @endphp

        @foreach ($rundowns as $rundown)
            @if (!in_array($rundown->subject, $printedSubjects))
                <div class="card mt-4 rundown-card" data-subject="{{ $rundown->subject }}">
                    <div class="card-header bg-primary text-white">
                        <h1 class="text-center">{{ $rundown->subject }}</h1>
                    </div>
                    <ul class="list-group list-group-flush">
                        @php $printedSubjects[] = $rundown->subject; @endphp
                    </ul>
                </div>
            @endif

            <div class="card rundown-card" data-subject="{{ $rundown->subject }}">
                <div class="card-body">
                    <h3 class="card-title text-center">DAY {{ $rundown->day }}</h3>
                    <button type="button" class="btn btn-warning btn-sm edit-subject mb-2" data-toggle="modal"
                    data-target="#editSubjectModal_{{ $rundown->id }}" data-subject="{{ $rundown->subject }}">
                    Edit All Activities
                </button>
                
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Start</th>
                                <th>End</th>
                                <th>Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rundownDetails->where('rundown_id', $rundown->id) as $index => $detail)
                                <tr>
                                    <td>{{ $detail->start }}</td>
                                    <td>{{ $detail->end }}</td>
                                    <td>{{ $detail->activity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade"  id="editSubjectModal_{{ $rundown->id }}"tabindex="-1" role="dialog"
                aria-labelledby="createRundownModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('rundowns.update', $rundown->id ) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="createRundownModalLabel">Add New Subject</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="subject">Subject</label>
                                        <select class="form-control" id="subject" name="subject" required>
                                            <option value="A&DS">A&DS</option>
                                            <option value="Database">Database</option>
                                            <option value="Java">Java</option>
                                            <option value="Web Design">Web Design</option>
                                            <option value="Web Programming">Web Programming</option>
                                            <option value="Networking">Networking</option>
                                            <option value="Python">Python</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="day">Day</label>
                                        <input type="text" class="form-control" id="day" name="day"
                                            value="{{ $rundown->day }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="edit-activity-container">
                                        @foreach ($rundownDetails->where('rundown_id', $rundown->id) as $index => $detail)
                                            <div class="activity-row row mt-2">
                                                <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="edit_start[]"
                                                        placeholder="Start" value="{{ $detail->start }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="edit_end[]"
                                                        placeholder="End" value="{{ $detail->end }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="edit_activity[]"
                                                        placeholder="Activity" value="{{ $detail->activity }}" required>
                                                </div>
                                                <div class="col-md-1 ml-2 d-flex align-items-center justify-content-center">
                                                    <button type="button" class="btn btn-danger remove-activity mr-3">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-primary" id="addEditActivity">Add More
                                    Activity</button>



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="modal fade" id="createRundownModal" tabindex="-1" role="dialog"
        aria-labelledby="createRundownModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('rundowns.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRundownModalLabel">Add New Subject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col">
                                <label for="subject">Subject</label>
                                <select class="form-control" id="subject" name="subject" required>
                                    <option value="A&DS">A&DS</option>
                                    <option value="Database">Database</option>
                                    <option value="Java">Java</option>
                                    <option value="Web Design">Web Design</option>
                                    <option value="Web Programming">Web Programming</option>
                                    <option value="Networking">Networking</option>
                                    <option value="Python">Python</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="day">Day</label>
                                <input type="text" class="form-control" id="day" name="day" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="activity-container">
                                <div class="activity-row row mt-2">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="edit_start[]"
                                            placeholder="Start" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="edit_end[]" placeholder="End"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="edit_activity[]"
                                            placeholder="Activity" required>
                                    </div>
                                    <div class="col-md-1 ml-2 d-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-danger remove-activity mr-3">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="addActivity">Add More Activity</button>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#addActivity").click(function() {
                var newRow = `
                <div class="activity-row row mt-2">
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="start[]" placeholder="Start" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="end[]" placeholder="End" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="activity[]" placeholder="Activity" required>
                    </div>
                    <div class="col-md-1 ml-2 d-flex align-items-center justify-content-center ">
                        <button type="button" class="btn btn-danger remove-activity mr-3">Remove</button>
                    </div>
                </div>
                `;
                $(".activity-container").append(newRow);
            });

            $(document).on("click", ".remove-activity", function() {
                $(this).closest(".activity-row").remove();
            });
        });

        $("#addEditActivity").click(function() {
            var newRow = `
            <div class="activity-row row mt-2">
                <div class="col-md-2">
                    <input type="text" class="form-control" name="edit_start[]" placeholder="Start" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="edit_end[]" placeholder="End" required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="edit_activity[]" placeholder="Activity" required>
                </div>
                <div class="col-md-1 ml-2 d-flex align-items-center justify-content-center ">
                    <button type="button" class="btn btn-danger remove-activity mr-3">Remove</button>
                </div>
            </div>
        `;
            $(".edit-activity-container").append(newRow);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('filter');
            filterSelect.addEventListener('change', function() {
                const value = this.value;
                const rundownCards = document.querySelectorAll('.rundown-card');
                rundownCards.forEach(function(card) {
                    const subject = card.getAttribute('data-subject');
                    if (value === 'all' || subject === value) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
