<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    // ── 1. Fix the 'writter' → 'writer' role typo in existing user records ──
    DB::table('users')
      ->where('role', 'writter')
      ->update(['role' => 'writer']);

    // ── 2. Performance indexes ─────────────────────────────────────────────
    Schema::table('posts', function (Blueprint $table): void {
      // Heavily used in WHERE active = 1 queries on every page load
      $table->index('active', 'posts_active_idx');
    });

    Schema::table('comments', function (Blueprint $table): void {
      // Used in PostController to filter visible comments
      $table->index('active', 'comments_active_idx');
    });

    Schema::table('login_history', function (Blueprint $table): void {
      // Used in LoginHistoryController stat aggregation
      $table->index('status', 'login_history_status_idx');
    });
  }

  public function down(): void
  {
    // Revert role back (data loss risk — only for rollback in dev)
    DB::table('users')
      ->where('role', 'writer')
      ->update(['role' => 'writter']);

    Schema::table('posts', function (Blueprint $table): void {
      $table->dropIndex('posts_active_idx');
    });

    Schema::table('comments', function (Blueprint $table): void {
      $table->dropIndex('comments_active_idx');
    });

    Schema::table('login_history', function (Blueprint $table): void {
      $table->dropIndex('login_history_status_idx');
    });
  }
};
