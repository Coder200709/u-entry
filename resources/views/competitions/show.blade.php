<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-primary">{{ $competition->name }}</h2>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <button onclick="toggleExportDropdown()" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-700">
                                {{ __('messages.export') }} ▼
                            </button>
                            <div id="exportDropdown" class="absolute bg-white shadow-md mt-2 rounded hidden right-0">
                                <a href="{{ route('competitions.export', ['competition' => $competition->id, 'format' => 'pdf']) }}" target="_blank" 
                                   class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                   {{ __('messages.export_to_pdf') }}
                                </a>
                                <a href="{{ route('competitions.export', ['competition' => $competition->id, 'format' => 'excel']) }}" 
                                   class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                   {{ __('messages.export_to_excel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-muted">📅 Date: <strong>{{ $competition->date }}</strong></p>

                <h3 class="mt-4">🏋️ Registered Athletes</h3>
                @if($registeredAthletes->isEmpty())
                    <p class="text-muted">No athletes registered yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Region</th>
                                    <th>Category</th>
                                    <th>Entry Total</th>
                                    <th>Reserve</th>
                                    @if($isAdmin)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registeredAthletes as $index => $athlete)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($athlete->picture)
                                                    <img src="{{ asset('storage/' . $athlete->picture) }}" 
                                                         alt="{{ $athlete->given_name }} {{ $athlete->family_name }}"
                                                         class="rounded-circle me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $athlete->family_name }} {{ $athlete->given_name }}</strong>
                                                    <div class="text-muted small">
                                                        ADAMS ID: {{ strtoupper($athlete->adams_id) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $athlete->region }}</td>
                                        <td>{{ $athlete->pivot->category }}</td>
                                        <td>{{ $athlete->pivot->entry_total }}</td>
                                        <td>
                                            @if($athlete->pivot->reserve)
                                                <span class="badge bg-warning">Reserve</span>
                                            @else
                                                <span class="badge bg-success">Not Reserve</span>
                                            @endif
                                        </td>
                                        @if($isAdmin)
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-warning btn-sm edit-athlete-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAthleteModal"
                                                            data-athlete-id="{{ $athlete->id }}"
                                                            data-family-name="{{ $athlete->family_name }}"
                                                            data-given-name="{{ $athlete->given_name }}"
                                                            data-category="{{ $athlete->pivot->category }}"
                                                            data-entry-total="{{ $athlete->pivot->entry_total }}"
                                                            data-reserve="{{ $athlete->pivot->reserve }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm delete-athlete-btn"
                                                            data-athlete-id="{{ $athlete->id }}"
                                                            data-competition-id="{{ $competition->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteModal">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(auth()->user()->role === 'regional')
                    <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#registerAthleteModal">
                        ➕ Register Athlete
                    </button>
                @endif
            </div>
        </div>
    </div>
<!-- Edit Athlete Modal -->
<div class="modal fade" id="editAthleteModal" tabindex="-1" aria-labelledby="editAthleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAthleteModalLabel">Edit Athlete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="editAthleteForm" method="POST">
    @csrf
    @method('PUT')

    <input type="hidden" name="athlete_id" id="editAthleteId">

    <div class="mb-3">
        <label class="form-label">Family Name</label>
        <input type="text" class="form-control" name="family_name" id="editFamilyName" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Given Name</label>
        <input type="text" class="form-control" name="given_name" id="editGivenName" required>
    </div>

    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>


            </div>
        </div>
    </div>
</div>
<!-- Confirm Delete Modal -->
<!-- Confirm Delete Modal -->
<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this athlete from the competition?
            </div>
            <div class="modal-footer">
                <form id="deleteAthleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>



    {{-- Include the Edit Modal --}}
    @include('competitions.partials.edit-athlete-modal')

    {{-- Include the Register Modal --}}
    @include('competitions.partials.register-modal')
    
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-athlete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let athleteId = this.getAttribute("data-athlete-id");
            let familyName = this.getAttribute("data-family-name");
            let givenName = this.getAttribute("data-given-name");

            document.getElementById("editAthleteId").value = athleteId;
            document.getElementById("editFamilyName").value = familyName;
            document.getElementById("editGivenName").value = givenName;

            document.getElementById("editAthleteForm").action = `/athletes/${athleteId}/update-name`;
          });
          
    });
    document.getElementById("editAthleteForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        let actionUrl = this.action;

        fetch(actionUrl, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Athlete name updated successfully!");
                location.reload();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
document.addEventListener("DOMContentLoaded", function () {
    let deleteAthleteForm = document.getElementById("deleteAthleteForm");

    document.querySelectorAll(".delete-athlete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let athleteId = this.getAttribute("data-athlete-id");
            let competitionId = this.getAttribute("data-competition-id");

            // This sets the correct route dynamically
            deleteAthleteForm.action = `/competitions/${competitionId}/athletes/${athleteId}/delete`;
        });
    });
});

function toggleExportDropdown() {
    const dropdown = document.getElementById('exportDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('exportDropdown');
    const button = document.querySelector('button[onclick="toggleExportDropdown()"]');
    
    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

</script>





</x-app-layout>
