<?php

namespace App\Models\Insuretech;

use Illuminate\Database\Eloquent\Model;

class PartnerApiToken extends Model
{
    protected $table = 'it_partner_api_tokens';

    protected $fillable = [
        'partner_id',
        'name',
        'token_hash',
        'token_prefix',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
