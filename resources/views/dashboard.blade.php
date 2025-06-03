@extends('layouts.adminlte')

@section('header')
    <h1>{{ __('Dashboard') }}</h1>
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="row">
        <!-- Department Card -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $departmentCount }}</h3>
                    <p>Total Departments</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('departments.index') }}" class="small-box-footer">View all departments <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Employee Card -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $employeeCount }}</h3>
                    <p>Total Employees</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('employees.index') }}" class="small-box-footer">View all employees <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Project Card -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $projectCount }}</h3>
                    <p>Total Projects</p>
                </div>
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <a href="{{ route('projects.index') }}" class="small-box-footer">View all projects <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Employees</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentEmployees as $employee)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-bold">{{ $employee->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $employee->email }}</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">{{ $employee->department->name }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">No recent employees</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('employees.index') }}">View All Employees</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Projects</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentProjects as $project)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-bold">{{ $project->project_name }}</span>
                                        <br>
                                        <small class="text-muted">Assigned to: {{ $project->employee->name ?? 'Unassigned' }}</small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">No recent projects</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('projects.index') }}">View All Projects</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Any dashboard-specific JavaScript can go here
    });
</script>
@endpush
