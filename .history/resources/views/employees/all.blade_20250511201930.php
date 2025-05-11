@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold text-primary">All Employees</h1>
            <p class="lead text-muted">Comprehensive view of all employees in the system</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="#" id="exportExcel">Excel (Client-side)</a></li>
                    <li><a class="dropdown-item" href="#" id="exportCSV">CSV (Client-side)</a></li>
                    <li><a class="dropdown-item" href="#" id="exportPDF">PDF (Client-side)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('employees.export.all.excel') }}">Excel (Server-side)</a></li>
                    <li><a class="dropdown-item" href="{{ route('employees.export.all.csv') }}">CSV (Server-side)</a></li>
                    <li><a class="dropdown-item" href="{{ route('employees.export.all.pdf') }}">PDF (Server-side)</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-users text-primary me-2"></i>Employee Directory
            </h5>
        </div>
        <div class="card-body">
            @if($employees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="employeesTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user text-primary me-2"></i>Name</th>
                                <th><i class="fas fa-envelope text-primary me-2"></i>Email</th>
                                <th><i class="fas fa-briefcase text-primary me-2"></i>Job</th>
                                <th><i class="fas fa-phone text-primary me-2"></i>Phone</th>
                                <th><i class="fas fa-calendar text-primary me-2"></i>Joined Date</th>
                                <th><i class="fas fa-chart-line text-primary me-2"></i>Status</th>
                                <th><i class="fas fa-cogs text-primary me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($employee->image)
                                                <img src="{{ asset('storage/'.$employee->image) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $employee->name }}">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                                </div>
                                            @endif                                            <div>
                                                <div class="fw-bold">{{ $employee->name }}</div>
                                                <span class="badge bg-success">
                                                    Employee
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->job ?? 'Not specified' }}</td>
                                    <td>{{ $employee->phone ?? 'Not specified' }}</td>                                    <td>{{ $employee->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($employee->attendance && !$employee->attendance->check_out_time)
                                            <span class="badge bg-success">Currently Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $employee->getKey()) }}" class="btn btn-sm btn-outline-primary" title="View Employee">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $employee->getKey()) }}" class="btn btn-sm btn-outline-secondary" title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('attendance.history', $employee->getKey()) }}" class="btn btn-sm btn-outline-info" title="Attendance History">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $employees->links() }}
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i> No employees found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/3b0c858f61.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Client-side exports
        document.getElementById('exportExcel').addEventListener('click', function(e) {
            e.preventDefault();
            exportTableToExcel('employeesTable', 'all_employees_detailed');
        });

        document.getElementById('exportCSV').addEventListener('click', function(e) {
            e.preventDefault();
            exportTableToCSV('employeesTable', 'all_employees_detailed.csv');
        });

        document.getElementById('exportPDF').addEventListener('click', function(e) {
            e.preventDefault();
            exportTableToPDF('employeesTable', 'all_employees_detailed.pdf');
        });

        // Add loading indicators for server-side exports
        const serverExportLinks = document.querySelectorAll('a[href^="{{ route("employees.export.all.excel") }}"], a[href^="{{ route("employees.export.all.csv") }}"], a[href^="{{ route("employees.export.all.pdf") }}"]');
        serverExportLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Add a loading spinner to the button
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                
                // After a delay, restore the original text
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 3000); // 3 seconds should be enough for most exports
            });
        });
    });

    // Excel Export Function
    function exportTableToExcel(tableID, filename = '') {
        const table = document.getElementById(tableID);
        const wb = XLSX.utils.table_to_book(table, { sheet: "All Employees" });
        
        XLSX.writeFile(wb, filename + '.xlsx');
    }

    // CSV Export Function
    function exportTableToCSV(tableID, filename) {
        const table = document.getElementById(tableID);
        const rows = Array.from(table.querySelectorAll('tr'));
        
        // Extract data from table
        const csvContent = rows.map(row => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            return cells.map(cell => {
                // Get text content, skip images and icons
                let text = cell.textContent.trim();
                
                // Clean up the text, remove multiple spaces
                text = text.replace(/\s+/g, ' ').trim();
                
                // Escape quotes
                return `"${text.replace(/"/g, '""')}"`;
            }).join(',');
        }).join('\n');
        
        // Create download link
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // PDF Export Function
    function exportTableToPDF(tableID, filename) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add title to PDF
        doc.setFontSize(18);
        doc.text('All Employees Detailed Report', 14, 15);
        doc.setFontSize(11);
        doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 22);
        
        // Generate the table
        doc.autoTable({
            html: '#' + tableID,
            startY: 28,
            styles: { overflow: 'linebreak' },
            columnStyles: { 5: { cellWidth: 'wrap' } },
            headStyles: { fillColor: [41, 128, 185], textColor: 255 },
            margin: { top: 28 },
            theme: 'striped',
            
            // Customize the columns to exclude action buttons
            didParseCell: function(data) {
                // Hide the Actions column
                if (data.column.index === 6) {
                    data.cell.text = '';
                }
                
                // Clean up content for the name column
                if (data.column.index === 0 && data.section === 'body') {
                    const nameText = data.cell.raw.textContent.trim();
                    data.cell.text = [nameText];
                }
            }
        });
        
        doc.save(filename);
    }
</script>
@endpush
