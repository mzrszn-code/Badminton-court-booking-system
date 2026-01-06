<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_id',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the court under maintenance.
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Check if maintenance is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'in_progress' || 
               ($this->status === 'scheduled' && now()->between($this->start_date, $this->end_date));
    }
}
