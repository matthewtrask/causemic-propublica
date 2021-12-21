<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'fees_for_services_accounting_grp_total_amt',
        'fees_for_services_accounting_grp_management_and_general_amt',
        'fees_for_services_other_grp_total_amt',
        'fees_for_services_other_grp_program_services_amt',
        'fees_for_services_other_grp_management_and_general_amt',
        'fees_for_services_other_grp_fundraising_amt',
    ];
}
