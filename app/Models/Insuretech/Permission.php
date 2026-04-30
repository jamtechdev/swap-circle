<?php

namespace App\Models\Insuretech;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'it_permissions';

    protected $fillable = ['name', 'slug'];
}
