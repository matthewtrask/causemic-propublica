<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'insurance_grp_total_amt',
        'insurance_grp_program_services_amt',
        'insurance_grp_management_and_general_amt'
    ];
}
