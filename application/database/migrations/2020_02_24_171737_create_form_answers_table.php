<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('study_items_id');
            $table->integer('study_id');
            $table->integer('question_id');
            $table->integer('user_id');
            $table->text('answers');
            $table->string('type');
            $table->string('key');
            $table->integer('study_item_access_id');
            $table->integer('sort_order');
            $table->timestamps();
            $table->index(['study_items_id', 'question_id','study_item_access_id','study_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_answers');
    }
}
