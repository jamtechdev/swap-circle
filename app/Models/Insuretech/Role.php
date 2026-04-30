<?php

namespace App\Models\Insuretech;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'it_roles';

    protected $fillable = ['name', 'slug'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'it_role_permission', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(AdminUser::class, 'it_user_role', 'role_id', 'user_id');
    }
}
