<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_name',
        'description',
        'employee_id',
        'task',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Get the employee that the project is assigned to.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
