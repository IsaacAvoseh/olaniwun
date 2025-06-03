@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('Project Details') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">Project Details</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="float-right">
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> {{ __('Edit Project') }}
                </a>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Project Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ID</label>
                        <p class="form-control">{{ $project->id }}</p>
                    </div>

                    <div class="form-group">
                        <label>Project Title</label>
                        <p class="form-control">{{ $project->project_name }}</p>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <p class="form-control" style="min-height: 80px;">{{ $project->description ?? 'No description provided' }}</p>
                    </div>

                    <div class="form-group">
                        <label>Assigned To</label>
                        <p class="form-control">
                            <a href="{{ route('employees.show', $project->employee) }}">
                                {{ $project->employee->name }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Department</label>
                        <p class="form-control">
                            <a href="{{ route('departments.show', $project->employee->department) }}">
                                {{ $project->employee->department->name }}
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <label>Task</label>
                        <p class="form-control" style="min-height: 60px;">{{ $project->task ?? 'No task specified' }}</p>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <p class="form-control">
                            <span class="badge badge-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'primary' : ($project->status == 'On Hold' ? 'warning' : 'secondary')) }}">
                                {{ $project->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Start Date</label>
                        <p class="form-control">{{ $project->start_date ? date('F d, Y', strtotime($project->start_date)) : 'Not specified' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>End Date</label>
                        <p class="form-control">{{ $project->end_date ? date('F d, Y', strtotime($project->end_date)) : 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Created At</label>
                        <p class="form-control">{{ $project->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Updated</label>
                        <p class="form-control">{{ $project->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
