<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'conferences_meetings_grp_total_amt',
        'conferences_meetings_grp_program_services_amt',
        'conferences_meetings_grp_management_and_general_amt',
        'conferences_meetings_grp_fundraising_amt'
    ];
}
