<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    /**
     * Get paginated employees with their departments.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedEmployees(int $perPage = 10): LengthAwarePaginator
    {
        return Employee::with('department')->paginate($perPage);
    }

    /**
     * Get all departments.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDepartments(): Collection
    {
        return Department::all();
    }

    /**
     * Create a new employee.
     *
     * @param array $data
     * @return \App\Models\Employee
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createEmployee(array $data): Employee
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Employee::create([
            'name' => $data['name'],
            'department_id' => $data['department_id'],
            'salary' => $data['salary'],
        ]);
    }

    /**
     * Update an existing employee.
     *
     * @param \App\Models\Employee $employee
     * @param array $data
     * @return \App\Models\Employee
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateEmployee(Employee $employee, array $data): Employee
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $employee->update([
            'name' => $data['name'],
            'department_id' => $data['department_id'],
            'salary' => $data['salary'],
        ]);

        return $employee->fresh();
    }

    /**
     * Delete an employee.
     *
     * @param \App\Models\Employee $employee
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteEmployee(Employee $employee): bool
    {
        // Check if employee has projects before deleting
        if ($employee->projects()->count() > 0) {
            throw new ValidationException(Validator::make([], []), [
                'employee' => 'Cannot delete employee with assigned projects. Reassign projects first.'
            ]);
        }

        return $employee->delete();
    }

    /**
     * Get employees by department.
     *
     * @param \App\Models\Department $department
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEmployeesByDepartment(Department $department): Collection
    {
        return $department->employees;
    }

    /**
     * Get salary statistics by department.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSalaryByDepartment()
    {
        $departments = Department::with('employees')->get();

        return $departments->map(function ($department) {
            return [
                'name' => $department->name,
                'total_salary' => $department->employees->sum('salary'),
                'employee_count' => $department->employees->count(),
            ];
        });
    }
}
