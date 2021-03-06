<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'pdf_link_1',
        'pdf_link_2',
        'pdf_link_3',
        'pdf_link_4'
    ];
}
