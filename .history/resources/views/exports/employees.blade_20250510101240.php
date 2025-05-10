<!DOCTYPE html>
<html>
<head>
    <title>All Employees</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            color: #2980b9;
            text-align: center;
            margin-bottom: 5px;
        }
        .company-name {
            text-align: center;
            font-size: 16px;
            margin-bottom: 0;
            font-weight: bold;
        }
        .report-info {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <p class="company-name">Employee Attendance System</p>
    <h1>All Employees Report</h1>
    <p class="report-info">Generated on: {{ date('F d, Y - h:i A') }}</p>
    
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
    
    <div class="footer">
        <p>This is an automatically generated report. Please do not reply.</p>
        <p>© {{ date('Y') }} Employee Attendance System. All Rights Reserved.</p>
    </div>
</body>
</html>
