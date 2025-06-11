<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athlete Database</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Athlete Database</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('admin.athletes.create') }}" class="btn btn-primary">Add Athlete</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Family Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Region</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($athletes as $athlete)
                    <tr>
                        <td>{{ $athlete->name }}</td>
                        <td>{{ $athlete->family_name }}</td>
                        <td>{{ $athlete->gender }}</td>
                        <td>{{ $athlete->date_of_birth }}</td>
                        <td>{{ $athlete->region }}</td>
                        <td>
                            <a href="{{ route('admin.athletes.edit', $athlete->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.athletes.destroy', $athlete->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>