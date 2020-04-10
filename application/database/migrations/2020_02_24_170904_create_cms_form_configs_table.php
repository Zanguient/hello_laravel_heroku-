<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsFormConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_form_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('study_id')->unique();
            $table->text('path_to_logo')->nullable();
            $table->text('intro_text')->nullable();
            $table->string('background_colour')->nullable();
            $table->string('text_colour')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('cms_form_configs');
    }
}
