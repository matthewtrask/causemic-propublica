<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateYearlyFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yearly_financials', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string(Str::lower(Str::snake('PyContributionsGrantsAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyContributionsGrantsAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyProgramServiceRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyProgramServiceRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyInvestmentIncomeAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyInvestmentIncomeAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyOtherRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyOtherRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyTotalRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyTotalRevenueAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyGrantsAndSimilarPaidAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyGrantsAndSimilarPaidAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyBenefitsPaidToMembersAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyBenefitsPaidToMembersAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PySalariesCompEmpBnftPaidAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CySalariesCompEmpBnftPaidAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyTotalProfFndrsngExpnsAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyTotalProfFndrsngExpnsAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyTotalFundraisingExpenseAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyOtherExpensesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyOtherExpensesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyTotalExpensesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyTotalExpensesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('PyRevenuesLessExpensesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('CyRevenuesLessExpensesAmt', '_')))->nullable();
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
        Schema::dropIfExists('yearly_financials');
    }
}

//  "IRS990-PYContributionsGrantsAmt" => "N/A"
//  "IRS990-CYContributionsGrantsAmt" => "N/A"

//  "IRS990-PYProgramServiceRevenueAmt" => "N/A"
//  "IRS990-CYProgramServiceRevenueAmt" => "N/A"

//  "IRS990-PYInvestmentIncomeAmt" => "N/A"
//  "IRS990-CYInvestmentIncomeAmt" => "N/A"
//  "IRS990-PYOtherRevenueAmt" => "N/A"
//  "IRS990-CYOtherRevenueAmt" => "N/A"
//  "IRS990-PYTotalRevenueAmt" => "N/A"
//  "IRS990-CYTotalRevenueAmt" => "N/A"

//  "IRS990-PYGrantsAndSimilarPaidAmt" => "N/A"
//  "IRS990-CYGrantsAndSimilarPaidAmt" => "N/A"

//  "IRS990-PYBenefitsPaidToMembersAmt" => "N/A"
//  "IRS990-CYBenefitsPaidToMembersAmt" => "N/A"

//  "IRS990-PYSalariesCompEmpBnftPaidAmt" => "N/A"
//  "IRS990-CYSalariesCompEmpBnftPaidAmt" => "N/A"

//  "IRS990-PYTotalProfFndrsngExpnsAmt" => "N/A"
//  "IRS990-CYTotalProfFndrsngExpnsAmt" => "N/A"
//  "IRS990-CYTotalFundraisingExpenseAmt" => "N/A"
//  "IRS990-PYOtherExpensesAmt" => "N/A"
//  "IRS990-CYOtherExpensesAmt" => "N/A"
//  "IRS990-PYTotalExpensesAmt" => "N/A"
//  "IRS990-CYTotalExpensesAmt" => "N/A"
//  "IRS990-PYRevenuesLessExpensesAmt" => "N/A"
//  "IRS990-CYRevenuesLessExpensesAmt" => "N/A"
