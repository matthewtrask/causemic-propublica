<?php

namespace App\Console\Commands;

use App\Models\Classification;
use App\Models\Organization;
use App\Models\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportCsvData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from CSV';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rows = SimpleExcelReader::create(base_path('data.csv'))->getRows();

        $rows->each(function(array $row) {
            $organization = Organization::firstOrCreate([
                'propublica_url' => $row['CompanyURL']
            ]);

            $organization->name = Str::title($row['Filer-BusinessName-BusinessNameLine1Txt']);
            $organization->ein = $row['EIN'];
            $organization->city = Str::title(Str::before($row['Address'], ','));
            $organization->state = Str::after(Str::beforeLast($row['Address'], ' '), ',');
            $organization->zip_code = Str::afterLast($row['Address'], ' ');
            $organization->phone = $row['Filer-PhoneNum'];
            $organization->filer_ein = $row['Filer-EIN'];
            $organization->total_revenue = $row['TotalRevenue'];
            $organization->net_income = $row['NetIncome'];
            $organization->exempt_since = Str::after($row['Tax Exempt Since'], 'since ');
            $organization->tax_period_end_date = $row['TaxPeriodEndDt'];
            $organization->tax_year = $row['TaxYear'];
            $organization->return_header_tax_year = $row['ReturnHeader-TaxYr'];
            $organization->tax_code_description = Str::after($row['Nonprofit Tax Code Designation'], ':');
            $organization->principle_officer = Str::title($row['IRS990-PrincipalOfficerNm']);
            $organization->classifications = $row['Classification'];
            $organization->mission_statement = Str::title($row['IRS990-MissionDesc']);
            $organization->total_volunteer_count = $row['IRS990-TotalVolunteersCnt'];
            $organization->total_functional_expenses = $row['TotalFuntionalExpenses'];
            $organization->gross_receipts_amount = $row['IRS990-GrossReceiptsAmt'];
            $organization->organization_501c3_ind = $row['IRS990-Organization501c3Ind'];
            $organization->website_address_txt = $row['IRS990-WebsiteAddressTxt'];
            $organization->type_of_organization = $row['IRS990-TypeOfOrganizationCorpInd'];
            $organization->formation_year = $row['IRS990-FormationYr'];
            $organization->voting_members_governing_body_count = $row['IRS990-VotingMembersGoverningBodyCnt'];
            $organization->voting_members_independent_count = $row['IRS990-VotingMembersIndependentCnt'];
            $organization->total_employee_count = $row['IRS990-TotalEmployeeCnt'];
            $organization->filter_url = $row['FilterURL'];
            $organization->total_gross_ubi_amt = $row['IRS990-TotalGrossUBIAmt'];
            $organization->financial_data = $row['FinancialData'];
            $organization->net_unrelated_bus_taxable_amt = $row['IRS990-NetUnrelatedBusTxblIncmAmt'];
            $organization->raw_xml = $row['Raw XML'];
            $organization->form_990_part_VII_section_a_grp = $row['Form990PartVIISectionAGrp'];
            $organization->cy_gross_investment_income_170_grp_amt = $row['GrossInvestmentIncome170Grp-CurrentTaxYearAmt'];
            $organization->cy_other_income_170_grp_amt = $row['OtherIncome170Grp-CurrentTaxYearAmt'];
            $organization->save();

            $this->storeNotableAmounts($organization, $row);
            $this->storePdfLinks($organization, $row);
            $this->storeOtherRevenue($organization, $row);
            $this->storeOrganizationMembers($organization, $row);
            $this->storeYearlyFinancialData($organization, $row);
            $this->storeMiscFinancialData($organization, $row);
        });

        return 0;
    }

    private function storePdfLinks(Organization $organization, array $row)
    {
        $organization->pdfLinks()->create([
            'pdf_link_1' => $row['PDFLink_1'],
            'pdf_link_2' => $row['PDFLink_2'],
            'pdf_link_3' => $row['PDFLink_3'],
            'pdf_link_4' => $row['PDFLink_4']
        ]);
    }

    private function storeOtherRevenue(Organization $organization, array $row)
    {
        if ($row['IRS990-OtherRevenueMiscGrp'] !== 'NEF' && $row['IRS990-OtherRevenueMiscGrp'] !== 'N/A') {
            $otherRevenues = explode('},{', $row['IRS990-OtherRevenueMiscGrp']);
            $revenues = [];

            foreach ($otherRevenues as $key => $otherRevenue) {
                // strip out the fake json brackets
                if (Str::contains($otherRevenue, '{')) {
                    $otherRevenue = Str::after($otherRevenue, '{');
                }

                if (Str::contains($otherRevenue, '}')) {
                    $otherRevenue = Str::before($otherRevenue, '}');
                }

                $tmpRevData = explode(', ', $otherRevenue);

                foreach ($tmpRevData as $rev) {
                    if (Str::contains($rev, '=')) {
                        $explodedData = explode('=', $rev);

                        if ($explodedData) {
                            $revenues[$key][$explodedData[0]] = isset($explodedData[1]) ? Str::title($explodedData[1]) : null;
                        }
                    } else {
                        $revenues[$key][$rev] = null;
                    }
                }
            }


            foreach($revenues as $revenue) {
                $organization->otherRevenues()->create([
                    'description' => $revenue['Desc'] ?? null,
                    'business_cd' => $revenue['BusinessCd'] ?? null,
                    'total_revenue_amt' => $revenue['TotalRevenueColumnAmt'] ?? null,
                    'exclusion_amt' => $revenue['ExclusionAmt'] ?? null,
                ]);
            }
        }

        if ($row['IRS990-OtherRevenueMiscGrp'] === 'NEF') {
            $organization->otherRevenues()->create([
                'description' => 'NEF',
                'business_cd' => 'NEF',
                'total_revenue_amt' => 'NEF',
                'exclusion_amt' => 'NEF',
            ]);
        }

        if ($row['IRS990-OtherRevenueMiscGrp'] === 'N/A') {
            $organization->otherRevenues()->create([
                'description' => 'N/A',
                'business_cd' => 'N/A',
                'total_revenue_amt' => 'N/A',
                'exclusion_amt' => 'N/A',
            ]);
        }
    }

    private function storeOrganizationMembers(Organization $organization, array $row)
    {
        if ($row['Form990PartVIISectionAGrp'] !== 'NEF' && $row['Form990PartVIISectionAGrp'] !== 'N/A') {
            $reportMembers = explode('},{', $row['Form990PartVIISectionAGrp']);
            $boardMembers = [];

            foreach ($reportMembers as $key => $member) {
                // strip out the fake json brackets
                if (Str::contains($member, '{')) {
                    $member = Str::after($member, '{');
                }

                if (Str::contains($member, '}')) {
                    $member = Str::before($member, '}');
                }

                $tmpMemberData = explode(', ', $member);

                foreach ($tmpMemberData as $person) {
                    if (Str::contains($person, '=')) {
                        $explodedData = explode('=', $person);

                        if ($explodedData) {
                            $boardMembers[$key][$explodedData[0]] = isset($explodedData[1]) ? Str::title($explodedData[1]) : null;
                        }
                    } else {
                        $boardMembers[$key][$person] = null;
                    }
                }

            }

            foreach ($boardMembers as $boardMember) {
                $organization->organizationMembers()->create([
                    'person_name' => $boardMember['PersonNm'] ?? null,
                    'title' => $boardMember['TitleTxt'] ?? null,
                    'reportable_comp_amt_from_org' => $boardMember['ReportableCompFromOrgAmt'] ?? null,
                    'other_comp_amt' => $boardMember['OtherCompensationAmt'] ?? null,
                ]);
            }
        }

        if ($row['Form990PartVIISectionAGrp'] === 'NEF') {
            $organization->organizationMembers()->create([
                'person_name' => 'NEF',
                'title' => 'NEF',
                'reportable_comp_amt_from_org' => 'NEF',
                'other_comp_amt' => 'NEF',
            ]);
        }

        if ($row['Form990PartVIISectionAGrp'] === 'N/A') {
            $organization->organizationMembers()->create([
                'person_name' => 'N/A',
                'title' => 'N/A',
                'reportable_comp_amt_from_org' => 'N/A',
                'other_comp_amt' => 'N/A',
            ]);
        }
    }

    private function storeNotableAmounts(Organization $organization, array $row)
    {
        $organization->notables()->create([
            'notable_contribution' => $row['NotableContributions'],
            'notable_program_services' => $row['NotableProgramServices'],
            'notable_investment_income' => $row['NotableInvestmentIncome'],
            'notable_net_fundraising' => $row['NotableNetFundraising'],
            'notable_sales_of_assets' => $row['NotableSalesofAssets'],
            'notable_net_inventory_of_sales' => $row['NotableNetInventorySales'],
            'other_revenue' => $row['OtherRevenue'],
            'other_total_assets' => $row['OtherTotalAssets'],
            'other_total_liabilities' => $row['OtherTotalLiabilities'],
            'other_net_assets' => $row['OtherNetAssets'],
        ]);
    }

    private function storeYearlyFinancialData(Organization $organization, array $row)
    {
        $organization->yearlyFinancial()->create([
            'py_contributions_grants_amt' => $row['IRS990-PYContributionsGrantsAmt'],
            'cy_contributions_grants_amt' => $row['IRS990-CYContributionsGrantsAmt'],
            'py_program_service_revenue_amt' => $row['IRS990-PYProgramServiceRevenueAmt'],
            'cy_program_service_revenue_amt' => $row['IRS990-CYProgramServiceRevenueAmt'],
            'py_investment_income_amt' => $row['IRS990-PYInvestmentIncomeAmt'],
            'cy_investment_income_amt' => $row['IRS990-CYInvestmentIncomeAmt'],
            'py_other_revenue_amt' => $row['IRS990-PYOtherRevenueAmt'],
            'cy_other_revenue_amt' => $row['IRS990-CYOtherRevenueAmt'],
            'py_total_revenue_amt' => $row['IRS990-PYTotalRevenueAmt'],
            'cy_total_revenue_amt' => $row['IRS990-CYTotalRevenueAmt'],
            'py_grants_and_similar_paid_amt' => $row['IRS990-PYGrantsAndSimilarPaidAmt'],
            'cy_grants_and_similar_paid_amt' => $row['IRS990-CYGrantsAndSimilarPaidAmt'],
            'py_benefits_paid_to_members_amt' => $row['IRS990-PYBenefitsPaidToMembersAmt'],
            'cy_benefits_paid_to_members_amt' => $row['IRS990-CYBenefitsPaidToMembersAmt'],
            'py_salaries_comp_emp_bnft_paid_amt' => $row['IRS990-PYSalariesCompEmpBnftPaidAmt'],
            'cy_salaries_comp_emp_bnft_paid_amt' => $row['IRS990-CYSalariesCompEmpBnftPaidAmt'],
            'py_total_prof_fndrsng_expns_amt' => $row['IRS990-PYTotalProfFndrsngExpnsAmt'],
            'cy_total_prof_fndrsng_expns_amt' => $row['IRS990-CYTotalProfFndrsngExpnsAmt'],
            'cy_total_fundraising_expense_amt' => $row['IRS990-CYTotalFundraisingExpenseAmt'],
            'py_other_expenses_amt' => $row['IRS990-PYOtherExpensesAmt'],
            'cy_other_expenses_amt' => $row['IRS990-CYOtherExpensesAmt'],
            'py_total_expenses_amt' => $row['IRS990-PYTotalExpensesAmt'],
            'cy_total_expenses_amt' => $row['IRS990-CYTotalExpensesAmt'],
            'py_revenues_less_expenses_amt' => $row['IRS990-PYRevenuesLessExpensesAmt'],
            'cy_revenues_less_expenses_amt' => $row['IRS990-CYRevenuesLessExpensesAmt'],
        ]);
    }

    private function storeMiscFinancialData(Organization $organization, array $row)
    {
        $organization->miscFinancial()->create([
            'total_assets_boy_amt' => $row['IRS990-TotalAssetsBOYAmt'],
            'total_assets_eoy_amt' => $row['IRS990-TotalAssetsEOYAmt'],
            'total_liabilities_boy_amt' => $row['IRS990-TotalLiabilitiesBOYAmt'],
            'total_liabilities_eoy_amt' => $row['IRS990-TotalLiabilitiesEOYAmt'],
            'net_assets_or_fund_balances_boy_amt' => $row['IRS990-NetAssetsOrFundBalancesBOYAmt'],
            'net_assets_or_fund_balances_eoy_amt' => $row['IRS990-NetAssetsOrFundBalancesEOYAmt'],
            'mission_desc' => Str::title($row['IRS990-MissionDesc']),
            'professional_fundraising_ind' => $row['IRS990-ProfessionalFundraisingInd'],
            'tax_exempt_bonds_ind' => $row['IRS990-TaxExemptBondsInd'],
            'contractor_compensation_grp' => $row['IRS990-TaxExemptBondsInd'],
            'cntrct_rcvd_greater_than_100k_cnt' => $row['IRS990-CntrctRcvdGreaterThan100KCnt'],
            'fundraising_amt' => $row['IRS990-FundraisingAmt'],
            'all_other_contributions_amt' => $row['IRS990-AllOtherContributionsAmt'],
            'noncash_contributions_amt' => $row['IRS990-NoncashContributionsAmt'],
            'total_contributions_amt' => $row['IRS990-TotalContributionsAmt'],
            'investment_income_grp_total_revenue_column_amt' => $row['IRS990-InvestmentIncomeGrp-TotalRevenueColumnAmt'],
            'investment_income_grp_exclusion_amt' => $row['IRS990-InvestmentIncomeGrp-ExclusionAmt'],
            'gross_amount_sales_assets_grp_securities_amt' => $row['IRS990-GrossAmountSalesAssetsGrp-SecuritiesAmt'],
            'less_cost_oth_basis_sales_expnss_grp_securities_amt' => $row['IRS990-LessCostOthBasisSalesExpnssGrp-SecuritiesAmt'],
            'gain_or_loss_grp_securities_amt' => $row['IRS990-GainOrLossGrp-SecuritiesAmt'],
            'net_gain_or_loss_investments_grp_total_revenue_column_amt' => $row['IRS990-NetGainOrLossInvestmentsGrp-TotalRevenueColumnAmt'],
            'net_gain_or_loss_investments_grp_exclusion_amt' => $row['IRS990-NetGainOrLossInvestmentsGrp-ExclusionAmt'],
            'fundraising_gross_income_amt' => $row['IRS990-FundraisingGrossIncomeAmt'],
            'fundraising_direct_expenses_amt' => $row['IRS990-FundraisingDirectExpensesAmt'],
            'contri_rpt_fundraising_event_amt' => $row['IRS990-ContriRptFundraisingEventAmt'],
            'net_incm_from_fundraising_evt_grp_total_revenue_column_amt' => $row['IRS990-NetIncmFromFundraisingEvtGrp-TotalRevenueColumnAmt'],
            'net_incm_from_fundraising_evt_grp_exclusion_amt' => $row['IRS990-NetIncmFromFundraisingEvtGrp-ExclusionAmt'],
            'gross_sales_of_inventory_amt' => $row['IRS990-GrossSalesOfInventoryAmt'],
            'cost_of_goods_sold_amt' => $row['IRS990-CostOfGoodsSoldAmt'],
            'net_income_or_loss_grp_total_revenue_column_amt' => $row['IRS990-NetIncomeOrLossGrp-TotalRevenueColumnAmt'],
            'net_income_or_loss_grp_related_or_exempt_func_income_amt' => $row['IRS990-NetIncomeOrLossGrp-RelatedOrExemptFuncIncomeAmt'],
        ]);
    }
}

//"CompanyURL" => "https://projects.propublica.org/nonprofits/organizations/526055662"
//  "ReturnTs" => "2020-04-17T09:24:03-07:00"
//  "Address" => "DALLAS, TX 75382-3177"
//  "TaxPeriodEndDt" => "2019-12-31"
//  "Tax Exempt Since" => "Tax-exempt since Dec. 1956"
//  "Filer-EIN" => "526055662"
//  "EIN" => "52-6055662"
//  "Filer-BusinessName-BusinessNameLine1Txt" => "CIVIL AVIATION MEDICAL ASSOCIATION"
//  "Classification" => "Diseases, Disorders, Medical Disciplines N.E.C."
//  "ReturnHeader-TaxYr" => "2019"
//  "Nonprofit Tax Code Designation" => "Defined as: Business leagues, chambers of commerce, real estate boards, etc, created for the improvement of business conditions."
//  "Filer-PhoneNum" => "7704870100"
//  "TaxYear" => "2019"
//  "IRS990-PrincipalOfficerNm" => "N/A"
//  "TotalRevenue" => "103216"
//  "IRS990-USAddress-AddressLine1Txt" => "N/A"
//  "TotalFuntionalExpenses" => "97111"
//  "IRS990-USAddress-CityNm" => "N/A"
//  "NetIncome" => "6105"
//  "IRS990-USAddress-StateAbbreviationCd" => "N/A"
//  "NotableContributions" => "500"
//  "IRS990-USAddress-ZIPCd" => "N/A"
//  "NotableProgramServices" => "73513"
//  "IRS990-GrossReceiptsAmt" => "103216"
//  "NotableInvestmentIncome" => "135"
//  "IRS990-Organization501c3Ind" => "N/A"
//  "NotableNetFundraising" => "0"
//  "IRS990-WebsiteAddressTxt" => "N/A"
//  "NotableSalesofAssets" => "0"
//  "IRS990-TypeOfOrganizationCorpInd" => "N/A"
//  "NotableNetInventorySales" => "33"
//  "IRS990-FormationYr" => "N/A"
//  "OtherRevenue" => "0"
//  "IRS990-ActivityOrMissionDesc" => "N/A"
//  "OtherTotalAssets" => "99766"
//  "IRS990-VotingMembersGoverningBodyCnt" => "N/A"
//  "OtherTotalLiabilities" => "1650"
//  "IRS990-VotingMembersIndependentCnt" => "N/A"
//  "OtherNetAssets" => "98116"
//  "IRS990-TotalEmployeeCnt" => "N/A"
//  "PDFLink_1" => "https://projects.propublica.org/nonprofits/display_990/526055662/12_2020_prefixes_46-54%2F526055662_201912_990EO_2020121617482051"
//  "PDFLink_2" => "N/A"
//  "PDFLink_3" => "N/A"
//  "PDFLink_4" => "N/A"
//  "IRS990-TotalVolunteersCnt" => "N/A"
//  "FilterURL" => "https://projects.propublica.org/nonprofits/search?utf8=%E2%9C%93&q=&state%5Bid%5D=TX&ntee%5Bid%5D=4&c_code%5Bid%5D=6"
//  "IRS990-TotalGrossUBIAmt" => "N/A"
//  "FinancialData" => "1"
//  "IRS990-NetUnrelatedBusTxblIncmAmt" => "N/A"
//  "Raw XML" => "1"
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
//  "IRS990-TotalAssetsBOYAmt" => "N/A"
//  "IRS990-TotalAssetsEOYAmt" => "N/A"
//  "IRS990-TotalLiabilitiesBOYAmt" => "N/A"
//  "IRS990-TotalLiabilitiesEOYAmt" => "N/A"
//  "IRS990-NetAssetsOrFundBalancesBOYAmt" => "92011"
//  "IRS990-NetAssetsOrFundBalancesEOYAmt" => "98116"
//  "IRS990-MissionDesc" => "N/A"
//  "IRS990-ProfessionalFundraisingInd" => "N/A"
//  "IRS990-TaxExemptBondsInd" => "N/A"
//  "Form990PartVIISectionAGrp" => "N/A"
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
//  "IRS990-TotalRevenueGrp-TotalRevenueColumnAmt" => "N/A"
//  "IRS990-TotalRevenueGrp-RelatedOrExemptFuncIncomeAmt" => "N/A"
//  "IRS990-TotalRevenueGrp-UnrelatedBusinessRevenueAmt" => "N/A"
//  "IRS990-TotalRevenueGrp-ExclusionAmt" => "N/A"
//  "IRS990-OtherRevenueMiscGrp" => "N/A"
//  "IRS990-GrantsToDomesticOrgsGrp-TotalAmt" => "N/A"
//  "IRS990-GrantsToDomesticOrgsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-GrantsToDomesticIndividualsGrp-TotalAmt" => "N/A"
//  "IRS990-GrantsToDomesticIndividualsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-ForeignGrantsGrp-TotalAmt" => "N/A"
//  "IRS990-ForeignGrantsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-CompCurrentOfcrDirectorsGrp-TotalAmt" => "N/A"
//  "IRS990-CompCurrentOfcrDirectorsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-CompCurrentOfcrDirectorsGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-CompCurrentOfcrDirectorsGrp-FundraisingAmt" => "N/A"
//  "IRS990-OtherSalariesAndWagesGrp-TotalAmt" => "N/A"
//  "IRS990-OtherSalariesAndWagesGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-OtherSalariesAndWagesGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-OtherSalariesAndWagesGrp-FundraisingAmt" => "N/A"
//  "IRS990-PensionPlanContributionsGrp-TotalAmt" => "N/A"
//  "IRS990-PensionPlanContributionsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-PensionPlanContributionsGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-PensionPlanContributionsGrp-FundraisingAmt" => "N/A"
//  "IRS990-OtherEmployeeBenefitsGrp-TotalAmt" => "N/A"
//  "IRS990-OtherEmployeeBenefitsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-OtherEmployeeBenefitsGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-OtherEmployeeBenefitsGrp-FundraisingAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-TotalAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-PayrollTaxesGrp-FundraisingAmt" => "N/A"
//  "IRS990-FeesForServicesAccountingGrp-TotalAmt" => "N/A"
//  "IRS990-FeesForServicesAccountingGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-TotalAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-FeesForServicesOtherGrp-FundraisingAmt" => "N/A"
//  "IRS990-AdvertisingGrp-TotalAmt" => "N/A"
//  "IRS990-AdvertisingGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-AdvertisingGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-AdvertisingGrp-FundraisingAmt" => "N/A"
//  "IRS990-OfficeExpensesGrp-TotalAmt" => "N/A"
//  "IRS990-OfficeExpensesGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-OfficeExpensesGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-OfficeExpensesGrp-FundraisingAmt" => "N/A"
//  "IRS990-InformationTechnologyGrp-TotalAmt" => "N/A"
//  "IRS990-InformationTechnologyGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-InformationTechnologyGrp-FundraisingAmt" => "N/A"
//  "IRS990-OccupancyGrp-TotalAmt" => "N/A"
//  "IRS990-OccupancyGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-OccupancyGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-OccupancyGrp-FundraisingAmt" => "N/A"
//  "IRS990-ConferencesMeetingsGrp-TotalAmt" => "N/A"
//  "IRS990-ConferencesMeetingsGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-ConferencesMeetingsGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-ConferencesMeetingsGrp-FundraisingAmt" => "N/A"
//  "IRS990-DepreciationDepletionGrp-TotalAmt" => "N/A"
//  "IRS990-DepreciationDepletionGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-DepreciationDepletionGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-DepreciationDepletionGrp-FundraisingAmt" => "N/A"
//  "IRS990-InsuranceGrp-TotalAmt" => "N/A"
//  "IRS990-InsuranceGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-InsuranceGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-OtherExpensesGrp" => "N/A"
//  "IRS990-AllOtherExpensesGrp-TotalAmt" => "N/A"
//  "IRS990-AllOtherExpensesGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-AllOtherExpensesGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-AllOtherExpensesGrp-FundraisingAmt" => "N/A"
//  "IRS990-TotalFunctionalExpensesGrp-TotalAmt" => "N/A"
//  "IRS990-TotalFunctionalExpensesGrp-ProgramServicesAmt" => "N/A"
//  "IRS990-TotalFunctionalExpensesGrp-ManagementAndGeneralAmt" => "N/A"
//  "IRS990-TotalFunctionalExpensesGrp-FundraisingAmt" => "N/A"
//  "GrossInvestmentIncome170Grp-CurrentTaxYearAmt" => "N/A"
//  "OtherIncome170Grp-CurrentTaxYearAmt" => "N/A"
//  "RecipientTable" => "N/A"
//  "error" => ""
