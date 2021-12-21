<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateDeprecationDepletionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deprecation_depletions', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('DepreciationDepletionGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('DepreciationDepletionGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('DepreciationDepletionGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('DepreciationDepletionGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('deprecation_depletions');
    }
}
