<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('name', 255);
            $table->string('label', 255);
            $table->string('author', 255);
            $table->text('content');
            $table->string('meta_title', 255);
            $table->string('meta_description', 255);
            $table->string('meta_keywords', 255);
            $table->boolean('is_post')->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_removable')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
