<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('portal_id');
            $table->string('gender');
            $table->string('agegroup');
            $table->string('location');
            $table->string('bio');
            $table->string('ethnicity')->nullable();
            $table->jsonb('sectors');
            $table->integer('contactnumber')->nullable();
            $table->boolean('profile_status')->default(false);
            $table->boolean('newsletter_subscription');
            $table->timestamps();
            $table->index(['user_id','portal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
