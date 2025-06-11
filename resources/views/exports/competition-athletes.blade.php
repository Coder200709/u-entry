<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $competition->name }} - Athletes List</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            border-radius: 10px;
        }
        .competition-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .competition-info {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .info-item {
            text-align: center;
        }
        .info-label {
            font-size: 12px;
            color: #6c757d;
        }
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .footer {
            margin-top: 30px;
            padding: 15px;
            text-align: right;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #ddd;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-reserve {
            background-color: #ffc107;
            color: #000;
        }
        .badge-not-reserve {
            background-color: #28a745;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="competition-title">{{ $competition->name }}</div>
        @if($imagePath)
            <div style="text-align: center; margin: 20px 0;">
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($imagePath)) }}" 
                     alt="Competition Image" 
                     style="max-width: 200px; max-height: 200px; object-fit: contain;">
            </div>
        @endif
    </div>

    <div class="competition-info">
        <div class="info-item">
            <div class="info-label">Date</div>
            <div class="info-value">{{ $competition->date }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Location</div>
            <div class="info-value">{{ $competition->location }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Total Athletes</div>
            <div class="info-value">{{ count($athletes) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>JSHSHIR</th>
                <th>Category</th>
                <th>Entry Total</th>
                <th>Reserve</th>
            </tr>
        </thead>
        <tbody>
            @foreach($athletes as $athlete)
                <tr>
                    <td>{{ $athlete['name'] }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ strtoupper($athlete['adams_id']) }}</td>
                    <td>{{ $athlete['category'] }}</td>
                    <td>{{ $athlete['entry_total'] }}</td>
                    <td>
                        <span class="badge {{ $athlete['reserve'] == 'Yes' ? 'badge-reserve' : 'badge-not-reserve' }}">
                            {{ $athlete['reserve'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>Generated on: {{ now()->timezone('Asia/Tashkent')->format('Y-m-d H:i:s') }} (Uzbekistan Time)</div>
        <div>© {{ date('Y') }} Weightlifting Federation</div>
    </div>
</body>
</html> 