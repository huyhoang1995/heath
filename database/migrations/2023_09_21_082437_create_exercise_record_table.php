<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_record', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('duration');
            $table->string('content');
            $table->integer('calories');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercise_record');
    }
};
