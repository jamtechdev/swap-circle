<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCustomersTxns extends Model
{
	protected $table="users_customers_txns";
	protected $fillable = [
		'from_users_customers_id',
		'from_system_currencies_id',
		'from_amount',
		'to_users_customers_id',
		'to_system_currencies_id',
		'to_amount',
		'admin_share',
		'admin_share_amount',
		'payment_method_id',
		'system_countries_id',
		'system_currencies_id',
		'base_amount',
		'status',
	];
    protected $primaryKey = 'users_customers_txns_id';
    public $timestamps = false; 
}