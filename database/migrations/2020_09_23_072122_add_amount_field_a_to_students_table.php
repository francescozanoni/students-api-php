<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountFieldAToStudentsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('amount_field_a')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('amount_field_a');
        });
        Schema::enableForeignKeyConstraints();
    }
}
