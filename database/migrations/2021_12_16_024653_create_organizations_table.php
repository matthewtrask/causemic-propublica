<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('ein')->nullable();
            $table->text('mission_statement')->nullable();
            $table->string('filer_ein')->nullable();
            $table->string('total_revenue')->nullable();
            $table->string('net_income')->nullable();
            $table->string('exempt_since')->nullable();
            $table->text('classifications')->nullable();
            $table->string('tax_period_end_date')->nullable();
            $table->string('tax_year')->nullable();
            $table->string('return_header_tax_year')->nullable();
            $table->longtext('ntee')->nullable();
            $table->string('propublica_url');
            $table->string('principle_officer')->nullable();
            $table->string('total_functional_expenses')->nullable();
            $table->string('gross_receipts_amount')->nullable();
            $table->string('organization_501c3_ind')->nullable();
            $table->string('website_address_txt')->nullable();
            $table->string('type_of_organization')->nullable();
            $table->string('formation_year')->nullable();
            $table->string('voting_members_governing_body_count')->nullable();
            $table->string('voting_members_independent_count')->nullable();
            $table->string('total_employee_count')->nullable();
            $table->string('total_volunteer_count')->nullable();
            $table->string('filter_url')->nullable();
            $table->string('total_gross_ubi_amt')->nullable();
            $table->string('financial_data')->nullable();
            $table->string('net_unrelated_bus_taxable_amt')->nullable();
            $table->string('raw_xml')->nullable();
            $table->text('form_990_part_VII_section_a_grp')->nullable();
            $table->text('gross_investment_income_170_grp_current_tax_year_amt')->nullable(); //GrossInvestmentIncome170Grp-CurrentTaxYearAmt
            $table->text('other_income_170_grp_current_tax_year_amt')->nullable(); //OtherIncome170Grp-CurrentTaxYearAmt
            $table->text('recipient_table')->nullable();
            $table->text('error')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
