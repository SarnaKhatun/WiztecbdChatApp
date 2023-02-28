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
        Schema::create('message_details', function (Blueprint $table) {
            $table->id();
            $table->integer('messages_id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->text('message_body');
            $table->string('status')->nullable()->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_details');
    }
};
