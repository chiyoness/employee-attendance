<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>All Employees Detailed Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .page-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-header h1 {
            color: #2c3e50;
            margin: 0;
            padding: 0;
            font-size: 24px;
        }
        .page-header p {
            color: #7f8c8d;
            margin: 5px 0 0;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 20px;
        }
        .summary {
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>All Employees Detailed Report</h1>
        <p>Generated on: {{ date('M d, Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Job</th>
                <th>Role</th>
                <th>Joined Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone ?? 'Not specified' }}</td>
                    <td>{{ $employee->job ?? 'Not specified' }}</td>
                    <td>{{ ucfirst($employee->getRoleNames()->first() ?? 'Employee') }}</td>
                    <td>{{ $employee->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        Total Employees: {{ $employees->count() }}
    </div>
    
    <div class="footer">
        &copy; {{ date('Y') }} Employee Attendance System - Confidential
    </div>
</body>
</html>
