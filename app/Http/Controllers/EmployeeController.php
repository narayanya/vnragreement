<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('emp_name',       'like', "%{$s}%")
                  ->orWhere('employee_id',  'like', "%{$s}%")
                  ->orWhere('emp_code',     'like', "%{$s}%")
                  ->orWhere('emp_email',    'like', "%{$s}%")
                  ->orWhere('emp_contact',  'like', "%{$s}%");
            });
        }

        // Status filter  (A = Active, D/I/0 = Inactive)
        if ($request->filled('status')) {
            if ($request->status === 'A') {
                $query->where('emp_status', 'A');
            } elseif ($request->status === 'inactive') {
                $query->whereIn('emp_status', ['I', 'D', '0']);
            }
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('emp_department', $request->department);
        }

        // Designation filter
        if ($request->filled('designation')) {
            $query->where('emp_designation', $request->designation);
        }

        // Counts (before pagination, after filters except status)
        $baseQuery     = Employee::query();
        $totalCount    = $baseQuery->count();
        $activeCount   = (clone $baseQuery)->where('emp_status', 'A')->count();
        $inactiveCount = (clone $baseQuery)->whereIn('emp_status', ['I', 'D', '0'])->count();

        $employees = $query->orderBy('emp_name')->paginate(20)->withQueryString();

        // Distinct values for filter dropdowns
        $departments  = Employee::whereNotNull('emp_department')->distinct()->orderBy('emp_department')->pluck('emp_department');
        $designations = Employee::whereNotNull('emp_designation')->distinct()->orderBy('emp_designation')->pluck('emp_designation');

        return view('master.employees.index', compact(
            'employees',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'departments',
            'designations'
        ));
    }
}
