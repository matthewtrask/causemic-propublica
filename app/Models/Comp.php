<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comp extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'comp_current_ofcr_directors_grp_total_amt',
        'comp_current_ofcr_directors_grp_program_services_amt',
        'comp_current_ofcr_directors_grp_management_and_general_amt',
        'comp_current_ofcr_directors_grp_fundraising_amt',
        'other_salaries_and_wages_grp_total_amt',
        'other_salaries_and_wages_grp_program_services_amt',
        'other_salaries_and_wages_grp_management_and_general_amt',
        'other_salaries_and_wages_grp_fundraising_amt',
    ];
}
