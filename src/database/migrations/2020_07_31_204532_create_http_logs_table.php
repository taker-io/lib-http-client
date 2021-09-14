<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHttpLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('http_logs')) {
            Schema::create('http_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('route');
                $table->string('type', 6);
                $table->text('request');
                $table->text('response');
                $table->smallInteger('code');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('http_logs');
    }
}
