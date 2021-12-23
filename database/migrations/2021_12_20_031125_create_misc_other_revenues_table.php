<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateMiscOtherRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('misc_other_revenues', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('desc')->nullable();
            $table->string('business_cd')->nullable();
            $table->string('total_revenue_column_amt')->nullable();
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
        Schema::dropIfExists('misc_other_revenues');
    }
}
