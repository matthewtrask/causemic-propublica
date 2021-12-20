<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiscFinancial extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_assets_boy_amt',
        'total_assets_eoy_amt',
        'total_liabilities_boy_amt',
        'total_liabilities_eoy_amt',
        'net_assets_or_fund_balances_boy_amt',
        'net_assets_or_fund_balances_eoy_amt',
        'mission_desc',
        'professional_fundraising_ind',
        'tax_exempt_bonds_ind',
        'contractor_compensation_grp',
        'cntrct_rcvd_greater_than_100k_cnt',
        'fundraising_amt',
        'all_other_contributions_amt',
        'noncash_contributions_amt',
        'total_contributions_amt',
        'investment_income_grp_total_revenue_column_amt',
        'investment_income_grp_exclusion_amt',
        'gross_amount_sales_assets_grp_securities_amt',
        'less_cost_oth_basis_sales_expnss_grp_securities_amt',
        'gain_or_loss_grp_securities_amt',
        'net_gain_or_loss_investments_grp_total_revenue_column_amt',
        'net_gain_or_loss_investments_grp_exclusion_amt',
        'fundraising_gross_income_amt',
        'contri_rpt_fundraising_event_amt',
        'net_incm_from_fundraising_evt_grp_total_revenue_column_amt',
        'net_incm_from_fundraising_evt_grp_exclusion_amt',
        'gross_sales_of_inventory_amt',
        'cost_of_goods_sold_amt',
        'net_income_or_loss_grp_total_revenue_column_amt',
        'net_income_or_loss_grp_related_or_exempt_func_income_amt',
        'fundraising_direct_expenses_amt'
    ];
}
