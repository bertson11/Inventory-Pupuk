<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'supplier_id',
        'type',
        'quantity',
        'date',
        'destination',      // untuk stok keluar
        'reference',        // no invoice/SP
        'notes',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getTypeBadgeAttribute()
    {
        return $this->type == 'masuk' 
            ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-arrow-down mr-1"></i>Masuk</span>'
            : '<span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-arrow-up mr-1"></i>Keluar</span>';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-clock mr-1"></i>Pending</span>',
            'approved' => '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-check mr-1"></i>Disetujui</span>',
            'rejected' => '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-times mr-1"></i>Ditolak</span>',
        ];
        
        return $badges[$this->status] ?? $this->status;
    }
}