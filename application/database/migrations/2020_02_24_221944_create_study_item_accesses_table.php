<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyItemAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_item_accesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('study_item_id');
            $table->integer('study_id');
            $table->timestamp('deleted_at')->nullable();;
            $table->boolean('value');
            $table->integer('completed')->nullable();
            $table->timestamps();
            $table->index(['user_id','study_item_id','study_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_item_accesses');
    }
}
