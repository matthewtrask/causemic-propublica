<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationTech extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'information_technology_grp_total_amt',
        'information_technology_grp_program_services_amt',
        'information_technology_grp_fundraising_amt'
    ];
}
