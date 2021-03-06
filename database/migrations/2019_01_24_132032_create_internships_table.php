<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternshipsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')->nullable(false);
            $table->unsignedInteger('location_id')->nullable(false);
            $table->unsignedInteger('sub_location_id')->nullable(true);
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);
            $table->unsignedInteger('hour_amount')->nullable(true);
            $table->unsignedInteger('other_amount')->nullable(true); // another amount, e.g. night shift amount
            $table->boolean('is_optional')->nullable(false);
            $table->boolean('is_interrupted')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('sub_location_id')->references('id')->on('sub_locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('internships');
    }

}
