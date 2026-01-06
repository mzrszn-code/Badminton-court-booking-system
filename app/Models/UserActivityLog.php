<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_time',
        'description',
    ];

    protected $casts = [
        'activity_time' => 'datetime',
    ];

    /**
     * Get the user for this activity log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity.
     */
    public static function log(int $userId, string $type, string $description = null): self
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => $type,
            'description' => $description,
            'activity_time' => now(),
        ]);
    }
}
