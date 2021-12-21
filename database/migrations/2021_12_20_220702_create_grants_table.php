<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('GrantsToDomesticOrgsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('GrantsToDomesticOrgsGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('GrantsToDomesticIndividualsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('GrantsToDomesticIndividualsGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('ForeignGrantsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('ForeignGrantsGrpProgramServicesAmt', '_')))->nullable();
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
        Schema::dropIfExists('grants');
    }
}



