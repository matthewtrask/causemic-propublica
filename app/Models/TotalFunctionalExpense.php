<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalFunctionalExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'total_functional_expenses_grp_total_amt',
        'total_functional_expenses_grp_program_services_amt',
        'total_functional_expenses_grp_management_and_general_amt',
        'total_functional_expenses_grp_fundraising_amt'
    ];
}
