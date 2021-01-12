<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_details', function (Blueprint $table) {
            // $table->id();
            $table->id('test_id');
            $table->text("test_name");
            $table->text("board");
            $table->text("class");
            $table->text("subject");
            $table->text("level");
            $table->text("module");
            $table->text("duration");
            $table->text("marks");
            $table->date_time_set("datetime");
            $table->integer("no_of_question");
            $table->text("created_by");
            $table->text("is_avilable")->nullable();
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
        Schema::dropIfExists('test_details');
    }
}
