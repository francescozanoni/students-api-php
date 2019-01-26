<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->char('code', 2)->nullable(false); // ISO 3166-1 alpha-2 country code
            $table->timestamps();
            $table->softDeletes();
            $table->unique('name');
            // Columns used as foreign keys by other tables must have a UNIQUE constraint.
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
