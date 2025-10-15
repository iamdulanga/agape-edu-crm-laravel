<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'age',
        'city',
        'passport',
        'inquiry_date',
        'study_level',
        'priority',
        'preferred_universities',
        'special_notes',
        'status',
        'assigned_to'
    ];

    protected $casts = [
        'inquiry_date' => 'date',
    ];

    // Relationship with assigned user
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Get full name attribute
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}