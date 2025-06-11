<form action="{{ route('competitions.register', $competition->id) }}" method="POST">
    @csrf
    <label>Select Athlete:</label>
    <select name="athlete_id" required>
        @foreach($athletes as $athlete)
            <option value="{{ $athlete->id }}">{{ $athlete->family_name }} {{ $athlete->given_name }}</option>
        @endforeach
    </select>
    <button type="submit">Register</button>
</form>
