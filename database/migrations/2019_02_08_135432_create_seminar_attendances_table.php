<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeminarAttendancesTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seminar_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seminar')->nullable(false);
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(true);
            $table->float('ects_credits')->nullable(false);
            $table->unsignedInteger('student_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('student_id')->references('id')->on('students');
        });

        // @todo find how to enforce UNIQUE (student_id, name, start_date)
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seminar_attendances');
    }

}
