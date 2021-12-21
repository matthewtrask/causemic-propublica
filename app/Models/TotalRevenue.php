<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'total_revenue_column_amt',
        'related_or_exempt_func_income_amt',
        'unrelated_business_revenue_amt',
        'exclusion_amt',
    ];
}
