<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeminarsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable(false);
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(true);
            $table->unsignedInteger('etcs_amount')->nullable(false);
            $table->unsignedInteger('student_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seminars');
    }

}
