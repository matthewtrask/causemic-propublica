<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllOtherExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'all_other_expenses_grp_total_amt',
        'all_other_expenses_grp_program_services_amt',
        'all_other_expenses_grp_management_and_general_amt',
        'all_other_expenses_grp_fundraising_amt'
    ];
}
