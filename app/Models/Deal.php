<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_amount',
        'discount_percentage',
        'service_id',
        'start_date',
        'end_date',
        'is_hidden',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'deal_service');
    }
}
