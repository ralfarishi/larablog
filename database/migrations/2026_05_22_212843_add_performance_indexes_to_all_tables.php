<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add all missing performance indexes identified in the audit.
     * Also persists reading_time to eliminate the expensive accessor computation.
     *
     * Uses Schema::hasIndex() (DB-agnostic) instead of MySQL-only SHOW INDEX FROM,
     * so this migration is compatible with SQLite (test suite) and MySQL/Postgres.
     */
    public function up(): void
    {
        // ── posts table ────────────────────────────────────────────────────────
        Schema::table('posts', function (Blueprint $table): void {
            // reading_time stored as DB column so the accessor computation
            // (strip_tags + ContentRenderer::render + str_word_count) only
            // runs once at save time, never at query time.
            if (!Schema::hasColumn('posts', 'reading_time')) {
                $table->unsignedSmallInteger('reading_time')->default(1)->after('status');
            }

            // status is filtered on every public page (WHERE status = 'published')
            if (!Schema::hasIndex('posts', 'posts_status_created_idx')) {
                $table->index(['status', 'created_at'], 'posts_status_created_idx');
            }
            // Used by postByUser() + dashboard queries
            if (!Schema::hasIndex('posts', 'posts_status_user_idx')) {
                $table->index(['status', 'user_id'], 'posts_status_user_idx');
            }
            // Category listing and related-posts queries
            if (!Schema::hasIndex('posts', 'posts_category_idx')) {
                $table->index('category_id', 'posts_category_idx');
            }
        });

        // ── comments table ─────────────────────────────────────────────────────
        Schema::table('comments', function (Blueprint $table): void {
            // withCount('comments') on every post card
            if (!Schema::hasIndex('comments', 'comments_post_idx')) {
                $table->index('post_id', 'comments_post_idx');
            }
            // ReaderDashboard comment history per user
            if (!Schema::hasIndex('comments', 'comments_user_idx')) {
                $table->index('user_id', 'comments_user_idx');
            }
        });

        // ── login_history table ────────────────────────────────────────────────
        Schema::table('login_history', function (Blueprint $table): void {
            // Hourly scheduled job + logout action both filter on email + logout_at
            if (!Schema::hasIndex('login_history', 'login_history_email_logout_idx')) {
                $table->index(['email', 'logout_at'], 'login_history_email_logout_idx');
            }
        });

        // ── categories table ───────────────────────────────────────────────────
        Schema::table('categories', function (Blueprint $table): void {
            // Slug is used for routing; must be unique
            if (!Schema::hasIndex('categories', 'categories_slug_unique')) {
                $table->unique('slug', 'categories_slug_unique');
            }
            // name is queried in CategoryListController::show()
            if (!Schema::hasIndex('categories', 'categories_name_idx')) {
                $table->index('name', 'categories_name_idx');
            }
        });

        // ── users table ────────────────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table): void {
            // postByUser() looks up users by slug
            if (!Schema::hasIndex('users', 'users_slug_unique')) {
                $table->unique('slug', 'users_slug_unique');
            }
        });

        // ── post_tag pivot ─────────────────────────────────────────────────────
        Schema::table('post_tag', function (Blueprint $table): void {
            // Prevent duplicate entries + composite lookup (post → tags)
            if (!Schema::hasIndex('post_tag', 'post_tag_post_id_tag_id_unique')) {
                $table->unique(['post_id', 'tag_id'], 'post_tag_post_id_tag_id_unique');
            }
            // Reverse lookup: tag → posts
            if (!Schema::hasIndex('post_tag', 'post_tag_tag_id_idx')) {
                $table->index('tag_id', 'post_tag_tag_id_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            if (Schema::hasColumn('posts', 'reading_time')) {
                $table->dropColumn('reading_time');
            }
            if (Schema::hasIndex('posts', 'posts_status_created_idx')) {
                $table->dropIndex('posts_status_created_idx');
            }
            if (Schema::hasIndex('posts', 'posts_status_user_idx')) {
                $table->dropIndex('posts_status_user_idx');
            }
            if (Schema::hasIndex('posts', 'posts_category_idx')) {
                $table->dropIndex('posts_category_idx');
            }
        });

        Schema::table('comments', function (Blueprint $table): void {
            if (Schema::hasIndex('comments', 'comments_post_idx')) {
                $table->dropIndex('comments_post_idx');
            }
            if (Schema::hasIndex('comments', 'comments_user_idx')) {
                $table->dropIndex('comments_user_idx');
            }
        });

        Schema::table('login_history', function (Blueprint $table): void {
            if (Schema::hasIndex('login_history', 'login_history_email_logout_idx')) {
                $table->dropIndex('login_history_email_logout_idx');
            }
        });

        Schema::table('categories', function (Blueprint $table): void {
            if (Schema::hasIndex('categories', 'categories_slug_unique')) {
                $table->dropUnique('categories_slug_unique');
            }
            if (Schema::hasIndex('categories', 'categories_name_idx')) {
                $table->dropIndex('categories_name_idx');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasIndex('users', 'users_slug_unique')) {
                $table->dropUnique('users_slug_unique');
            }
        });

        Schema::table('post_tag', function (Blueprint $table): void {
            if (Schema::hasIndex('post_tag', 'post_tag_post_id_tag_id_unique')) {
                $table->dropUnique('post_tag_post_id_tag_id_unique');
            }
            if (Schema::hasIndex('post_tag', 'post_tag_tag_id_idx')) {
                $table->dropIndex('post_tag_tag_id_idx');
            }
        });
    }
};
