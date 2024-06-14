@extends('components.navbar')
<title>Forum</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

@section('content')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
    <div class="modal" id="shuffleForumsModal" tabindex="-1" role="dialog" aria-labelledby="shuffleForumsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shuffleForumsModalLabel">Enter Password to Shuffle Forums</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('shuffleForums') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="header mb-4">
            <div class="row justify-content-end align-items-center">
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn btn-primary mr-3" id="newforumBtn" data-toggle="modal"
                            data-target="#newforumModal">New Forum</button>
                        <a href="#" class="btn btn-primary mr-5" data-toggle="modal"
                            data-target="#shuffleForumsModal">Shuffle</a>
                        <div class="search-container">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Search by Trainee Number...">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @php
            use Illuminate\Support\Str;
        @endphp

        <div class="card-container">
            <h3>Unshuffle Forum Links</h3>
            <div class="row" id="unshuffle-forums">
                @foreach ($forums as $forum)
                    @if ($forum->forum_status == 'unshuffle')
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body text-center animate__animated animate__fadeIn">
                                    <div class="d-flex text-center justify-content-between align-items-center">
                                        <a class="card-text text-center ">{{ $forum->link }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <h3>Forums</h3>
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="statusFilter">Show by Status:</label>
                        <select id="statusFilter" class="form-control">
                            <option value="no" selected>No</option>
                            <option value="all">All</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="row" id="forums">
                    @php
                        $sortedTrainees = $trainee->sortByDesc('totalForum');
                    @endphp

                    @foreach ($sortedTrainees as $t)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body ">
                                    <h5 class="card-title text-center">{{ $t->trainee_number }} - {{ $t->name }} |
                                        Total : {{ $t->totalForum }}</h5>
                                    @foreach ($forums as $forum)
                                        @if ($t->id == $forum->trainee_id)
                                            <div class="forum-link-container mb-2" data-status="{{ $forum->forum_status }}">
                                                <div class="d-flex flex-row align-items-start">
                                                    <form method="POST"
                                                        action="{{ route('updateForumStatus', $forum->id) }}"
                                                        class="d-flex flex-row align-items-start">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="forum_status" value="yes"
                                                                id="forum-{{ $forum->id }}"
                                                                {{ $forum->forum_status == 'yes' ? 'checked' : '' }}
                                                                onchange="this.form.submit()">
                                                            <a href="{{ $forum->link }}"
                                                                class="card-text d-inline">{{ $forum->link }}</a>
                                                        </div>
                                                    </form>
                                                    <div class="ml-auto">
                                                        <form method="POST"
                                                            action="{{ route('deleteForum', $forum->id) }}"
                                                            class="delete-forum-form"
                                                            onsubmit="return confirm('Are you sure you want to delete this forum?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="modal fade" id="newforumModal" tabindex="-1" role="dialog" aria-labelledby="newforumModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newforumModalLabel">Create New Case Solve</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('storeForum') }}" method="POST">
                            @csrf
                            <label for="link">Link</label>
                            <input type="text" id="link" name="link" class="form-control"
                                placeholder="Paste the link here">
                            <button type="submit" class="btn btn-success mt-5">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            const searchInput = document.getElementById('searchInput');
            const forumsContainer = document.getElementById('forums');
            const statusFilter = document.getElementById('statusFilter');
            const forumContainers = document.querySelectorAll('.forum-link-container');

            function filterforums() {
                const searchValue = searchInput.value.trim().toLowerCase();
                const forums = document.querySelectorAll('.card');

                forums.forEach(forum => {
                    const title = forum.querySelector('.card-title').textContent.toLowerCase();
                    if (title.includes(searchValue)) {
                        forum.style.display = '';
                    } else {
                        forum.style.display = 'none';
                    }
                });
            }

            function filterByStatus() {
                const selectedStatus = statusFilter.value;
                forumContainers.forEach(container => {
                    const forumStatus = container.getAttribute('data-status');
                    if (selectedStatus === 'all' || forumStatus === selectedStatus || (selectedStatus === 'no' &&
                            forumStatus !== 'yes')) {
                        container.style.display = 'block';
                    } else {
                        container.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterforums);
            statusFilter.addEventListener('change', filterByStatus);

            // Initial filter setup
            filterByStatus();
        </script>
    @endsection
