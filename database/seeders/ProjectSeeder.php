<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all employee IDs
        $employeeIds = Employee::pluck('id')->toArray();

        $projects = [
            [
                'project_name' => 'Website Redesign',
                'description' => 'Redesign the company website with modern UI/UX principles',
                'employee_id' => $employeeIds[0], // John Doe (Engineering)
                'task' => 'Implement new responsive design',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'status' => 'In Progress',
            ],
            [
                'project_name' => 'Marketing Campaign',
                'description' => 'Q3 digital marketing campaign for new product launch',
                'employee_id' => $employeeIds[1], // Jane Smith (Marketing)
                'task' => 'Create social media content calendar',
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addMonths(3),
                'status' => 'Not Started',
            ],
            [
                'project_name' => 'Financial Audit',
                'description' => 'Annual financial audit and reporting',
                'employee_id' => $employeeIds[2], // Michael Johnson (Finance)
                'task' => 'Prepare Q2 financial statements',
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addWeeks(2),
                'status' => 'In Progress',
            ],
            [
                'project_name' => 'Employee Training Program',
                'description' => 'Develop new employee onboarding and training program',
                'employee_id' => $employeeIds[3], // Emily Brown (HR)
                'task' => 'Create training materials',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'status' => 'In Progress',
            ],
            [
                'project_name' => 'Supply Chain Optimization',
                'description' => 'Improve efficiency in the supply chain process',
                'employee_id' => $employeeIds[4], // David Wilson (Operations)
                'task' => 'Analyze current bottlenecks',
                'start_date' => Carbon::now()->addWeeks(1),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'Not Started',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
