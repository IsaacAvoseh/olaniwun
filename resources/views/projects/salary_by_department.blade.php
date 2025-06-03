@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('Salary Expenditure by Department') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
                <li class="breadcrumb-item active">Salary Expenditure</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Total Salary Expenditure Per Department</h3>
        </div>
        <div class="card-body">
            @if ($departmentSalaries->isEmpty())
                <div class="alert alert-info">
                    <i class="icon fas fa-info"></i> No salary data available.
                </div>
            @else
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Total Salary Expenditure</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departmentSalaries as $salary)
                                        <tr>
                                            <td>{{ $salary->department->name }}</td>
                                            <td>N{{ number_format($salary->total_salary, 2) }}</td>
                                            <td>
                                                <a href="{{ route('departments.show', $salary->department) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View Department
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th>N{{ number_format($departmentSalaries->sum('total_salary'), 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Salary Distribution</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="salaryChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('salaryChart').getContext('2d');
        var salaryChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [@foreach($departmentSalaries as $salary) '{{ $salary->department->name }}', @endforeach],
                datasets: [{
                    label: 'Salary Distribution',
                    data: [@foreach($departmentSalaries as $salary) {{ $salary->total_salary }}, @endforeach],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                return label + ': $' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
