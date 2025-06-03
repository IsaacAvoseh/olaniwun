<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    
    public function index()
    {
        $employees = Employee::with('department')->paginate(10);
        $departments = Department::all();
        return view('employees.index', compact('employees', 'departments'));
    }


    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Employee::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'salary' => $request->salary,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('department', 'projects');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

   
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'salary' => $request->salary,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Check if employee has projects before deleting
        if ($employee->projects()->count() > 0) {
            return redirect()->route('employees.index')
                ->with('error', 'Cannot delete employee with assigned projects. Reassign projects first.');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

  
    public function byDepartment(Department $department)
    {
        $employees = $department->employees;
        return view('employees.by_department', compact('employees', 'department'));
    }

    public function salaryByDepartment()
    {
        $departments = Department::with('employees')->get();

        $departmentSalaries = $departments->map(function ($department) {
            return [
                'name' => $department->name,
                'total_salary' => $department->employees->sum('salary'),
                'employee_count' => $department->employees->count(),
            ];
        });

        return view('employees.salary_by_department', compact('departmentSalaries'));
    }
}
