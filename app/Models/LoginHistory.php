<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_history';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'successful',
        'login_at',
    ];

    protected $casts = [
        'successful' => 'boolean',
        'login_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to parse user agent
    public static function parseUserAgent($userAgent)
    {
        $deviceType = 'desktop';
        $browser = 'Unknown';

        // Simple device detection
        if (preg_match('/mobile|android|iphone|ipad|tablet/i', $userAgent)) {
            if (preg_match('/tablet|ipad/i', $userAgent)) {
                $deviceType = 'tablet';
            } else {
                $deviceType = 'mobile';
            }
        }

        // Simple browser detection
        if (preg_match('/chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/firefox/i', $userAgent)) $browser = 'Firefox';
        elseif (preg_match('/safari/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/edge/i', $userAgent)) $browser = 'Edge';
        elseif (preg_match('/opera/i', $userAgent)) $browser = 'Opera';

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
        ];
    }
}
