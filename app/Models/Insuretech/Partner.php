<?php

namespace App\Models\Insuretech;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'it_partners';

    protected $fillable = ['partner_code', 'name', 'slug', 'base_url', 'status', 'settings'];

    protected $casts = ['settings' => 'array'];

    public function tokens()
    {
        return $this->hasMany(PartnerApiToken::class, 'partner_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'it_partner_product', 'partner_id', 'product_id')->withTimestamps();
    }
}
