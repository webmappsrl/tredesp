<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('trello_id');
            $table->longText('name');
            $table->longText('list')->default('');
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->unsignedBigInteger('member_id')->nullable();

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
