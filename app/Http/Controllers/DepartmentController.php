<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    protected $departmentService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\DepartmentService $departmentService
     * @return void
     */
    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        $departments = $this->departmentService->getPaginatedDepartments(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        try {
            $this->departmentService->createDepartment($request->all());
            return redirect()->route('departments.index')
                ->with('success', 'Department created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        try {
            $this->departmentService->updateDepartment($department, $request->all());
            return redirect()->route('departments.index')
                ->with('success', 'Department updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function destroy(Department $department)
    {
        try {
            $this->departmentService->deleteDepartment($department);
            return redirect()->route('departments.index')
                ->with('success', 'Department deleted successfully.');
        } catch (ValidationException $e) {
            return redirect()->route('departments.index')
                ->with('error', $e->getMessage() ?: 'Cannot delete department with employees. Reassign employees first.');
        }
    }
}
