@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('Department Details') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
                <li class="breadcrumb-item active">Department Details</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> {{ __('Edit Department') }}
                </a>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Department Information</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">ID</dt>
                                <dd class="col-sm-8">{{ $department->id }}</dd>

                                <dt class="col-sm-4">Department Name</dt>
                                <dd class="col-sm-8">{{ $department->name }}</dd>

                                <dt class="col-sm-4">Total Employees</dt>
                                <dd class="col-sm-8">{{ $department->employees_count }}</dd>

                                <dt class="col-sm-4">Total Salary</dt>
                                <dd class="col-sm-8">N{{ number_format($department->total_salary, 2) }}</dd>

                                <dt class="col-sm-4">Created At</dt>
                                <dd class="col-sm-8">{{ $department->created_at->format('F d, Y h:i A') }}</dd>

                                <dt class="col-sm-4">Updated At</dt>
                                <dd class="col-sm-8">{{ $department->updated_at->format('F d, Y h:i A') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Department Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Employees</span>
                                    <span class="info-box-number">{{ $department->employees_count }}</span>
                                </div>
                            </div>

                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Salary</span>
                                    <span class="info-box-number">N{{ number_format($department->total_salary, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Employees in Department</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Salary</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($department->employees as $employee)
                                    <tr>
                                        <td>{{ $employee->id }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>N{{ number_format($employee->salary, 2) }}</td>
                                        <td>
                                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No employees found in this department.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
