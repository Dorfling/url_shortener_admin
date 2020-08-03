<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

/**
 * App\Models\CacheTag
 *
 * @property \UuidInterface $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CacheTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CacheTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CacheTag query()
 * @mixin \Eloquent
 */
class CacheTag extends \App\Models\BaseModel\BaseModel
{
    protected $table = 'tags.cache_tags';

    protected $casts = [
        'uuid' => 'uuid'
    ];

    protected $fillable = [
        'name'
    ];
}
