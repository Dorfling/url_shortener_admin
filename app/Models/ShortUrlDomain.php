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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrlDomain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrlDomain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrlDomain query()
 * @mixin \Eloquent
 */
class ShortUrlDomain extends \App\Models\BaseModel\BaseModel
{
    protected $table = 'short_urls.short_url_domains';

    protected $casts = [
        'uuid' => 'uuid',
        'company_id' => 'int'
    ];

    protected $dates = [
        'dt_deleted'
    ];

    protected $fillable = [
        'dt_deleted',
        'company_id',
        'domain'
    ];

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }
}
