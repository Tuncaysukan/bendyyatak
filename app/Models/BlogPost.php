<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'blog_category_id', 'admin_id', 'title', 'slug', 'excerpt',
        'content', 'cover_image', 'seo_title', 'seo_description',
        'og_image', 'is_published', 'published_at'
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/blog-placeholder.jpg');
    }
}
