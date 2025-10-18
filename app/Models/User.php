<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    // Relationship with roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // Check if user has a specific role
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    // Lead relationship (if users can have leads)
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}