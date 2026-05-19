<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table
        ->enum('status', ['draft', 'published', 'hidden'])
        ->default('draft')
        ->after('allowed_comment');
    });

    // Migrate existing data: active=1 → published, active=0 → draft
    DB::table('posts')
      ->where('active', 1)
      ->update(['status' => 'published']);
    DB::table('posts')
      ->where('active', 0)
      ->update(['status' => 'draft']);

    Schema::table('posts', function (Blueprint $table): void {
      $table->dropIndex('posts_active_idx');
      $table->dropColumn('active');
    });
  }

  public function down(): void
  {
    Schema::table('posts', function (Blueprint $table): void {
      $table->boolean('active')->default(false)->after('allowed_comment');
    });

    // Reverse: published → active=1, everything else → active=0
    DB::table('posts')
      ->where('status', 'published')
      ->update(['active' => 1]);
    DB::table('posts')
      ->whereIn('status', ['draft', 'hidden'])
      ->update(['active' => 0]);

    Schema::table('posts', function (Blueprint $table): void {
      $table->index('active', 'posts_active_idx');
      $table->dropColumn('status');
    });
  }
};
