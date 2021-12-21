<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateTotalRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_revenues', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('TotalRevenueColumnAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('RelatedOrExemptFuncIncomeAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('UnrelatedBusinessRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('ExclusionAmt', '_')))->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_revenues');
    }
}
