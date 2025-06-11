<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('messages.dashboard') }}
            </h2>
            <form action="{{ route('switchLang', app()->getLocale()) }}" method="get">
                <select id="language-select" onchange="changeLanguage(this.value)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>Русский</option>
                    <option value="uz" {{ app()->getLocale() == 'uz' ? 'selected' : '' }}>Oʻzbekcha</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="flex justify-between items-center mb-4">
                    <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700" 
                        onclick="document.getElementById('addAthleteModal').classList.remove('hidden')">
                        + {{ __('messages.add_athlete') }}
                    </button>
                    <input type="text" id="searchInput" placeholder="{{ __('messages.search_by_name') }}" 
                    class="border p-2 rounded w-64">
                    
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-700">
                        {{ __('messages.export') }} ▼
                        </button>
                        <div id="exportDropdown" class="absolute bg-white shadow-md mt-2 rounded hidden">
                            <a href="{{ route('athletes.export', 'pdf') }}" target="_blank" 
                               class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                               {{ __('messages.export_to_pdf') }}
                            </a>
                            <a href="{{ route('athletes.export', 'excel') }}" 
                               class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                               {{ __('messages.export_to_excel') }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700">  {{ __('messages.filter_by_region') }}:</label>
                        <select id="regionFilter" class="border p-2 rounded">
                            <option value="">All Regions</option>
                            <option value="Andijan">Andijan</option>
                            <option value="Bukhara">Bukhara</option>
                            <option value="Fergana">Fergana</option>
                            <option value="Jizzakh">Jizzakh</option>
                            <option value="Kashkadarya">Kashkadarya</option>
                            <option value="Khorezm">Khorezm</option>
                            <option value="Namangan">Namangan</option>
                            <option value="Navoi">Navoi</option>
                            <option value="Samarkand">Samarkand</option>
                            <option value="Surkhandarya">Surkhandarya</option>
                            <option value="Syrdarya">Syrdarya</option>
                            <option value="Tashkent">Tashkent</option>
                        </select>
                    </div>
                </div>

                <!-- Add Athlete Modal -->
                <div id="addAthleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
                        <h3 class="text-xl font-semibold mb-4">Add New Athlete</h3>

                        <form action="{{ route('athletes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700">Gender:</label>
                                    <select name="gender" class="w-full border p-2 rounded" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-gray-700">Family Name:</label>
                                    <input type="text" name="family_name" class="w-full border p-2 rounded" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700">Given Name:</label>
                                    <input type="text" name="given_name" class="w-full border p-2 rounded" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700">Date of Birth:</label>
                                    <input type="date" name="date_of_birth" class="w-full border p-2 rounded" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700">Nation:</label>
                                    <input type="text" name="nation" class="w-full border p-2 rounded" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700">Region:</label>
                                    <input type="text" name="region" class="w-full border p-2 rounded" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700">JSHSHIR:</label>
                                    <input type="text" name="adams_id" class="w-full border p-2 rounded" required>
                                </div>

                                <div>
                                    <label class="block text-gray-700">ID Picture:</label>
                                    <input type="file" name="id_card_picture" class="w-full border p-2 rounded">
                                </div>

                                <div>
                                    <label class="block text-gray-700">Certificate (Optional):</label>
                                    <input type="file" name="certificate" class="w-full border p-2 rounded">
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2"
                                    onclick="document.getElementById('addAthleteModal').classList.add('hidden')">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
                                    Add Athlete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Athlete Table -->
                <div class="overflow-auto">
                    <table class="w-full border-collapse border border-gray-300 mt-4">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Gender</th>
                                <th class="border px-4 py-2">Family Name</th>
                                <th class="border px-4 py-2">Given Name</th>
                                <th class="border px-4 py-2">Date of Birth</th>
                                <th class="border px-4 py-2">Nation</th>
                                <th class="border px-4 py-2">Region</th>
                                <th class="border px-4 py-2">JSHSHIR</th>
                                <th class="border px-4 py-2">ID Picture</th>
                                <th class="border px-4 py-2">Certificate</th>
                                <th class="border px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($athletes as $athlete)
                                <tr class="border athlete-row" data-region="{{ $athlete->region }}" data-gender="{{ strtolower(trim($athlete->gender)) }}">
                                    <td class="border px-4 py-2">{{ $athlete->id }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->gender }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->family_name }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->given_name }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->date_of_birth }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->nation }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->region }}</td>
                                    <td class="border px-4 py-2">{{ $athlete->adams_id }}</td>
                                    <td class="border px-4 py-2">
                                        @if($athlete->id_card_picture)
                                            <img src="{{ asset('storage/' . $athlete->id_card_picture) }}" alt="ID Picture" class="w-16 h-16 rounded cursor-pointer" onclick="openModal('{{ asset('storage/' . $athlete->id_card_picture) }}')">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($athlete->certificate)
                                            <img src="{{ asset('storage/' . $athlete->certificate) }}" alt="Certificate" class="w-16 h-16 rounded cursor-pointer" onclick="openModal('{{ asset('storage/' . $athlete->certificate) }}')">
                                        @else
                                            No Certificate
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2 flex gap-2">
                                        <a href="{{ route('athletes.edit', $athlete->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
                                        <form action="{{ route('athletes.destroy', $athlete->id) }}" method="POST" id="delete-form-{{ $athlete->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-button bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700" onclick="confirmDelete(event, {{ $athlete->id }})">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center hidden" onclick="closeModal()">
        <div class="relative bg-white p-4 rounded-lg w-3/5 max-w-lg" onclick="event.stopPropagation();">
            <button onclick="closeModal()" class="absolute top-0 right-0 m-2 text-white text-lg font-bold">X</button>
            <img id="modalImage" src="" alt="Modal Image" class="w-full h-auto">
        </div>
    </div>

</x-app-layout>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('exportDropdown');
        dropdown.classList.toggle('hidden');
    }

    function openModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function confirmDelete(event, athleteId) {
        event.preventDefault();
        if (confirm('Are you sure you want to delete this athlete?')) {
            document.getElementById(`delete-form-${athleteId}`).submit();
        }
    }
</script>
