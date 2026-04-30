<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteSwapOffer extends Model
{
	protected $table="swap_offers_favourite";

    protected $fillable = ['users_customers_id','swap_offers_id'];

    protected $primaryKey = 'swap_offers_favourite_id';
    public $timestamps = false;

}