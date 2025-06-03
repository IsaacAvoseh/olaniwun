<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    protected $projectService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\ProjectService $projectService
     * @return void
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }


    public function index(Request $request)
    {
        if ($request->has('employee_id')) {
            $projects = $this->projectService->filterProjects($request->all());
        } else {
            $projects = $this->projectService->getPaginatedProjects();
        }

        $employees = $this->projectService->getAllEmployees();
        return view('projects.index', compact('projects', 'employees'));
    }

    public function create()
    {
        $employees = $this->projectService->getAllEmployees();
        return view('projects.create', compact('employees'));
    }

    public function store(Request $request)
    {
        try {
            $this->projectService->createProject($request->all());
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show(Project $project)
    {
        $project->load(['employee', 'employee.department']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $employees = $this->projectService->getAllEmployees();
        return view('projects.edit', compact('project', 'employees'));
    }

    public function update(Request $request, Project $project)
    {
        try {
            $this->projectService->updateProject($project, $request->all());
            return redirect()->route('projects.index')
                ->with('success', 'Project updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function byEmployee(Employee $employee)
    {
        $projects = $this->projectService->getProjectsByEmployee($employee);
        return view('projects.by_employee', compact('projects', 'employee'));
    }

    public function employeesWithMultipleProjects()
    {
        $employees = $this->projectService->getEmployeesWithMultipleProjects();
        return view('projects.employees_multiple_projects', compact('employees'));
    }

    public function salaryByDepartment()
    {
        $departmentSalaries = $this->projectService->getTotalSalaryByDepartment();
        return view('projects.salary_by_department', compact('departmentSalaries'));
    }
}
