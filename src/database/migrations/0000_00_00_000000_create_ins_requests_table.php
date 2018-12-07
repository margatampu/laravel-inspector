<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ins_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ins_auth_id')->nullable();
            $table->string('method');
            $table->text('uri');
            $table->string('ip')->nullable();
            $table->text('headers')->nullable();
            $table->float('start_time', 16, 4)->nullable();
            $table->float('end_time', 16, 4)->nullable();
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
        Schema::dropIfExists('ins_requests');
    }
}
