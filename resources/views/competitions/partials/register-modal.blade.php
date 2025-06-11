<!-- Register Athlete Modal -->
<div class="modal fade" id="registerAthleteModal" tabindex="-1" aria-labelledby="registerAthleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerAthleteModalLabel">Register Athlete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('competitions.register', $competition->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="athlete" class="form-label">Select Athlete:</label>
                        <select name="athlete_id" id="athlete" class="form-select" required>
                            <option disabled selected>Choose an athlete...</option>
                            @if(isset($availableAthletes))
    @foreach($availableAthletes as $athlete)
        <option value="{{ $athlete->id }}">
            {{ $athlete->family_name }} {{ $athlete->given_name }} ({{ $athlete->region }})
        </option>
    @endforeach
@else
    <option disabled>No available athletes</option>
@endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
