<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateConferenceMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conference_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->unique();
            $table->string(Str::lower(Str::snake('ConferencesMeetingsGrpTotalAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('ConferencesMeetingsGrpProgramServicesAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('ConferencesMeetingsGrpManagementAndGeneralAmt', '_')))->nullable();
            $table->string(Str::lower(Str::snake('ConferencesMeetingsGrpFundraisingAmt', '_')))->nullable();
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
        Schema::dropIfExists('conference_meetings');
    }
}
