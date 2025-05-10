<!DOCTYPE html>
<html>
<head>
    <title>All Users</title>
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
    <h1>All Users Report</h1>
    <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Job</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? 'Not specified' }}</td>
                    <td>{{ $user->job ?? 'Not specified' }}</td>
                    <td>{{ ucfirst($user->roles->pluck('name')->first()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
