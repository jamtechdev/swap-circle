<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectArticleView extends Model
{
	protected $table="connect_articles_views";

    protected $fillable = ['users_customers_id','connect_articles_id'];

    protected $primaryKey = 'connect_articles_views_id';
    public $timestamps = false;

}