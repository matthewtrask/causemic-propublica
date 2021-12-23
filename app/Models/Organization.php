<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'propublica_url',
        'ein',
        'tax_year'
    ];

    public function pdfLinks(): HasOne
    {
        return $this->hasOne(Pdf::class);
    }

    public function otherRevenues(): HasMany
    {
        return $this->hasMany(OtherRevenue::class);
    }

    public function organizationMembers(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function notables(): HasOne
    {
        return $this->hasOne(Notable::class);
    }

    public function yearlyFinancial(): HasOne
    {
        return $this->hasOne(YearlyFinancial::class);
    }

    public function miscFinancial(): HasOne
    {
        return $this->hasOne(MiscFinancial::class);
    }

    public function miscOtherFinancial(): HasMany
    {
        return $this->hasMany(MiscOtherRevenue::class);
    }

    public function totalRevenue(): HasOne
    {
        return $this->hasOne(TotalRevenue::class);
    }

    public function grant(): HasOne
    {
        return $this->hasOne(Grant::class);
    }

    public function comp(): HasOne
    {
        return $this->hasOne(Comp::class);
    }

    public function otherExpenses(): HasMany
    {
        return $this->hasMany(OtherExpense::class);
    }
}
