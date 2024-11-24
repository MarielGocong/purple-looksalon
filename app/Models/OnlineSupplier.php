<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'contact',
        'address',
    ];

    public function supply()
    {
        return $this->hasMany(Supply::class);
    }

}
