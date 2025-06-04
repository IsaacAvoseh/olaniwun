<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    protected $employeeService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\EmployeeService $employeeService
     * @return void
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index()
    {
        $employees = $this->employeeService->getPaginatedEmployees(10);
        $departments = $this->employeeService->getAllDepartments();
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = $this->employeeService->getAllDepartments();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        try {
            $this->employeeService->createEmployee($request->all());
            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show(Employee $employee)
    {
        $employee->load('department', 'projects');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = $this->employeeService->getAllDepartments();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            $this->employeeService->updateEmployee($employee, $request->all());
            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            $this->employeeService->deleteEmployee($employee);
            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted successfully.');
        } catch (ValidationException $e) {
            return redirect()->route('employees.index')
                ->with('error', $e->getMessage() ?: 'Cannot delete employee with assigned projects. Reassign projects first.');
        }
    }

    public function byDepartment(Department $department)
    {
        $employees = $this->employeeService->getEmployeesByDepartment($department);
        return view('employees.by_department', compact('employees', 'department'));
    }

    public function salaryByDepartment()
    {
        $departmentSalaries = $this->employeeService->getSalaryByDepartment();
        return view('employees.salary_by_department', compact('departmentSalaries'));
    }
}
