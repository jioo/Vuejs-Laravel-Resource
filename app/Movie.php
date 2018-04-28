<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'year', 'youtubeId'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id'  => 'int',
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * Belongs to category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
