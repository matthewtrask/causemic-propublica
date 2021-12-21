<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateCompsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comps', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('CompCurrentOfcrDirectorsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CompCurrentOfcrDirectorsGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CompCurrentOfcrDirectorsGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CompCurrentOfcrDirectorsGrpFundraisingAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherSalariesAndWagesGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherSalariesAndWagesGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherSalariesAndWagesGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OtherSalariesAndWagesGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('comps');
    }
}
