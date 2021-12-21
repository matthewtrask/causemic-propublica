<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateAllOtherExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_other_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('AllOtherExpensesGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('AllOtherExpensesGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('AllOtherExpensesGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('AllOtherExpensesGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('other_expenses');
    }
}
