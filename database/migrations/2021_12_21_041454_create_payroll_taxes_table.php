<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreatePayrollTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('PayrollTaxesGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PayrollTaxesGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PayrollTaxesGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PayrollTaxesGrpFundraisingAmt', '_')))->nullable();
            $table->timestamps();
        });
    }

    //  "IRS990-PayrollTaxesGrp-TotalAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-FundraisingAmt" => "N/A"

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_taxes');
    }
}
