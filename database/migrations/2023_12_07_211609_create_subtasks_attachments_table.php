<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subtasks_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('subtask_id')->unsigned();
            $table->unsignedBiginteger('attachment_id')->unsigned();

            $table->foreign('subtask_id')->references('id')
                 ->on('subtasks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('attachment_id')->references('id')
                ->on('attachments')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtasks_attachments');
    }
};
