<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateEmployeeBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_benefits', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('OtherEmployeeBenefitsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherEmployeeBenefitsGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherEmployeeBenefitsGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherEmployeeBenefitsGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('employee_benefits');
    }
}
