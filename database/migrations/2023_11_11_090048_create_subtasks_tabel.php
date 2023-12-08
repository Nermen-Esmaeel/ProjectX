<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->enum("status", ["Canceled", "Completed", "Pending", "OnProgress", "OnHold"]);
            $table->enum("priority", ["high", "low"]);
            $table->date("start_date");
            $table->date("end_date");
            $table->longText("desciption");
            $table->unsignedBigInteger("owner_id")->nullable();

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");

            $table->unsignedBigInteger("task_id");
            $table->foreign("task_id")->references("id")->on("tasks")->onDelete("cascade");

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
        Schema::dropIfExists('subtasks');
    }
};
