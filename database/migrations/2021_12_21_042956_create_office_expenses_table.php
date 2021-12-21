<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateOfficeExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('OfficeExpensesGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OfficeExpensesGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OfficeExpensesGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OfficeExpensesGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('office_expenses');
    }
}
