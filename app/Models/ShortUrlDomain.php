<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

/**
 * App\Models\ShortUrlDomain
 *
 * @property-read \App\Models\Company $company
 * @property \UuidInterface $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ShortUrl[] $short_urls
 * @property-read int|null $short_urls_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrlDomain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrlDomain newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ShortUrlDomain onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrlDomain query()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ShortUrlDomain withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ShortUrlDomain withoutTrashed()
 * @mixin \Eloquent
 */
class ShortUrlDomain extends \App\Models\BaseModel\BaseModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes;



    protected $table = 'short_urls.short_url_domains';

    protected $casts = [
        'uuid' => 'uuid',
        'company_id' => 'int',
        'public' => 'boolean'
    ];

    protected $fillable = [
        'company_id',
        'public',
        'domain'
    ];

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    public function short_urls()
    {
        return $this->hasMany(\App\Models\ShortUrl::class, 'short_url_domain_id');
    }
}
