<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Athlete</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Add New Athlete</h1>
        <form action="{{ route('admin.athletes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="family_name">Family Name</label>
                <input type="text" name="family_name" id="family_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="region">Region</label>
                <input type="text" name="region" id="region" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="passport_copy">Passport Copy</label>
                <input type="file" name="passport_copy" id="passport_copy" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Athlete</button>
            <a href="{{ route('admin.athletes') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>