<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('study_id');
            $table->integer('member_id');
            $table->jsonb('questionConfig');
            $table->jsonb('questions');
            $table->string('question_uniquid');
            $table->timestamps();
            $table->index(['study_id', 'member_id', 'question_uniquid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streams');
    }
}
