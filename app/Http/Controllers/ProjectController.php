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

    /**
     * Display a listing of the projects.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = $this->projectService->getAllEmployees();
        return view('projects.create', compact('employees'));
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project->load(['employee', 'employee.department']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $employees = $this->projectService->getAllEmployees();
        return view('projects.edit', compact('project', 'employees'));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified project from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Display projects by employee.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function byEmployee(Employee $employee)
    {
        $projects = $this->projectService->getProjectsByEmployee($employee);
        return view('projects.by_employee', compact('projects', 'employee'));
    }

    /**
     * Display employees working on multiple projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesWithMultipleProjects()
    {
        $employees = $this->projectService->getEmployeesWithMultipleProjects();
        return view('projects.employees_multiple_projects', compact('employees'));
    }

    /**
     * Display total salary expenditure per department.
     *
     * @return \Illuminate\Http\Response
     */
    public function salaryByDepartment()
    {
        $departmentSalaries = $this->projectService->getTotalSalaryByDepartment();
        return view('projects.salary_by_department', compact('departmentSalaries'));
    }
}
