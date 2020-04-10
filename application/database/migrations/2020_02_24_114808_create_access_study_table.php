<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessStudyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_study', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('study_id');
            $table->integer('access_id');
            $table->integer('user_id');
            $table->date('deleted_at');
            $table->timestamps();
            $table->index(['study_id', 'access_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_study');
    }
}
