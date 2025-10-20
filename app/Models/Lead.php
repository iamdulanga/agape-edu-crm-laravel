<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Activity;

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
        'assigned_to',
        'avatar', // Add avatar to fillable
    ];

    protected $casts = [
        'inquiry_date' => 'date',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function assignTo(User $user, $currentUserId)
    {
        $this->update(['assigned_to' => $user->id]);

        Activity::create([
            'user_id' => $currentUserId,
            'lead_id' => $this->id,
            'action' => 'assigned',
            'description' => "Lead assigned to {$user->name}",
            'metadata' => ['assigned_to' => $user->id],
        ]);

        return $this;
    }

    public function unassign($currentUserId)
    {
        $this->update(['assigned_to' => null]);

        Activity::create([
            'user_id' => $currentUserId,
            'lead_id' => $this->id,
            'action' => 'unassigned',
            'description' => 'Lead unassigned',
        ]);

        return $this;
    }

    public function isAssigned()
    {
        return !is_null($this->assigned_to);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }
}
