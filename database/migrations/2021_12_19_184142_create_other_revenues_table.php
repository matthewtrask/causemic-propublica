<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_revenues', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->nullable();
            $table->string('description')->nullable();
            $table->string('total_revenue_amt')->nullable();
            $table->string('business_cd')->nullable();
            $table->string('exclusion_amt')->nullable();
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
        Schema::dropIfExists('other_revenues');
    }
}
