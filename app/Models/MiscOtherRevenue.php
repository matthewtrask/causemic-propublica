<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiscOtherRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'desc',
        'business_cd',
        'total_revenue_column_amt',
    ];
}
