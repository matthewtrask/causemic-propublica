<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeprecationDepletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'depreciation_depletion_grp_total_amt',
        'depreciation_depletion_grp_program_services_amt',
        'depreciation_depletion_grp_management_and_general_amt',
        'depreciation_depletion_grp_fundraising_amt'
    ];
}
