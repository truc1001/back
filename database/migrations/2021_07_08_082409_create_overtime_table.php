<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user')->unsigned(); // nguoi xin don lam
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('reason')->nullable(); // ly do lam
            $table->integer('number');    // so gio lam
            $table->boolean('status')->default(0);  // trang thai
            $table->datetime('ngayDK');   // ngay dang ky don
            $table->bigInteger('id_Admin')->unsigned()->nullable();   // nguoi duyet
            $table->foreign('id_Admin')->references('id')->on('users');
            $table->datetime('approved_time')->nullable(); // thoi gian duyet don dang ky
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
        Schema::dropIfExists('overtime');
    }
}
