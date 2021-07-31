<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id_report');
            $table->string('name_project');
            $table->integer('time_for_project');
            $table->integer('rate_of_process');
            $table->string('status');
            $table->string('reason')->nullable();
            $table->string('advantage')->nullable();
            $table->string('disadvantage')->nullable();
            $table->string('suggestion')->nullable();
            $table->string('plan_for_next_day');
            $table->bigInteger('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('id_project')->unsigned();
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('reports');
    }
}
