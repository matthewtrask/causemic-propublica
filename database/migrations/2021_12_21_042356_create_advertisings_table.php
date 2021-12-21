<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateAdvertisingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisings', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('AdvertisingGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('AdvertisingGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('AdvertisingGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('AdvertisingGrpFundraisingAmt', '_')))->nullable();
            $table->timestamps();
        });
    }


    //  "IRS990-AdvertisingGrp-TotalAmt" => "N/A"
//  "IRS990-AdvertisingGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-AdvertisingGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-AdvertisingGrp-FundraisingAmt" => "N/A"

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisings');
    }
}
