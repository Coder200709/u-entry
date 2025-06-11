<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Available Competitions') }}
            </h2>
            @if(auth()->user()->roles->contains('name', 'admin'))
                <button id="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    + Create Competition
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow-md">
                <thead class="bg-gray-300">
                    <tr>
                        <th class="py-2 px-4 border">Competition Name</th>
                        <th class="py-2 px-4 border">Date</th>
                        <th class="py-2 px-4 border">Location</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($competitions as $competition)
                        <tr class="border">
                            <td class="py-2 px-4 border">{{ $competition->name }}</td>
                            <td class="py-2 px-4 border">{{ $competition->date }}</td>
                            <td class="py-2 px-4 border">{{ $competition->location }}</td>
                            <td class="py-2 px-4 border flex gap-2">
                                @if(!auth()->user()->roles->contains('name', 'admin'))
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded register-btn"
                                            data-id="{{ $competition->id }}">
                                        Register
                                    </button>
                                @endif
                                <a href="{{ route('competitions.show', $competition->id) }}" 
                                   class="bg-green-500 text-white px-3 py-1 rounded">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Competition Modal --}}
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" id="createModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-center">Create New Competition</h3>
            <form id="createCompetitionForm" action="{{ route('competitions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="block text-gray-700 font-bold mt-3">Name:</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>

                <label class="block text-gray-700 font-bold mt-3">Date:</label>
                <input type="date" name="date" class="w-full p-2 border rounded" required>

                <label class="block text-gray-700 font-bold mt-3">Location:</label>
                <input type="text" name="location" class="w-full p-2 border rounded" required>

                <label class="block text-gray-700 font-bold mt-3">Competition Image:</label>
                <input type="file" name="image" class="w-full p-2 border rounded" accept="image/*">

                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 w-full rounded hover:bg-blue-700">
                    Create
                </button>
                <button type="button" id="closeCreateModal" class="mt-2 bg-gray-500 text-white px-4 py-2 w-full rounded">
                    Cancel
                </button>
            </form>
        </div>
    </div>

    {{-- Register Modal --}}
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" id="registerModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white overflow-auto max-h-[80vh]">
            <h3 class="text-lg font-bold text-center">Register for Competition:</h3>
            <form id="registerForm" method="POST">
                @csrf
                <input type="hidden" name="competition_id" id="competition_id">

                <label class="block text-gray-700 font-bold mt-3">Select Athlete:</label>
                <select name="athlete" id="athlete" class="w-full p-2 border rounded" required>
                    <option value="">Select an athlete</option>
                    @foreach($athletes as $athlete)
                        <option value="{{ $athlete->id }}"
                            data-name="{{ $athlete->given_name }} {{ $athlete->family_name }}"
                            data-dob="{{ $athlete->date_of_birth }}"
                            data-adams-id="{{ $athlete->adams_id }}"
                            data-image="{{ $athlete->picture ? asset('storage/' . $athlete->picture) : '' }}"
                            data-idcard="{{ $athlete->id_card_picture ? asset('storage/' . $athlete->id_card_picture) : '' }}"
                            data-certificate="{{ $athlete->certificate ? asset('storage/' . $athlete->certificate) : '' }}"
                            @if(isset($registeredAthletes) && in_array($athlete->id, $registeredAthletes))
                                disabled
                                data-registered="true"
                            @endif>
                            {{ $athlete->given_name }} {{ $athlete->family_name }}
                            @if(isset($registeredAthletes) && in_array($athlete->id, $registeredAthletes))
                                (Already registered for this competition)
                            @endif
                        </option>
                    @endforeach
                </select>

                <div class="mt-2 text-sm text-gray-600">
                    <p>Note: Athletes already registered for this competition are disabled.</p>
                </div>

                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 w-full rounded hover:bg-blue-700">
                    Continue
                </button>
                <button type="button" id="closeRegisterModal" class="mt-2 bg-gray-500 text-white px-4 py-2 w-full rounded">
                    Cancel
                </button>
            </form>
        </div>
    </div>

    {{-- Confirm Register Modal --}}
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" id="confirmRegisterModal">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white overflow-auto max-h-[90vh]">
            <h3 class="text-lg font-bold text-center mb-4">Confirm Registration</h3>

            <form id="confirmRegisterForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="competition_id" id="confirm_competition_id">
                <input type="hidden" name="athlete_id" id="confirm_athlete_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Athlete Information -->
                    <div class="space-y-4">
                        <div class="text-center">
                            <img id="athleteImage" class="mx-auto rounded-full w-32 h-32 object-cover" src="" alt="Athlete Image">
                            <p class="font-bold text-xl mt-2" id="athleteName"></p>
                            <p class="text-gray-600" id="athleteDob"></p>
                            <p class="text-sm text-gray-500" id="athleteAdamsId"></p>
                        </div>

                        <!-- Category Dropdown -->
                        <div>
                            <label class="block text-gray-700 font-bold">Select Category:</label>
                            <select name="category" id="category" class="w-full p-2 border rounded" required>
                                <option value="">Select a category</option>
                                <option value="55kg">55kg</option>
                                <option value="61kg">61kg</option>
                                <option value="67kg">67kg</option>
                                <option value="73kg">73kg</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold">Entry Total:</label>
                            <input type="number" name="entry_total" class="w-full p-2 border rounded" required>
                        </div>

                        <div>
                            <label class="block font-bold">Reserve:</label>
                            <div class="flex items-center gap-4">
                                <label><input type="radio" name="reserve" value="1" required> Reserve</label>
                                <label><input type="radio" name="reserve" value="0" required> Not Reserve</label>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-semibold text-lg mb-2">ID Card</h4>
                            <div class="border rounded-lg p-2 bg-gray-50">
                                <img id="athleteIdCard" class="w-full h-auto object-contain rounded-md" src="" alt="ID Card">
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-2">Certificate</h4>
                            <div class="border rounded-lg p-2 bg-gray-50">
                                <img id="athleteCertificate" class="w-full h-auto object-contain rounded-md" src="" alt="Certificate">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Confirm
                    </button>
                    <button type="button" id="closeConfirmRegisterModal" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Create Competition Modal
        const createModal = document.getElementById("createModal");
        const openCreateModal = document.getElementById("openCreateModal");
        const closeCreateModal = document.getElementById("closeCreateModal");
        const createCompetitionForm = document.getElementById("createCompetitionForm");

        if (openCreateModal) {
            openCreateModal.addEventListener("click", function() {
                createModal.classList.remove("hidden");
            });
        }

        if (closeCreateModal) {
            closeCreateModal.addEventListener("click", function() {
                createModal.classList.add("hidden");
            });
        }

        // Close modal when clicking outside
        window.addEventListener("click", function(event) {
            if (event.target === createModal) {
                createModal.classList.add("hidden");
            }
        });

        // Handle form submission
        if (createCompetitionForm) {
            createCompetitionForm.addEventListener("submit", function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred while creating the competition.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating the competition.');
                });
            });
        }

        const registerModal = document.getElementById("registerModal");
        const confirmRegisterModal = document.getElementById("confirmRegisterModal");
        const registerForm = document.getElementById("registerForm");
        const confirmRegisterForm = document.getElementById("confirmRegisterForm");

        document.querySelectorAll(".register-btn").forEach(button => {
            button.addEventListener("click", function () {
                const competitionId = this.dataset.id;
                document.getElementById("competition_id").value = competitionId;
                registerForm.setAttribute("action", `/competitions/${competitionId}/register`);
                
                // Update the URL to include competition_id parameter
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('competition_id', competitionId);
                window.history.pushState({}, '', currentUrl);
                
                registerModal.classList.remove("hidden");
            });
        });

        // Check if there's a competition_id in the URL when page loads
        const urlParams = new URLSearchParams(window.location.search);
        const competitionId = urlParams.get('competition_id');
        if (competitionId) {
            document.getElementById("competition_id").value = competitionId;
            registerForm.setAttribute("action", `/competitions/${competitionId}/register`);
        }

        document.getElementById("confirmRegisterForm")?.addEventListener("submit", function (e) {
            e.preventDefault();
            
            // Log the form data to check what is being sent
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key + ": " + value);
            }

            // Submit the form
            this.submit();
        });

        document.getElementById("athlete")?.addEventListener("change", function () {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                document.getElementById("athleteName").innerText = selected.dataset.name || "-";
                document.getElementById("athleteDob").innerText = selected.dataset.dob || "-";
                document.getElementById("athleteAdamsId").innerText = selected.dataset.adamsId || "-";

                document.getElementById("athleteImage").src = selected.dataset.image || "/default-image.png";
                document.getElementById("athleteIdCard").src = selected.dataset.idcard || "/default-idcard.png";
                document.getElementById("athleteCertificate").src = selected.dataset.certificate || "/default-certificate.png";

                // Fill hidden fields
                document.getElementById("confirm_competition_id").value = document.getElementById("competition_id").value;
                document.getElementById("confirm_athlete_id").value = selected.value;

                confirmRegisterForm.setAttribute("action", `/competitions/${document.getElementById("confirm_competition_id").value}/register`);

                // Hide register modal, show confirm modal
                registerModal.classList.add("hidden");
                confirmRegisterModal.classList.remove("hidden");
            }
        });

        document.getElementById("closeRegisterModal")?.addEventListener("click", () => {
            registerModal.classList.add("hidden");
        });

        document.getElementById("closeConfirmRegisterModal")?.addEventListener("click", () => {
            confirmRegisterModal.classList.add("hidden");
        });
    });
    </script>
    @endpush

</x-app-layout>
