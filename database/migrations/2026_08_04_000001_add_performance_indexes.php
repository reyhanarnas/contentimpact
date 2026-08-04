<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah indexes untuk mempercepat query production.
     * - articles: status, published_at, views (semua sering di-filter/sort)
     * - articles: composite index (status + published_at) untuk query published
     * - comments: article_id + status (sering diquery untuk approved comments)
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->index('status', 'idx_articles_status');
            $table->index('published_at', 'idx_articles_published_at');
            $table->index('views', 'idx_articles_views');
            $table->index(['status', 'published_at'], 'idx_articles_status_published');
            $table->index(['status', 'views'], 'idx_articles_status_views');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['article_id', 'status'], 'idx_comments_article_status');
        });

        Schema::table('article_revisions', function (Blueprint $table) {
            $table->index('article_id', 'idx_revisions_article_id');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('idx_articles_status');
            $table->dropIndex('idx_articles_published_at');
            $table->dropIndex('idx_articles_views');
            $table->dropIndex('idx_articles_status_published');
            $table->dropIndex('idx_articles_status_views');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('idx_comments_article_status');
        });

        Schema::table('article_revisions', function (Blueprint $table) {
            $table->dropIndex('idx_revisions_article_id');
        });
    }
};
