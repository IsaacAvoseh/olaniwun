<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function getPaginatedProjects(int $perPage = 10): LengthAwarePaginator
    {
        return Project::with(['employee', 'employee.department'])->paginate($perPage);
    }

    public function getAllEmployees(): Collection
    {
        return Employee::with('department')->get();
    }


    public function createProject(array $data): Project
    {
        $validator = Validator::make($data, [
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_id' => 'required|exists:employees,id',
            'task' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:Not Started,In Progress,Completed,On Hold',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Project::create([
            'project_name' => $data['project_name'],
            'description' => $data['description'] ?? null,
            'employee_id' => $data['employee_id'],
            'task' => $data['task'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'status' => $data['status'] ?? 'Not Started',
        ]);
    }

    /**
     * Update an existing project.
     *
     * @param \App\Models\Project $project
     * @param array $data
     * @return \App\Models\Project
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProject(Project $project, array $data): Project
    {
        $validator = Validator::make($data, [
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employee_id' => 'required|exists:employees,id',
            'task' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:Not Started,In Progress,Completed,On Hold',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $project->update([
            'project_name' => $data['project_name'],
            'description' => $data['description'] ?? $project->description,
            'employee_id' => $data['employee_id'],
            'task' => $data['task'] ?? $project->task,
            'start_date' => $data['start_date'] ?? $project->start_date,
            'end_date' => $data['end_date'] ?? $project->end_date,
            'status' => $data['status'] ?? $project->status,
        ]);

        return $project->fresh();
    }

    public function deleteProject(Project $project): bool
    {
        return $project->delete();
    }


    public function getProjectsByEmployee(Employee $employee): Collection
    {
        return $employee->projects;
    }

    /**
     * Filter projects by criteria.
     *
     * @param array $criteria
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function filterProjects(array $criteria, int $perPage = 10): LengthAwarePaginator
    {
        $query = Project::with(['employee', 'employee.department']);

        // Filter by employee
        if (!empty($criteria['employee_id'])) {
            $query->where('employee_id', $criteria['employee_id']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get employees working on more than one project.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEmployeesWithMultipleProjects(): Collection
    {
        return Employee::withCount('projects')
            ->having('projects_count', '>', 1)
            ->with(['department', 'projects'])
            ->get();
    }

    /**
     * Get total salary expenditure per department.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalSalaryByDepartment(): Collection
    {
        return Employee::selectRaw('department_id, SUM(salary) as total_salary')
            ->groupBy('department_id')
            ->with('department')
            ->get();
    }
}
