<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'advertising_grp_total_amt',
        'advertising_grp_program_services_amt',
        'advertising_grp_management_and_general_amt',
        'advertising_grp_fundraising_amt',
    ];
}
