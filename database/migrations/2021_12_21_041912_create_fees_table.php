<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('FeesForServicesAccountingGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('FeesForServicesAccountingGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('FeesForServicesOtherGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('FeesForServicesOtherGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('FeesForServicesOtherGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('FeesForServicesOtherGrpFundraisingAmt', '_')))->nullable();
            $table->timestamps();
        });
    }

    //  "IRS990-FeesForServicesAccountingGrp-TotalAmt" => "N/A"
//  "IRS990-FeesForServicesAccountingGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-TotalAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-FundraisingAmt" => "N/A"

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
