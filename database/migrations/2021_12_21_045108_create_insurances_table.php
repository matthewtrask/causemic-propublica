<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('InsuranceGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('InsuranceGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('InsuranceGrpManagementAndGeneralAmt', '_')))->nullable();
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
        Schema::dropIfExists('insurances');
    }
}
