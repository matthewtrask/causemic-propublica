<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateTotalFunctionalExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_functional_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('TotalFunctionalExpensesGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('TotalFunctionalExpensesGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('TotalFunctionalExpensesGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('TotalFunctionalExpensesGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('total_functional_expenses');
    }
}
