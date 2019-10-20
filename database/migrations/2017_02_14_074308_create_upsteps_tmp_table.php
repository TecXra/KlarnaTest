<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpstepsTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upsteps_tmp', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->string('RimSize', 10);
            $table->string('RimWide', 10);
            $table->string('Tyre1', 20);
            $table->string('Tyre2', 20);
            $table->string('Tyre3', 20);
            $table->string('Tyre4', 20);
            $table->string('Tyre5', 20);
            $table->string('Tyre6', 20);
            $table->string('Tyre7', 20);
            $table->string('Tyre8', 20);
            $table->string('MinOffset', 10);
            $table->string('MaxOffset', 10);
            $table->string('Token', 30);
            $table->timestamps();
        });

        Schema::table('upsteps_tmp', function($table) {
            $table->index('Token');
            $table->index('RimSize');
            $table->index('MinOffset');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Scheme::drop("upsteps_tmp");
    }
}
