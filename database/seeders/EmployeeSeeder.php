<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all department IDs
        $departmentIds = Department::pluck('id')->toArray();

        $employees = [
            [
                'name' => 'John Doe',
                'department_id' => $departmentIds[0], // Engineering
                'salary' => 85000,
            ],
            [
                'name' => 'Jane Smith',
                'department_id' => $departmentIds[1], // Marketing
                'salary' => 75000,
            ],
            [
                'name' => 'Michael Johnson',
                'department_id' => $departmentIds[2], // Finance
                'salary' => 90000,
            ],
            [
                'name' => 'Emily Brown',
                'department_id' => $departmentIds[3], // HR
                'salary' => 70000,
            ],
            [
                'name' => 'David Wilson',
                'department_id' => $departmentIds[4], // Operations
                'salary' => 80000,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
