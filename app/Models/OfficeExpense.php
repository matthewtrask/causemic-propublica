<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'office_expenses_grp_total_amt',
        'office_expenses_grp_program_services_amt',
        'office_expenses_grp_management_and_general_amt',
        'office_expenses_grp_fundraising_amt'
    ];
}
