<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteConnectArticle extends Model
{
	protected $table="connect_articles_favourite";

    protected $fillable = ['users_customers_id','connect_articles_id'];

    protected $primaryKey = 'connect_articles_favourite_id';
    public $timestamps = false;

}