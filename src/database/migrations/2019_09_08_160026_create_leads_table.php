<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Contracts\Table\NameInterface as TableNameInterface;
use App\Contracts\Table\ColumnName\LeadInterface;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TableNameInterface::LEADS, function (Blueprint $table) {
            $table->bigIncrements(LeadInterface::ID);

            //person
            $table->string(LeadInterface::PHONE_WORK)->nullable();
            $table->string(LeadInterface::PHONE_WHATS_APP)->nullable();
            $table->string(LeadInterface::CITY)->nullable();
            $table->string(LeadInterface::TIMEZONE)->nullable();
            $table->string(LeadInterface::COUNTRY)->nullable();

            //person > names
            $table->string(LeadInterface::FIRST_NAME)->nullable();
            $table->string(LeadInterface::LAST_NAME)->nullable();
            $table->string(LeadInterface::MIDDLE_NAME)->nullable();

            //driver license
            $table->string(LeadInterface::DRIVER_LICENSE_NUMBER)->nullable();
            $table->string(LeadInterface::DRIVER_LICENSE_ISSUE_DATE)->nullable();
            $table->string(LeadInterface::DRIVER_LICENSE_EXPIRATION_DATE)->nullable();
            $table->string(LeadInterface::BIRTH_DATE)->nullable();

            //car
            $table->string(LeadInterface::CAR_NUMBER)->nullable();
            $table->string(LeadInterface::CAR_BRAND)->nullable();
            $table->string(LeadInterface::CAR_MODEL)->nullable();
            $table->string(LeadInterface::CAR_YEAR)->nullable();
            $table->string(LeadInterface::CAR_COLOR)->nullable();
            $table->string(LeadInterface::CAR_VIN)->nullable();

            $table->string(LeadInterface::REGISTRATION_NUMBER)->nullable();

            $table->string(LeadInterface::BRANDING)->nullable();

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
        Schema::dropIfExists(TableNameInterface::LEADS);
    }
}
