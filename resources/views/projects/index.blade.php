@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('Project Management') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Project List</h3>
            <div class="card-tools">
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ __('Add New Project') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <form action="{{ route('projects.index') }}" method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="employee_filter" class="sr-only">Filter by Employee</label>
                        <select id="employee_filter" name="employee_id" class="form-control">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->department->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> {{ __('Filter') }}
                    </button>
                    @if(request()->has('employee_id') && request('employee_id') != '')
                        <a href="{{ route('projects.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> Clear Filter
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project Title</th>
                            <th>Assigned To</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td>
                                    {{ $project->project_name }}
                                    @if($project->description)
                                        <small class="d-block text-muted">{{ Str::limit($project->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $project->employee->name }}</td>
                                <td>{{ $project->employee->department->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'primary' : ($project->status == 'On Hold' ? 'warning' : 'secondary')) }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($project->start_date)
                                        <small class="d-block"><strong>Start:</strong> {{ date('M d, Y', strtotime($project->start_date)) }}</small>
                                    @endif
                                    @if($project->end_date)
                                        <small class="d-block"><strong>End:</strong> {{ date('M d, Y', strtotime($project->end_date)) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
@endsection
