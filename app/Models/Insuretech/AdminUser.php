<?php

namespace App\Models\Insuretech;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $table = 'it_users';

    protected $fillable = ['name', 'email', 'password', 'status', 'last_login_at'];

    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'it_user_role', 'user_id', 'role_id');
    }

    public function hasRole($slug)
    {
        return $this->roles()->where('slug', $slug)->exists();
    }
}
