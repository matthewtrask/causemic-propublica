<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateMiscFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('misc_financials', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string(Str::lower(Str::snake('TotalAssetsBoyAmt', '_')));
            $table->string(Str::lower(Str::snake('TotalAssetsEoyAmt', '_')));
            $table->string(Str::lower(Str::snake('TotalLiabilitiesBoyAmt', '_')));
            $table->string(Str::lower(Str::snake('TotalLiabilitiesEoyAmt', '_')));
            $table->string(Str::lower(Str::snake('NetAssetsOrFundBalancesBoyAmt', '_')));
            $table->string(Str::lower(Str::snake('NetAssetsOrFundBalancesEoyAmt', '_')));
            $table->longText(Str::lower(Str::snake('MissionDesc', '_')));
            $table->string(Str::lower(Str::snake('ProfessionalFundraisingInd', '_')));
            $table->string(Str::lower(Str::snake('TaxExemptBondsInd', '_')));
            $table->string(Str::lower(Str::snake('ContractorCompensationGrp', '_')));
            $table->string(Str::lower(Str::snake('CntrctRcvdGreaterThan_100kCnt', '_')));
            $table->string(Str::lower(Str::snake('FundraisingAmt', '_')));
            $table->string(Str::lower(Str::snake('AllOtherContributionsAmt', '_')));
            $table->string(Str::lower(Str::snake('NoncashContributionsAmt', '_')));
            $table->string(Str::lower(Str::snake('TotalContributionsAmt', '_')));
            $table->string(Str::lower(Str::snake('InvestmentIncomeGrpTotalRevenueColumnAmt', '_')));
            $table->string(Str::lower(Str::snake('InvestmentIncomeGrpExclusionAmt', '_')));
            $table->string(Str::lower(Str::snake('GrossAmountSalesAssetsGrpSecuritiesAmt', '_')));
            $table->string(Str::lower(Str::snake('LessCostOthBasisSalesExpnssGrpSecuritiesAmt', '_')));
            $table->string(Str::lower(Str::snake('GainOrLossGrpSecuritiesAmt', '_')));
            $table->string(Str::lower(Str::snake('NetGainOrLossInvestmentsGrpTotalRevenueColumnAmt', '_')));
            $table->string(Str::lower(Str::snake('NetGainOrLossInvestmentsGrpExclusionAmt', '_')));
            $table->string(Str::lower(Str::snake('FundraisingGrossIncomeAmt', '_')));
            $table->string(Str::lower(Str::snake('ContriRptFundraisingEventAmt', '_')));
            $table->string(Str::lower(Str::snake('FundraisingDirectExpensesAmt', '_')));
            $table->string(Str::lower(Str::snake('NetIncmFromFundraisingEvtGrpTotalRevenueColumnAmt', '_')));
            $table->string(Str::lower(Str::snake('NetIncmFromFundraisingEvtGrpExclusionAmt', '_')));
            $table->string(Str::lower(Str::snake('GrossSalesOfInventoryAmt', '_')));
            $table->string(Str::lower(Str::snake('CostOfGoodsSoldAmt', '_')));
            $table->string(Str::lower(Str::snake('NetIncomeOrLossGrpTotalRevenueColumnAmt', '_')));
            $table->string(Str::lower(Str::snake('NetIncomeOrLossGrpRelatedOrExemptFuncIncomeAmt', '_')));
            $table->string(Str::lower(Str::snake('OtherRevenueTotalAmt', '_')));
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
        Schema::dropIfExists('misc_financials');
    }
}


//  "IRS990-TotalAssetsBOYAmt" => "N/A"
//  "IRS990-TotalAssetsEOYAmt" => "N/A"
//  "IRS990-TotalLiabilitiesBOYAmt" => "N/A"
//  "IRS990-TotalLiabilitiesEOYAmt" => "N/A"
//  "IRS990-NetAssetsOrFundBalancesBOYAmt" => "92011"
//  "IRS990-NetAssetsOrFundBalancesEOYAmt" => "98116"
//  "IRS990-MissionDesc" => "N/A"
//  "IRS990-ProfessionalFundraisingInd" => "N/A"
//  "IRS990-TaxExemptBondsInd" => "N/A"
//  "IRS990-ContractorCompensationGrp" => "N/A"
//  "IRS990-CntrctRcvdGreaterThan100KCnt" => "N/A"
//  "IRS990-FundraisingAmt" => "N/A"
//  "IRS990-AllOtherContributionsAmt" => "N/A"
//  "IRS990-NoncashContributionsAmt" => "N/A"
//  "IRS990-TotalContributionsAmt" => "N/A"
//  "IRS990-InvestmentIncomeGrp-TotalRevenueColumnAmt" => "N/A"
//  "IRS990-InvestmentIncomeGrp-ExclusionAmt" => "N/A"
//  "IRS990-GrossAmountSalesAssetsGrp-SecuritiesAmt" => "N/A"
//  "IRS990-LessCostOthBasisSalesExpnssGrp-SecuritiesAmt" => "N/A"
//  "IRS990-GainOrLossGrp-SecuritiesAmt" => "N/A"
//  "IRS990-NetGainOrLossInvestmentsGrp-TotalRevenueColumnAmt" => "N/A"
//  "IRS990-NetGainOrLossInvestmentsGrp-ExclusionAmt" => "N/A"
//  "IRS990-FundraisingGrossIncomeAmt" => "0"
//  "IRS990-ContriRptFundraisingEventAmt" => "N/A"
//  "IRS990-FundraisingDirectExpensesAmt" => "N/A"
//  "IRS990-NetIncmFromFundraisingEvtGrp-TotalRevenueColumnAmt" => "N/A"
//  "IRS990-NetIncmFromFundraisingEvtGrp-ExclusionAmt" => "N/A"
//  "IRS990-GrossSalesOfInventoryAmt" => "33"
//  "IRS990-CostOfGoodsSoldAmt" => "0"
//  "IRS990-NetIncomeOrLossGrp-TotalRevenueColumnAmt" => "N/A"
//  "IRS990-NetIncomeOrLossGrp-RelatedOrExemptFuncIncomeAmt" => "N/A"
//  "IRS990-OtherRevenueTotalAmt" => "N/A"
//  "IRS990-OtherRevenueMiscGrp" => "N/A"
