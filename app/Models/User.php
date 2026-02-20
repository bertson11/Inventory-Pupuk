<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'phone',
        'position',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function approvedTransactions()
    {
        return $this->hasMany(Transaction::class, 'approved_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKtu()
    {
        return $this->role === 'ktu';
    }

    public function isKrani()
    {
        return $this->role === 'krani';
    }

    public function getRoleBadgeAttribute()
    {
        $badges = [
            'admin' => '<span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs"><i class="fa-solid fa-crown mr-1"></i>Admin</span>',
            'ktu' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs"><i class="fa-solid fa-check-double mr-1"></i>KTU</span>',
            'krani' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs"><i class="fa-solid fa-warehouse mr-1"></i>Krani</span>',
        ];
        
        return $badges[$this->role] ?? $this->role;
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs"><i class="fa-solid fa-circle mr-1"></i>Aktif</span>'
            : '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs"><i class="fa-solid fa-circle mr-1"></i>Nonaktif</span>';
    }
}