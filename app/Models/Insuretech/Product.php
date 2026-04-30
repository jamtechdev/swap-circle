<?php

namespace App\Models\Insuretech;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'it_products';

    protected $fillable = [
        'product_code',
        'name',
        'slug',
        'description',
        'currency',
        'price',
        'cover_duration_rule',
        'status',
    ];

    public function fields()
    {
        return $this->hasMany(ProductField::class, 'product_id')->orderBy('sort_order');
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'it_partner_product', 'product_id', 'partner_id')->withTimestamps();
    }
}
