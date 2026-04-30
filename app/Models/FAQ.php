<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
	protected $table="faqs";

    protected $fillable = ['question','answer'];

    protected $primaryKey = 'faqs_id';
    public $timestamps = false;

}