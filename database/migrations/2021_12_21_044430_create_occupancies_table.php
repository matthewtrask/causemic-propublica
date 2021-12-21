<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateOccupanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupancies', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('OccupancyGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OccupancyGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OccupancyGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('OccupancyGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('occupancies');
    }
}
