<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

/**
 * App\Models\ShortUrl
 *
 * @property-read \App\Models\Company $company
 * @property \UuidInterface $uuid
 * @property-read \App\Models\ShortUrlDomain $short_url_domain
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ShortUrlTracking[] $short_url_trackings
 * @property-read int|null $short_url_trackings_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ShortUrl onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl query()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ShortUrl withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ShortUrl withoutTrashed()
 * @mixin \Eloquent
 */
class ShortUrl extends \App\Models\BaseModel\BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;



    protected $table = 'short_urls.short_urls';

    protected $casts = [
        'uuid' => 'uuid',
        'company_id' => 'int',
        'short_url_domain_id' => 'int'
    ];

    protected $fillable = [
        'company_id',
        'short_url_domain_id',
        'full_url',
        'short_url',
        'hash'
    ];

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    public function short_url_domain()
    {
        return $this->belongsTo(\App\Models\ShortUrlDomain::class, 'short_url_domain_id');
    }

    public function short_url_trackings()
    {
        return $this->hasMany(\App\Models\ShortUrlTracking::class, 'short_url_id');
    }
}
