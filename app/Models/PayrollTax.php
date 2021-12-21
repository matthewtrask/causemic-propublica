<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'payroll_taxes_grp_total_amt',
        'payroll_taxes_grp_program_services_amt',
        'payroll_taxes_grp_management_and_general_amt',
        'payroll_taxes_grp_fundraising_amt',
    ];
}
