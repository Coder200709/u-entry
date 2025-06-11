<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athlete List</title>
    <style>
    body { 
        font-family: Arial, sans-serif;
        margin: 0px; /* Adds margins around the document */
    }
    
    .table-container {
        width: 100%; /* Make table smaller than full width */
        margin: 0 auto; /* Centers the table */
    }

    table { 
        width: 100%; /* Table will be responsive within the container */
        border-collapse: collapse; 
        margin-top: 20px;
        margin-left:-20px; 
    }

    th, td { 
        border: 1px solid black; 
        padding: 8px; 
        text-align: left; 
        word-wrap: break-word;
    }

    th { background-color: #f2f2f2; }

    img { 
        width: 50px; 
        height: 50px; 
        object-fit: cover; 
        border-radius: 5px; 
    }
</style>

</head>
<body>

    <h2 style="text-align: center;">Athlete List</h2>
    
    <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Nation</th>
                <th>Region</th>
                <th>ADAMS ID</th>
                <th>Picture</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($athletes as $index => $athlete)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $athlete->family_name }} {{ $athlete->given_name }}</td>
                    <td>{{ $athlete->gender }}</td>
                    <td>{{ $athlete->date_of_birth }}</td>
                    <td>{{ $athlete->nation }}</td>
                    <td>{{ $athlete->region }}</td>
                    <td>{{ $athlete->adams_id }}</td>
                    <td>
                        @if($athlete->picture)
                            <img src="{{ public_path('storage/' . $athlete->picture) }}" alt="Athlete Picture">
                        @else
                            No Image
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


</body>
</html>
