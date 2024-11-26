<?php

namespace App\Models;

use App\Enums\AppointmentStatusEnum;
use App\Jobs\SendAppointmentConfirmationMailJob;
use App\Jobs\SendNewServicePromoMailJob;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'appointment_code',
        'cart_id',
        'user_id',
        'service_id',
        'date',
        'time',
        'employee_id',
        'total',
        'status',
        'cancellation_reason',
        'notes',
        'first_name',
        'pay_method',
        'proof_of_payment',
        'reference_number',
    ];

    protected $casts = [
        'first_name' => 'string',
        'date' => 'date',
        'time' => 'datetime:H:i',
        'total' => 'float',
        'status' => AppointmentStatusEnum::class, // Example: use Enum for status
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors and Mutators
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->last_name ?? '');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    // Boot Method
    static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            // Generate a unique appointment code
            $latestAppointment = static::latest('id')->first();
            $nextId = $latestAppointment ? $latestAppointment->id + 1 : 1;
            $appointment->appointment_code = 'APP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        });

        static::updated(function ($appointment) {
            if ($appointment->isDirty('status')) {
                // Logic for status change
            }
        });

        static::deleted(function ($appointment) {
            // Cleanup or notification logic on delete
        });
    }
}
