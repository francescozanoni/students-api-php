<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->increments('id');
            foreach (config('internships.evaluations.items') as $item) {
                $table->enum($item['name'], $item['values'])->nullable(!$item['required']);
            }
            $table->string('notes')->nullable(true);
            $table->unsignedInteger('internship_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('internship_id')->references('id')->on('internships');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }

}
