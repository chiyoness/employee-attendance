<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="background-color: #4a86e8; color: white; font-size: 16px; text-align: center;">All Employees Detailed Report</th>
        </tr>
        <tr>
            <th colspan="6" style="background-color: #f3f3f3; text-align: right; font-style: italic;">Generated on: {{ date('M d, Y') }}</th>
        </tr>
        <tr style="background-color: #f3f3f3; font-weight: bold;">
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
            <tr>                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone ?? 'Not specified' }}</td>
                <td>{{ $employee->job ?? 'Not specified' }}</td>
                <td>{{ ucfirst($employee->getRoleNames()->first() ?? 'Employee') }}</td>
                <td>{{ $employee->created_at->format('M d, Y') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="background-color: #f3f3f3; text-align: center;">Total Employees: {{ $employees->count() }}</td>
        </tr>
    </tfoot>
</table>
