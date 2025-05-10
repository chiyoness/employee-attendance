<!DOCTYPE html>
<html>
<head>
    <title>All Employees</title>
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
    <h1>All Employees Report</h1>
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
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone ?? 'Not specified' }}</td>
                    <td>{{ $employee->job ?? 'Not specified' }}</td>
                    <td>{{ ucfirst($employee->roles->pluck('name')->first()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
