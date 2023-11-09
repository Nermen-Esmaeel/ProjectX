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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("type");
            $table->date("start_date");
            $table->date("end_date");
            $table->dateTime("time_spent")->nullable();
            $table->longText("description");

            $table->enum("priority", ["high", "low"]);
            $table->enum("status", ["Canceled", "Completed", "Pending", "OnProgress", "OnHold"]);

            $table->unsignedBigInteger("project_id");
            $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade");

            $table->unsignedBigInteger("assign_to");
            $table->foreign("assign_to")->references("id")->on("users")->onDelete("cascade");

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
        Schema::dropIfExists('tasks');
    }
};
