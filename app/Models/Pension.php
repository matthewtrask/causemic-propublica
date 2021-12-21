<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'pension_plan_contributions_grp_total_amt',
        'pension_plan_contributions_grp_program_services_amt',
        'pension_plan_contributions_grp_management_and_general_amt',
        'pension_plan_contributions_grp_fundraising_amt',
    ];
}
