<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->unsignedInteger('student_id')->nullable(false);
            $table->unsignedInteger('location_id')->nullable(false); // referential integrity is enforced by input validation rules
            $table->unsignedInteger('sub_location_id')->nullable(true); // referential integrity is enforced by input validation rules
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);
            $table->unsignedInteger('hour_amount')->nullable(false);
            $table->unsignedInteger('other_amount')->nullable(true); // another amount, e.g. night shift amount
            $table->boolean('is_optional')->nullable(false);
            $table->boolean('is_interrupted')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('student_id')->references('id')->on('students');
        });

        // @todo find how to enforce UNIQUE (student_id, location_id, sub_location_id, start_date)
        // @todo assess whether to create a location and sub location importer
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stages');
    }

}
