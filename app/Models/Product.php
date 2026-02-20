<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'code', 'name', 'description', 
        'stock', 'min_stock', 'unit', 'price', 'image_url', 'barcode'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) return 'Kritis';
        if ($this->stock <= $this->min_stock) return 'Menipis';
        return 'Aman';
    }

    public function getStockStatusColorAttribute()
    {
        return [
            'Aman' => 'green',
            'Menipis' => 'yellow',
            'Kritis' => 'red'
        ][$this->stock_status] ?? 'gray';
    }
}