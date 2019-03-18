<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterruptionReportsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('interruption_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notes')->nullable(false);
            $table->unsignedInteger('internship_id')->nullable(false);
            // report author, whose referential integrity is enforced by input validation rules
            $table->unsignedInteger('clinical_tutor_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('internship_id')->references('id')->on('internships');
        });

        // @todo assess whether to create a clinical tutor importer
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('interruption_reports');
    }

}
