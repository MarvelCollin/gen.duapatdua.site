@extends('components.navbar')
<title>Catering</title>
@section('content')
    <form method="POST" action="{{ route('updateCatering') }}">
        @csrf
        @foreach ($trainees as $trainee)
            <div>
                <input type="hidden" name="trainee_number[]" value="{{ $trainee->trainee_number }}">
                <label for="trainee{{ $trainee->trainee_number }}">{{ $trainee->name }}</label>
                <input type="checkbox" id="trainee{{ $trainee->trainee_number }}" name="bookCatering[]" value="accept"
                       {{ $trainee->bookCatering === 'accept' ? 'checked' : '' }}>
                <input type="hidden" name="bookCatering[]" value="decline">
            </div>
        @endforeach
        <button type="submit">Update Catering</button>
    </form>
@endsection
