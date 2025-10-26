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
        'avatar',
    ];

    protected $casts = [
        'inquiry_date' => 'date',
    ];

    /**
     * Note: The assigned_to column and related assignment functionality have been removed.
     * The following methods were removed: assignedUser(), assignTo(), unassign(),
     * isAssigned(), scopeAssignedTo(), scopeUnassigned()
     * If assignment functionality is needed in the future, it should be implemented
     * through a separate assignment tracking table rather than a column on the leads table.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
