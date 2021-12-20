<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notables', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('notable_contribution')->nullable();
            $table->string('notable_program_services')->nullable();
            $table->string('notable_investment_income')->nullable();
            $table->string('notable_net_fundraising')->nullable();
            $table->string('notable_sales_of_assets')->nullable();
            $table->string('notable_net_inventory_of_sales')->nullable();
            $table->string('other_revenue')->nullable();
            $table->string('other_total_assets')->nullable();
            $table->string('other_total_liabilities')->nullable();
            $table->string('other_net_assets')->nullable();
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
        Schema::dropIfExists('notables');
    }
}
