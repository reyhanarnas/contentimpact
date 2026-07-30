<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'status', // draft, pending_review, revision_required, published
        'revision_note',
        'views',
        'likes',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views' => 'integer',
            'likes' => 'integer',
        ];
    }

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function revisions()
    {
        return $this->hasMany(ArticleRevision::class)->latest();
    }

    // Status Helpers
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    public function isRevisionRequired(): bool
    {
        return $this->status === 'revision_required';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeLatestPublished($query)
    {
        return $query->where('status', 'published')->orderBy('published_at', 'desc');
    }

    public function getCoverImageUrlAttribute(): string
    {
        if (!$this->cover_image) {
            return 'https://images.unsplash.com/photo-1495020689067-958852a6565d?q=80&w=600&auto=format&fit=crop';
        }

        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        return Storage::url($this->cover_image);
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // 200 words per minute average reading speed
        return (int) max(1, $minutes);
    }
}
