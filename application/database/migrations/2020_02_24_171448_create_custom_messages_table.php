<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->text('message');
            $table->integer('study_id');
            $table->boolean('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('unique_id');
            $table->timestamps();
            $table->index('study_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_messages');
    }
}
