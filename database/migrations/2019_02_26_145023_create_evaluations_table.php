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
            $table->enum('item_1_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_1_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_1_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_1_4', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_2_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_2_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_2_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_2_4', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_2_5', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_3_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_3_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_3_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_3_4', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_4_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_4_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_4_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_4_4', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_4_5', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_5_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_5_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_5_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_5_4', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_5_5', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_6_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_6_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_6_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_7_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_7_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_8_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_8_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_8_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_8_4', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_8_5', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_9_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_9_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_10_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_10_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_11_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_11_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_12_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_12_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_12_3', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_13_1', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->enum('item_13_2', ['A', 'B', 'C', 'D', 'E', 'NV'])->nullable(true);
            $table->string('annotations')->nullable(false);
            $table->unsignedInteger('stage_id')->nullable(false);
            // annotation author, whose referential integrity is enforced by input validation rules
            $table->unsignedInteger('clinical_tutor_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('stage_id')->references('id')->on('stages');
        });

        // @todo assess whether to create a clinical tutor importer
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }

}
