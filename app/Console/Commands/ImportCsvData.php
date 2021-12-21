<?php

namespace App\Console\Commands;

use App\Models\Advertising;
use App\Models\Comp;
use App\Models\ConferenceMeeting;
use App\Models\DeprecationDepletion;
use App\Models\EmployeeBenefit;
use App\Models\Fee;
use App\Models\Grant;
use App\Models\InformationTech;
use App\Models\Insurance;
use App\Models\MiscFinancial;
use App\Models\MiscOtherRevenue;
use App\Models\Notable;
use App\Models\Occupancy;
use App\Models\OfficeExpense;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\AllOtherExpense;
use App\Models\OtherExpense;
use App\Models\OtherRevenue;
use App\Models\PayrollTax;
use App\Models\Pdf;
use App\Models\Pension;
use App\Models\TotalFunctionalExpense;
use App\Models\TotalRevenue;
use App\Models\YearlyFinancial;
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
    protected $signature = 'data:import {file}';

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
        $file = $this->input->getArgument('file');
        $rows = SimpleExcelReader::create(base_path($file))->getRows();

        $rows->each(function(array $row) {
            $organization = Organization::firstOrCreate([
                'propublica_url' => $row['CompanyURL'],
                'ein' => $row['EIN'],
                'tax_year' => $row['TaxYear'],
            ]);

            $organization->name = Str::title($row['Filer-BusinessName-BusinessNameLine1Txt']);
            $organization->city = Str::title(Str::before($row['Address'], ','));
            $organization->state = Str::after(Str::beforeLast($row['Address'], ' '), ',');
            $organization->zip_code = Str::afterLast($row['Address'], ' ');
            $organization->phone = $row['Filer-PhoneNum'];
            $organization->filer_ein = $row['Filer-EIN'];
            $organization->total_revenue = $row['TotalRevenue'];
            $organization->net_income = $row['NetIncome'];
            $organization->exempt_since = Str::after($row['Tax Exempt Since'], 'since ');
            $organization->tax_period_end_date = $row['TaxPeriodEndDt'];
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
            $organization->gross_investment_income_170_grp_current_tax_year_amt = $row['GrossInvestmentIncome170Grp-CurrentTaxYearAmt'];
            $organization->other_income_170_grp_current_tax_year_amt = $row['OtherIncome170Grp-CurrentTaxYearAmt'];
            $organization->recipient_table = $row['RecipientTable'];
            $organization->error = $row['error'];
            $organization->save();

            $this->storeNotableAmounts($organization, $row);
            $this->storePdfLinks($organization, $row);
            $this->storeOtherRevenue($organization, $row);
            $this->storeOrganizationMembers($organization, $row);
            $this->storeYearlyFinancialData($organization, $row);
            $this->storeMiscFinancialData($organization, $row);
            $this->storeMiscOtherFinancialData($organization, $row);
            $this->storeTotalRevenue($organization, $row);
            $this->storeGrantData($organization, $row);
            $this->storeCompData($organization, $row);
            $this->storePensionData($organization, $row);
            $this->storeEmployeeBenefitsData($organization, $row);
            $this->storePayrollTaxData($organization, $row);
            $this->storeFeeData($organization, $row);
            $this->storeAdvertisingData($organization, $row);
            $this->storeOfficeExpenseData($organization, $row);
            $this->storeInformationTechData($organization, $row);
            $this->storeOccupancyData($organization, $row);
            $this->storeConferenceMeetingData($organization, $row);
            $this->storeDeprecationDeletionData($organization, $row);
            $this->storeInsuranceData($organization, $row);
            $this->storeAllOtherExpenseData($organization, $row);
            $this->storeOtherExpenseData($organization, $row);
            $this->storeTotalFunctionalExpenseData($organization, $row);
        });

        return 0;
    }

    private function storePdfLinks(Organization $organization, array $row)
    {
        Pdf::updateOrCreate([
            'organization_id' => $organization->id,
        ], [
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
                OtherRevenue::updateOrCreate([
                    'organization_id' => $organization->id
                ],[
                    'description' => $revenue['Desc'] ?? null,
                    'business_cd' => $revenue['BusinessCd'] ?? null,
                    'total_revenue_amt' => $revenue['TotalRevenueColumnAmt'] ?? null,
                    'exclusion_amt' => $revenue['ExclusionAmt'] ?? null,
                ]);
            }
        }

        if ($row['IRS990-OtherRevenueMiscGrp'] === 'NEF') {
            OtherRevenue::updateOrCreate([
                'organization_id' => $organization->id
            ],[
                'description' => 'NEF',
                'business_cd' => 'NEF',
                'total_revenue_amt' => 'NEF',
                'exclusion_amt' => 'NEF',
            ]);
        }

        if ($row['IRS990-OtherRevenueMiscGrp'] === 'N/A') {
            OtherRevenue::updateOrCreate([
                'organization_id' => $organization->id
            ],[
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
                OrganizationMember::updateOrCreate([
                    'organization_id' => $organization->id
                ],[
                    'person_name' => $boardMember['PersonNm'] ?? null,
                    'title' => $boardMember['TitleTxt'] ?? null,
                    'reportable_comp_amt_from_org' => $boardMember['ReportableCompFromOrgAmt'] ?? null,
                    'other_comp_amt' => $boardMember['OtherCompensationAmt'] ?? null,
                ]);
            }
        }

        if ($row['Form990PartVIISectionAGrp'] === 'NEF') {
            OrganizationMember::updateOrCreate([
                'organization_id' => $organization->id
            ],[
                'person_name' => 'NEF',
                'title' => 'NEF',
                'reportable_comp_amt_from_org' => 'NEF',
                'other_comp_amt' => 'NEF',
            ]);
        }

        if ($row['Form990PartVIISectionAGrp'] === 'N/A') {
            OrganizationMember::updateOrCreate([
                'organization_id' => $organization->id
            ],[
                'person_name' => 'N/A',
                'title' => 'N/A',
                'reportable_comp_amt_from_org' => 'N/A',
                'other_comp_amt' => 'N/A',
            ]);
        }
    }

    private function storeNotableAmounts(Organization $organization, array $row)
    {
        Notable::updateOrCreate([
            'organization_id' => $organization->id
        ],[
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
        YearlyFinancial::updateOrCreate([
            'organization_id' => $organization->id,
        ],[
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
        MiscFinancial::updateOrCreate([
            'organization_id' => $organization->id
        ], [
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
            'other_revenue_total_amt' => $row['IRS990-OtherRevenueTotalAmt'],
        ]);
    }

    private function storeMiscOtherFinancialData(Organization $organization, array $row)
    {
        if ($row['IRS990-OtherRevenueMiscGrp'] !== 'NEF' && $row['IRS990-OtherRevenueMiscGrp'] !== 'N/A') {
            $miscFinancials = explode('},{', $row['IRS990-OtherRevenueMiscGrp']);
            $financials = [];

            foreach ($miscFinancials as $key => $miscFinancial) {
                // strip out the fake json brackets
                if (Str::contains($miscFinancial, '{')) {
                    $miscFinancial = Str::after($miscFinancial, '{');
                }

                if (Str::contains($miscFinancial, '}')) {
                    $miscFinancial = Str::before($miscFinancial, '}');
                }

                $tmpMemberData = explode(', ', $miscFinancial);

                foreach ($tmpMemberData as $person) {
                    if (Str::contains($person, '=')) {
                        $explodedData = explode('=', $person);

                        if ($explodedData) {
                            $financials[$key][$explodedData[0]] = isset($explodedData[1]) ? Str::title($explodedData[1]) : null;
                        }
                    } else {
                        $financials[$key][$person] = null;
                    }
                }
            }


            foreach ($financials as $financial) {
                MiscOtherRevenue::updateOrCreate([
                    'organization_id' => $organization->id
                ],[
                    'desc' => $financial['Desc'] ?? null,
                    'business_cd' => $financial['BusinessCd'] ?? null,
                    'total_revenue_column_amt' => $financial['TotalRevenueColumnAmt'] ?? null,
                ]);
            }
        }

        if ($row['IRS990-OtherRevenueMiscGrp'] === 'NEF') {
            MiscOtherRevenue::firstOrCreate([
                'organization_id' => $organization->id
            ], [
                'desc' => 'NEF',
                'business_cd' => 'NEF',
                'total_revenue_column_amt' => 'NEF',
           ]);
        }

        if ($row['IRS990-OtherRevenueMiscGrp'] === 'N/A') {
            MiscOtherRevenue::updateOrcreate([
                'organization_id' => $organization->id
            ], [
                'desc' => 'N/A',
                'business_cd' => 'N/A',
                'total_revenue_column_amt' => 'N/A',
           ]);
        }
    }

    private function storeTotalRevenue(Organization $organization, array $row)
    {
        TotalRevenue::updateOrCreate([
                'organization_id' => $organization->id
        ], [
            'total_revenue_column_amt' => $row['IRS990-TotalRevenueGrp-TotalRevenueColumnAmt'],
            'related_or_exempt_func_income_amt' => $row['IRS990-TotalRevenueGrp-RelatedOrExemptFuncIncomeAmt'],
            'unrelated_business_revenue_amt' => $row['IRS990-TotalRevenueGrp-UnrelatedBusinessRevenueAmt'],
            'exclusion_amt' => $row['IRS990-TotalRevenueGrp-ExclusionAmt'],
        ]);
    }

    private function storeGrantData(Organization $organization, array $row)
    {
        Grant::updateOrCreate([
            'organization_id' => $organization->id,
        ], [
            'grants_to_domestic_orgs_grp_total_amt' => $row['IRS990-GrantsToDomesticOrgsGrp-TotalAmt'],
            'grants_to_domestic_orgs_grp_program_services_amt' => $row['IRS990-GrantsToDomesticOrgsGrp-ProgramServicesAmt'],
            'grants_to_domestic_individuals_grp_total_amt' => $row['IRS990-GrantsToDomesticIndividualsGrp-TotalAmt'],
            'grants_to_domestic_individuals_grp_program_services_amt' => $row['IRS990-GrantsToDomesticIndividualsGrp-ProgramServicesAmt'],
            'foreign_grants_grp_total_amt' => $row['IRS990-ForeignGrantsGrp-TotalAmt'],
            'foreign_grants_grp_program_services_amt' => $row['IRS990-ForeignGrantsGrp-ProgramServicesAmt'],
        ]);
    }

    private function storeCompData(Organization $organization, array $row)
    {
        Comp::updateOrCreate([
            'organization_id' => $organization->id,
        ], [
            'comp_current_ofcr_directors_grp_total_amt' => $row['IRS990-CompCurrentOfcrDirectorsGrp-TotalAmt'],
            'comp_current_ofcr_directors_grp_program_services_amt' => $row['IRS990-CompCurrentOfcrDirectorsGrp-ProgramServicesAmt'],
            'comp_current_ofcr_directors_grp_management_and_general_amt' => $row['IRS990-CompCurrentOfcrDirectorsGrp-ManagementAndGeneralAmt'],
            'comp_current_ofcr_directors_grp_fundraising_amt' => $row['IRS990-CompCurrentOfcrDirectorsGrp-FundraisingAmt'],
            'other_salaries_and_wages_grp_total_amt' => $row['IRS990-OtherSalariesAndWagesGrp-TotalAmt'],
            'other_salaries_and_wages_grp_program_services_amt' => $row['IRS990-OtherSalariesAndWagesGrp-ProgramServicesAmt'],
            'other_salaries_and_wages_grp_management_and_general_amt' => $row['IRS990-OtherSalariesAndWagesGrp-ManagementAndGeneralAmt'],
            'other_salaries_and_wages_grp_fundraising_amt' => $row['IRS990-OtherSalariesAndWagesGrp-FundraisingAmt'],
        ]);
    }

    private function storePensionData(Organization $organization, array $row)
    {
        Pension::updateOrCreate([
            'organization_id' => $organization->id,
        ], [
            'pension_plan_contributions_grp_total_amt' => $row['IRS990-PensionPlanContributionsGrp-TotalAmt'],
            'pension_plan_contributions_grp_program_services_amt' => $row['IRS990-PensionPlanContributionsGrp-ProgramServicesAmt'],
            'pension_plan_contributions_grp_management_and_general_amt' => $row['IRS990-PensionPlanContributionsGrp-ManagementAndGeneralAmt'],
            'pension_plan_contributions_grp_fundraising_amt' => $row['IRS990-PensionPlanContributionsGrp-FundraisingAmt'],
        ]);
    }

    private function storeEmployeeBenefitsData(Organization $organization, array $row)
    {
        EmployeeBenefit::updateOrCreate([
           'organization_id' => $organization->id,
        ], [
            'other_employee_benefits_grp_total_amt' => $row['IRS990-OtherEmployeeBenefitsGrp-TotalAmt'],
            'other_employee_benefits_grp_program_services_amt' => $row['IRS990-OtherEmployeeBenefitsGrp-ProgramServicesAmt'],
            'other_employee_benefits_grp_management_and_general_amt' => $row['IRS990-OtherEmployeeBenefitsGrp-ManagementAndGeneralAmt'],
            'other_employee_benefits_grp_fundraising_amt' => $row['IRS990-OtherEmployeeBenefitsGrp-FundraisingAmt']
        ]);
    }

    private function storePayrollTaxData(Organization $organization, array $row)
    {
        PayrollTax::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'organization_id',
            'payroll_taxes_grp_total_amt' => $row['IRS990-PayrollTaxesGrp-TotalAmt'],
            'payroll_taxes_grp_program_services_amt' => $row['IRS990-PayrollTaxesGrp-ProgramServicesAmt'],
            'payroll_taxes_grp_management_and_general_amt' => $row['IRS990-PayrollTaxesGrp-ManagementAndGeneralAmt'],
            'payroll_taxes_grp_fundraising_amt' => $row['IRS990-PayrollTaxesGrp-FundraisingAmt'],
        ]);
    }

    private function storeFeeData(Organization $organization, array $row)
    {
        Fee::updateOrCreate([
            'organization_id' => $organization->id,
        ], [
            'fees_for_services_accounting_grp_total_amt' => $row['IRS990-FeesForServicesAccountingGrp-TotalAmt'],
            'fees_for_services_accounting_grp_management_and_general_amt' => $row['IRS990-FeesForServicesAccountingGrp-ManagementAndGeneralAmt'],
            'fees_for_services_other_grp_total_amt' => $row['IRS990-FeesForServicesOtherGrp-TotalAmt'],
            'fees_for_services_other_grp_program_services_amt' => $row['IRS990-FeesForServicesOtherGrp-ProgramServicesAmt'],
            'fees_for_services_other_grp_management_and_general_amt' => $row['IRS990-FeesForServicesOtherGrp-ManagementAndGeneralAmt'],
            'fees_for_services_other_grp_fundraising_amt' => $row['IRS990-FeesForServicesOtherGrp-FundraisingAmt'],
        ]);
    }

    private function storeAdvertisingData(Organization $organization, array $row)
    {
        Advertising::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'advertising_grp_total_amt' => $row['IRS990-AdvertisingGrp-TotalAmt'],
            'advertising_grp_program_services_amt' => $row['IRS990-AdvertisingGrp-ProgramServicesAmt'],
            'advertising_grp_management_and_general_amt' => $row['IRS990-AdvertisingGrp-ManagementAndGeneralAmt'],
            'advertising_grp_fundraising_amt' => $row['IRS990-AdvertisingGrp-FundraisingAmt'],
        ]);
    }

    private function storeOfficeExpenseData(Organization $organization, array $row)
    {
        OfficeExpense::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'office_expenses_grp_total_amt' => $row['IRS990-OfficeExpensesGrp-TotalAmt'],
            'office_expenses_grp_program_services_amt' => $row['IRS990-OfficeExpensesGrp-ProgramServicesAmt'],
            'office_expenses_grp_management_and_general_amt' => $row['IRS990-OfficeExpensesGrp-ManagementAndGeneralAmt'],
            'office_expenses_grp_fundraising_amt' => $row['IRS990-OfficeExpensesGrp-FundraisingAmt'],
        ]);
    }

    private function storeInformationTechData(Organization $organization, array $row)
    {
        InformationTech::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'information_technology_grp_total_amt' => $row['IRS990-InformationTechnologyGrp-TotalAmt'],
            'information_technology_grp_program_services_amt' => $row['IRS990-InformationTechnologyGrp-ProgramServicesAmt'],
            'information_technology_grp_fundraising_amt' => $row['IRS990-InformationTechnologyGrp-FundraisingAmt'],
        ]);
    }

    private function storeOccupancyData(Organization $organization, array $row)
    {
        Occupancy::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'occupancy_grp_total_amt' => $row['IRS990-OccupancyGrp-TotalAmt'],
            'occupancy_grp_program_services_amt' => $row['IRS990-OccupancyGrp-ProgramServicesAmt'],
            'occupancy_grp_management_and_general_amt' => $row['IRS990-OccupancyGrp-ManagementAndGeneralAmt'],
            'occupancy_grp_fundraising_amt' => $row['IRS990-OccupancyGrp-FundraisingAmt'],
        ]);
    }

    private function storeConferenceMeetingData(Organization $organization, array $row)
    {
        ConferenceMeeting::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'conferences_meetings_grp_total_amt' => $row['IRS990-ConferencesMeetingsGrp-TotalAmt'],
            'conferences_meetings_grp_program_services_amt' => $row['IRS990-ConferencesMeetingsGrp-ProgramServicesAmt'],
            'conferences_meetings_grp_management_and_general_amt' => $row['IRS990-ConferencesMeetingsGrp-ManagementAndGeneralAmt'],
            'conferences_meetings_grp_fundraising_amt' => $row['IRS990-ConferencesMeetingsGrp-FundraisingAmt'],
        ]);
    }

    private function storeDeprecationDeletionData(Organization $organization, array $row)
    {
        DeprecationDepletion::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'depreciation_depletion_grp_total_amt' => $row['IRS990-DepreciationDepletionGrp-TotalAmt'],
            'depreciation_depletion_grp_program_services_amt' => $row['IRS990-DepreciationDepletionGrp-ProgramServicesAmt'],
            'depreciation_depletion_grp_management_and_general_amt' => $row['IRS990-DepreciationDepletionGrp-ManagementAndGeneralAmt'],
            'depreciation_depletion_grp_fundraising_amt' => $row['IRS990-DepreciationDepletionGrp-FundraisingAmt'],
        ]);
    }

    private function storeInsuranceData(Organization $organization, array $row)
    {
        Insurance::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'insurance_grp_total_amt' => $row['IRS990-InsuranceGrp-TotalAmt'],
            'insurance_grp_program_services_amt' => $row['IRS990-InsuranceGrp-ProgramServicesAmt'],
            'insurance_grp_management_and_general_amt' => $row['IRS990-InsuranceGrp-ManagementAndGeneralAmt'],
        ]);
    }

    private function storeAllOtherExpenseData(Organization $organization, array $row)
    {
        AllOtherExpense::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'all_other_expenses_grp_total_amt' => $row['IRS990-AllOtherExpensesGrp-TotalAmt'],
            'all_other_expenses_grp_program_services_amt' => $row['IRS990-AllOtherExpensesGrp-ProgramServicesAmt'],
            'all_other_expenses_grp_management_and_general_amt' => $row['IRS990-AllOtherExpensesGrp-ManagementAndGeneralAmt'],
            'all_other_expenses_grp_fundraising_amt' => $row['IRS990-AllOtherExpensesGrp-FundraisingAmt'],
        ]);
    }

    private function storeTotalFunctionalExpenseData($organization, array $row)
    {
        TotalFunctionalExpense::updateOrCreate([
            'organization_id' => $organization->id
        ], [
            'total_functional_expenses_ggrp_total_amt' => $row['IRS990-TotalFunctionalExpensesGrp-TotalAmt'],
            'total_functional_expenses_ggrp_program_services_amt' => $row['IRS990-TotalFunctionalExpensesGrp-ProgramServicesAmt'],
            'total_functional_expenses_ggrp_management_and_general_amt' => $row['IRS990-TotalFunctionalExpensesGrp-ManagementAndGeneralAmt'],
            'total_functional_expenses_ggrp_fundraising_amt' => $row['IRS990-TotalFunctionalExpensesGrp-FundraisingAmt'],
        ]);
    }

    private function storeOtherExpenseData(Organization $organization, array $row)
    {
        if ($row['IRS990-OtherExpensesGrp'] !== 'NEF' && $row['IRS990-OtherExpensesGrp'] !== 'N/A') {
            $otherExpenses = explode('},{', $row['IRS990-OtherExpensesGrp']);
            $expenses = [];

            foreach ($otherExpenses as $key => $otherExpense) {
                // strip out the fake json brackets
                if (Str::contains($otherExpense, '{')) {
                    $otherExpense = Str::after($otherExpense, '{');
                }

                if (Str::contains($otherExpense, '}')) {
                    $otherExpense = Str::before($otherExpense, '}');
                }

                $tmpExpenses = explode(', ', $otherExpense);

                foreach ($tmpExpenses as $expense) {
                    if (Str::contains($expense, '=')) {
                        $explodedData = explode('=', $expense);

                        if ($explodedData) {
                            $expenses[$key][$explodedData[0]] = isset($explodedData[1]) ? Str::title($explodedData[1]) : null;
                        }
                    } else {
                        $expenses[$key][$expense] = null;
                    }
                }
            }


            foreach ($expenses as $financial) {
                OtherExpense::updateOrCreate([
                    'organization_id' => $organization->id
                ],[
                    'desc' => $financial['Desc'] ?? null,
                    'total_amount' => $financial['BusinessCd'] ?? null,
                    'program_services_amt' => $financial['TotalRevenueColumnAmt'] ?? null,
                ]);
            }
        }

        if ($row['IRS990-OtherExpensesGrp'] === 'NEF') {
            OtherExpense::create([
                'organization_id' => $organization->id],
                [
                    'desc' => 'NEF',
                    'total_amount' => 'NEF',
                    'program_services_amt' => 'NEF',
                ]);
        }

        if ($row['IRS990-OtherExpensesGrp'] === 'N/A') {
            OtherExpense::updateOrcreate([
                'organization_id' => $organization->id
            ], [
                'desc' => 'N/A',
                'total_amount' => 'N/A',
                'program_services_amt' => 'N/A',
            ]);
        }
    }
}
