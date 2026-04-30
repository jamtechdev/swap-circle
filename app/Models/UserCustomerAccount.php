<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomerAccount extends Model
{
	protected $table="users_customers_accounts";

    protected $fillable = ['users_customers_id','system_currencies_id','full_name','iban'];
    protected $primaryKey = 'users_customers_accounts_id';
    public $timestamps = false;

    public function account_currency()
    {	
    	return $this->hasOne(SystemCurrency::class,'system_currencies_id','system_currencies_id');
    }
}