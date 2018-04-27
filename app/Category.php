<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * Category has many article
     */
     public function articles()
     {
         return $this->hasMany(Article::class);
     }
}
