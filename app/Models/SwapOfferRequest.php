<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwapOfferRequest extends Model
{
	protected $table="swap_offers_requests";

    protected $fillable = ['from_users_customers_id','swap_offers_id'];

    protected $primaryKey = 'swap_offers_requests_id';
    public $timestamps = false;

}