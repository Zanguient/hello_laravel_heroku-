<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('invitee_email');
            $table->date('invitee_joining_date');
            $table->integer('invitee_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('active')->nullable();
            $table->integer('study_id');
            $table->string('email_confirmation_id')->default(0);
            $table->timestamps();
            $table->index(['email_confirmation_id', 'invitee_email','invitee_id','study_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesses');
    }
}
