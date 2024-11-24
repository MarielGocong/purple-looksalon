<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'quantity',
        'category_id',
        'color_code',
        'color_shade',
        'size',
        'status',
        'expiration_date',
        'online_supplier_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function online_supplier()
    {
        return $this->belongsTo(OnlineSupplier::class);
    }
}
