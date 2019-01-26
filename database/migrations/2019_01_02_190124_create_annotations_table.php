<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnotationsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('annotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->nullable(false);
            $table->char('title')->nullable(false);
            $table->char('content')->nullable(false);
            // annotation author, whose referential integrity is enforced by input validation rules
            $table->integer('user_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('student_id')->references('id')->on('students');
        });

        // @todo assess whether to create a user importer
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('annotations');
    }

}
