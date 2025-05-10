<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{    /**
     * Export employees to CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Job', 'Role']);

            User::whereHas('roles', function ($query) {
                $query->where('name', 'employee');
            })->with('roles')->chunk(100, function ($users) use ($file) {
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name, 
                        $user->email, 
                        $user->phone ?? 'Not specified', 
                        $user->job ?? 'Not specified',
                        ucfirst($user->roles->pluck('name')->first())
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }    /**
     * Export employees to PDF.
     */    public function exportPdf()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        })->with('roles')->get();

        $pdf = Pdf::loadView('exports.employees', compact('employees'));

        return $pdf->download('employees.pdf');
    }
    
    /**
     * Export employees to Excel.
     */
    public function exportExcel()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        })->with('roles')->get();
        
        // Return a view that will be converted to Excel via browser download
        return response()->view('exports.employees-excel', compact('employees'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="all_employees.xls"');
    }
    
    /**
     * Export active employees to Excel.
     */
    public function exportActiveEmployeesExcel()
    {
        $activeUsers = User::whereHas('attendance', function ($query) {
            $query->whereNull('check_out_time');
        })->with('attendance')->get();
        
        // Return a view that will be converted to Excel via browser download
        return response()->view('exports.active-employees-excel', compact('activeUsers'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="active_employees.xls"');
    }

    /**
     * Export active employees to CSV.
     */
    public function exportActiveEmployeesCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="active_employees.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Job', 'Check-In Time', 'Duration']);

            User::whereHas('attendance', function ($query) {
                $query->whereNull('check_out_time');
            })->with('attendance')->chunk(100, function ($users) use ($file) {
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name, 
                        $user->email, 
                        $user->phone ?? 'Not specified', 
                        $user->job ?? 'Not specified',
                        $user->attendance->check_in_time->format('M d, Y - h:i A'),
                        $user->attendance->check_in_time->diffForHumans(null, true)
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }    /**
     * Export active employees to PDF.
     */
    public function exportActiveEmployeesPdf()
    {
        $activeUsers = User::whereHas('attendance', function ($query) {
            $query->whereNull('check_out_time');
        })->with('attendance')->get();

        $pdf = Pdf::loadView('exports.active-employees', compact('activeUsers'));

        return $pdf->download('active_employees.pdf');
    }

    /**
     * Export all users to Excel.
     */
    public function exportAllUsersExcel()
    {
        $users = User::with('roles')->get();
        
        // Return a view that will be converted to Excel via browser download
        return response()->view('exports.all-users-excel', compact('users'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="all_users.xls"');
    }
    
    /**
     * Export all users to CSV.
     */
    public function exportAllUsersCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="all_users.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Job', 'Role']);

            User::with('roles')->chunk(100, function ($users) use ($file) {
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name, 
                        $user->email, 
                        $user->phone ?? 'Not specified', 
                        $user->job ?? 'Not specified',
                        ucfirst($user->roles->pluck('name')->first())
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export all users to PDF.
     */
    public function exportAllUsersPdf()
    {
        $users = User::with('roles')->get();

        $pdf = Pdf::loadView('exports.all-users', compact('users'));

        return $pdf->download('all_users.pdf');
    }

    /**
     * Export all employees to Excel.
     */
    public function exportAllEmployeesExcel()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        })->with('roles')->get();
        
        // Return a view that will be converted to Excel via browser download
        return response()->view('exports.all-employees-excel', compact('employees'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="all_employees_detailed.xls"');
    }

    /**
     * Export all employees to CSV.
     */
    public function exportAllEmployeesCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="all_employees_detailed.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Job', 'Role', 'Created At']);

            User::whereHas('roles', function ($query) {
                $query->where('name', 'employee');
            })->with('roles')->chunk(100, function ($users) use ($file) {                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name, 
                        $user->email, 
                        $user->phone ?? 'Not specified', 
                        $user->job ?? 'Not specified',
                        ucfirst($user->getRoleNames()->first() ?? 'Employee'),
                        $user->created_at->format('M d, Y')
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all employees to PDF.
     */
    public function exportAllEmployeesPdf()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        })->with('roles')->get();
        
        $pdf = Pdf::loadView('exports.all-employees-pdf', compact('employees'));

        return $pdf->download('all_employees_detailed.pdf');
    }
}
