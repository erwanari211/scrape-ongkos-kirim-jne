<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('origin');
            $table->string('origin_type');
            $table->unsignedInteger('destination');
            $table->string('destination_type');
            $table->unsignedInteger('weight')->default(1000);
            $table->string('courier');
            $table->string('service');
            $table->unsignedBigInteger('cost')->default(0);
            $table->string('estimation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costs');
    }
}
