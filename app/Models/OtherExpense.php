<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'desc',
        'total_amount',
        'program_services_amt',
    ];
}
