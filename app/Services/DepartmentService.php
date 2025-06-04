<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentService
{
    /**
     * Get paginated departments with employee counts and salary sums.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedDepartments(int $perPage = 10): LengthAwarePaginator
    {
        return Department::withCount('employees')
            ->withSum('employees', 'salary')
            ->paginate($perPage);
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
     * Create a new department.
     *
     * @param array $data
     * @return \App\Models\Department
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createDepartment(array $data): Department
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:departments',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Department::create([
            'name' => $data['name'],
        ]);
    }

    /**
     * Update an existing department.
     *
     * @param \App\Models\Department $department
     * @param array $data
     * @return \App\Models\Department
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateDepartment(Department $department, array $data): Department
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $department->update([
            'name' => $data['name'],
        ]);

        return $department->fresh();
    }

    /**
     * Delete a department.
     *
     * @param \App\Models\Department $department
     * @return bool
     */
    public function deleteDepartment(Department $department): bool
    {
        // Check if department has employees before deleting
        if ($department->employees()->count() > 0) {
            throw new ValidationException(Validator::make([], []), [
                'department' => 'Cannot delete department with employees. Reassign employees first.'
            ]);
        }

        return $department->delete();
    }

    /**
     * Get department salary statistics.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDepartmentSalaryStatistics()
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
