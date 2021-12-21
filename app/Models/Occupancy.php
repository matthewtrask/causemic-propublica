<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'occupancy_grp_total_amt',
        'occupancy_grp_program_services_amt',
        'occupancy_grp_management_and_general_amt',
        'occupancy_grp_fundraising_amt'
    ];
}
