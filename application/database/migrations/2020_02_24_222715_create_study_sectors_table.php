<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudySectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_sectors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('study_id');
            $table->integer('unique_id');
            $table->boolean('checked');
            $table->integer('portal_id');
            $table->timestamps();
            $table->index(['unique_id','study_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_sectors');
    }
}
