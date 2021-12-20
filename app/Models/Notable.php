<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notable extends Model
{
    use HasFactory;

    protected $fillable = [
        'notable_contribution',
        'notable_program_services',
        'notable_investment_income',
        'notable_net_fundraising',
        'notable_sales_of_assets',
        'notable_net_inventory_of_sales',
        'other_revenue',
        'other_total_assets',
        'other_total_liabilities',
        'other_net_assets',
    ];
}
