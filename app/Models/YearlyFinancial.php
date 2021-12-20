<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyFinancial extends Model
{
    use HasFactory;

    protected $fillable = [
        'py_contributions_grants_amt',
        'cy_contributions_grants_amt',
        'py_program_service_revenue_amt',
        'cy_program_service_revenue_amt',
        'py_investment_income_amt',
        'cy_investment_income_amt',
        'py_other_revenue_amt',
        'cy_other_revenue_amt',
        'py_total_revenue_amt',
        'cy_total_revenue_amt',
        'py_grants_and_similar_paid_amt',
        'cy_grants_and_similar_paid_amt',
        'py_benefits_paid_to_members_amt',
        'cy_benefits_paid_to_members_amt',
        'py_salaries_comp_emp_bnft_paid_amt',
        'cy_salaries_comp_emp_bnft_paid_amt',
        'py_total_prof_fndrsng_expns_amt',
        'cy_total_prof_fndrsng_expns_amt',
        'cy_total_fundraising_expense_amt',
        'py_other_expenses_amt',
        'cy_other_expenses_amt',
        'py_total_expenses_amt',
        'cy_total_expenses_amt',
        'py_revenues_less_expenses_amt',
        'cy_revenues_less_expenses_amt',
    ];
}
