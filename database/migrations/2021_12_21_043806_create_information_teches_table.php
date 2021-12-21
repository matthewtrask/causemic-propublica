<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateInformationTechesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_teches', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('InformationTechnologyGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('InformationTechnologyGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('InformationTechnologyGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('information_teches');
    }
}
