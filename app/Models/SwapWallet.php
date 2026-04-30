<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwapWallet extends Model
{
	protected $table="swap_wallets";

    protected $fillable = ['users_customers_id','from_users_customers_wallets_id','to_users_customers_wallets_id','amount_from','amount_to',
                        'exchange_rate','system_currencies_id','base_amount','admin_share','admin_share_amount'];

    protected $primaryKey = 'swap_wallets_id';
    public $timestamps = false;

}