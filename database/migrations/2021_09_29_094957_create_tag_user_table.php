<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('tag_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('user');
            $table->foreignId('tag_id')->references('id')->on('tags');
            $table->float('progress')->default(0);
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_user');
    }
}
