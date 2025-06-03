@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('Employees with Multiple Projects') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">Employees with Multiple Projects</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Employees Working on More Than One Project</h3>
        </div>
        <div class="card-body">
            @if ($employees->isEmpty())
                <div class="alert alert-info">
                    <i class="icon fas fa-info"></i> No employees are currently working on multiple projects.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Salary</th>
                                <th>Number of Projects</th>
                                <th>Projects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>N{{ number_format($employee->salary, 2) }}</td>
                                    <td>{{ $employee->projects_count }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($employee->projects as $project)
                                                <li>
                                                    <a href="{{ route('projects.show', $project) }}">
                                                        {{ $project->project_name }}
                                                    </a>
                                                    <span class="badge badge-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'primary' : ($project->status == 'On Hold' ? 'warning' : 'secondary')) }}">
                                                        {{ $project->status }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
