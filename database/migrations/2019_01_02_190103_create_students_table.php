<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->char('first_name')->nullable(false);
            $table->char('last_name')->nullable(false);
            $table->char('phone')->nullable(true);
            $table->char('e_mail')->nullable(true);
            // Nationality consists of the ISO country code.
            $table->char('nationality', 2)->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('nationality')->references('code')->on('countries');
        });

        // @todo assess whether to create a country importer
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }

}
