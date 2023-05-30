<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserSubscription
 * @package App\Models
 * @version January 6, 2020, 10:14 am UTC
 *
 * @property integer user_id
 * @property string product_id
 * @property string environment
 * @property string original_transaction_id
 * @property string start_date
 * @property string end_date
 * @property string latest_receipt
 */

class UserSubscription extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'user_subscriptions';

    protected $dates = ['deleted_at'];
    public $fillable = [
        'user_id',
        'product_id',
        'subscription_group',
        'environment',
        'original_transaction_id',
        'transaction_id',
        'original_purchase_date',
        'purchase_date',
        'expiry_date',
        'is_trial',
        'quantity',
        'latest_receipt'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];
}
