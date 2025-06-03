@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('Employee Details') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item active">Employee Details</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="float-right">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> {{ __('Edit Employee') }}
                </a>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>ID</label>
                        <p class="form-control">{{ $employee->id }}</p>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <p class="form-control">{{ $employee->name }}</p>
                    </div>

                    <div class="form-group">
                        <label>Department</label>
                        <p class="form-control">
                            <a href="{{ route('departments.show', $employee->department) }}">
                                {{ $employee->department->name }}
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <label>Salary</label>
                        <p class="form-control">N{{ number_format($employee->salary, 2) }}</p>
                    </div>

                    <div class="form-group">
                        <label>Created At</label>
                        <p class="form-control">{{ $employee->created_at->format('F d, Y h:i A') }}</p>
                    </div>

                    <div class="form-group">
                        <label>Last Updated</label>
                        <p class="form-control">{{ $employee->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assigned Projects</h3>
                    <div class="card-tools">
                        <a href="{{ route('projects.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('Assign New Project') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($employee->projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->projects as $project)
                                        <tr>
                                            <td>{{ $project->project_name }}</td>
                                            <td>
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No projects assigned to this employee yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
