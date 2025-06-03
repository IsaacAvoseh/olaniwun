<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Engineering'],
            ['name' => 'Marketing'],
            ['name' => 'Finance'],
            ['name' => 'Human Resources'],
            ['name' => 'Operations'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
