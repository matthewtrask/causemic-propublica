<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreatePensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pensions', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('PensionPlanContributionsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PensionPlanContributionsGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PensionPlanContributionsGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PensionPlanContributionsGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('pensions');
    }
}

