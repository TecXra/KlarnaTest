<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronJobStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_job_status', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('name');
            $table->text('response')->nullable();
            $table->integer('created_products')->nullable();
            $table->integer('updated_products')->nullable();
            $table->integer('deleted_products')->nullable();
            $table->timestamp('begin_at');
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
        Schema::drop('cron_job_status');
    }
}
