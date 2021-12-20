<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'business_cd',
        'total_revenue_amt',
        'exclusion_amt',
    ];
}
