<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'image',
        'phone_number',
        'birthday',
        'address',
        'date_started',
        'job_category_id',
        'status',
        'is_hidden',
        'working_days',
        'email',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
        'working_days' => 'array',
        'status' => 'boolean',
    ];

    public function scopeIsVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function equipments()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service');
    }



}
