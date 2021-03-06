<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEligibilitiesTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('eligibilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')->nullable(false);
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);
            $table->string('notes')->nullable(true);
            $table->boolean('is_eligible')->nullable(false);
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
        Schema::dropIfExists('eligibilities');
    }

}
