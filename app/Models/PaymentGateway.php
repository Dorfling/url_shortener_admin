<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

/**
 * App\Models\PaymentGateway
 *
 * @property \UuidInterface $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentGateway newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentGateway newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PaymentGateway query()
 * @mixin \Eloquent
 */
class PaymentGateway extends \App\Models\BaseModel\BaseModel
{
    protected $table = 'billing.payment_gateways';

    protected $casts = [
        'uuid' => 'uuid'
    ];

    protected $fillable = [
        'name'
    ];
}
