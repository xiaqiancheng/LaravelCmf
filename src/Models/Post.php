<?php

namespace XADMIN\LaravelCmf\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use XADMIN\LaravelCmf\Facades\LaravelCmf;
use XADMIN\LaravelCmf\Traits\Resizable;
use XADMIN\LaravelCmf\Traits\Translatable;

class Post extends Model
{
    use Translatable,
        Resizable;

    protected $translatable = ['title', 'seo_title', 'excerpt', 'body', 'slug', 'meta_description', 'meta_keywords'];

    const PUBLISHED = 'PUBLISHED';

    protected $guarded = [];

    public function save(array $options = [])
    {
        // If no author has been assigned, assign the current user's id as the author of the post
        if (!$this->author_id && app('LaravelCmfAuth')->user()) {
            $this->author_id = app('LaravelCmfAuth')->user()->getKey();
        }

        parent::save();
    }

    public function authorId()
    {
        return $this->belongsTo(LaravelCmf::modelClass('User'), 'author_id', 'id');
    }

    /**
     * Scope a query to only published scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', '=', static::PUBLISHED);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->belongsTo(LaravelCmf::modelClass('Category'));
    }
}
