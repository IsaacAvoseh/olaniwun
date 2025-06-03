<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with relevant data and metrics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get counts for dashboard metrics
        $departmentCount = Department::count();
        $employeeCount = Employee::count();
        $projectCount = Project::count();

        // Get recent employees
        $recentEmployees = Employee::with('department')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent projects
        $recentProjects = Project::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get department salary data for chart
        $departments = Department::with('employees')->get();
        $departmentSalaries = $departments->map(function ($department) {
            return [
                'name' => $department->name,
                'total_salary' => $department->employees->sum('salary'),
                'employee_count' => $department->employees->count(),
            ];
        });

        // Get projects by employee count
        $employeeProjects = Employee::withCount('projects')->orderBy('projects_count', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'departmentCount',
            'employeeCount',
            'projectCount',
            'recentEmployees',
            'recentProjects',
            'departmentSalaries',
            'employeeProjects'
        ));
    }
}
