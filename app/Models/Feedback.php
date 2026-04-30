<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	protected $table="feedbacks";

    protected $fillable = ['users_customers_id','name','email','subject'];

    protected $primaryKey = 'feedbacks_id';
    public $timestamps = false;

}