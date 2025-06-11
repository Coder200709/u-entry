<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Athlete
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('athletes.update', $athlete->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <label>Gender:</label>
                    <input type="text" name="gender" value="{{ $athlete->gender }}" required>
<br>
                    <label>Family Name:</label>
                    <input type="text" name="family_name" value="{{ $athlete->family_name }}" required>
<br>
                    <label>Given Name:</label>
                    <input type="text" name="given_name" value="{{ $athlete->given_name }}" required>
<br>
                    <label>Date of Birth:</label>
                    <input type="date" name="date_of_birth" value="{{ $athlete->date_of_birth }}" required>
<br>
                    <label>Nation:</label>
                    <input type="text" name="nation" value="{{ $athlete->nation }}" required>
<br>
                    <label>Region:</label>
                    <input type="text" name="region" value="{{ $athlete->region }}" required>
<br>
                    <label>JSHSHIR:</label>
                    <input type="text" name="adams_id" value="{{ $athlete->adams_id }}" required>
<br>
                    <label>Current ID card Picture:</label>
                    @if($athlete->id_card_picture)
                        <img src="{{ asset('storage/' . $athlete->id_card_picture) }}" width="100">
                    @endif
<br>
                    <label>New Picture (Optional):</label>
                    <input type="file" name="id_card_picture">
<br>
<label>Current Certificate Picture:</label>
                    @if($athlete->certificate)
                        <img src="{{ asset('storage/' . $athlete->certificate) }}" width="100">
                    @endif
<br>
                    <label>New Picture (Optional):</label>
                    <input type="file" name="certificate">
<br>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Athlete</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
