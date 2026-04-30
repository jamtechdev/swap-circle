<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SwapOffer extends Model
{
	protected $table="swap_offers";

    protected $fillable = ['users_customers_id','from_system_currencies_id','to_system_currencies_id','from_amount','to_amount',
                        'exchange_rate','system_currencies_id','base_amount','admin_share','admin_share_amount','expiry_date_time'];

    protected $primaryKey = 'swap_offers_id';
    public $timestamps = false;
    
    public function to_currency()
    {	
        return $this->hasOne(SystemCurrency::class,'system_currencies_id','to_system_currencies_id')->with('country');
    }
    
    public function from_currency()
    {	
        return $this->hasOne(SystemCurrency::class,'system_currencies_id','from_system_currencies_id')->with('country');
    }
}