<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'other_employee_benefits_grp_total_amt',
        'other_employee_benefits_grp_program_services_amt',
        'other_employee_benefits_grp_management_and_general_amt',
        'other_employee_benefits_grp_fundraising_amt'
    ];
}
