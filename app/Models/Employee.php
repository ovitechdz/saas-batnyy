<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'farm_id',
        'user_id',
        'role_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'national_id',
        'hire_date',
        'termination_date',
        'base_salary',
        'salary_frequency',
        'status',
    ];

    //
}
