<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('bookmarks', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->foreignId('post_id')->constrained()->cascadeOnDelete();
      $table->timestamps();

      // One bookmark per user per post
      $table->unique(['user_id', 'post_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('bookmarks');
  }
};
