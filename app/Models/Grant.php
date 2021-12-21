<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'grants_to_domestic_orgs_grp_total_amt',
        'grants_to_domestic_orgs_grp_program_services_amt',
        'grants_to_domestic_individuals_grp_total_amt',
        'grants_to_domestic_individuals_grp_program_services_amt',
        'foreign_grants_grp_total_amt',
        'foreign_grants_grp_program_services_amt',
    ];
}
