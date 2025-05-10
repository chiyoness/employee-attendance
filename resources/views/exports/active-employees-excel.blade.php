<!DOCTYPE html>
<html>
<head>
    <title>Active Employees</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Active Employees</h1>
    <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Job</th>
                <th>Check-In Time</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activeUsers as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? 'Not specified' }}</td>
                    <td>{{ $user->job ?? 'Not specified' }}</td>
                    <td>{{ $user->attendance->check_in_time->format('M d, Y - h:i A') }}</td>
                    <td>{{ $user->attendance->check_in_time->diffForHumans(null, true) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
