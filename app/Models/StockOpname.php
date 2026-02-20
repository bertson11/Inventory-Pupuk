<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 'stock_opname';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'system_stock',
        'physical_stock',
        'difference',
        'notes',
        'created_by'
    ];
}