<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log a system activity.
     *
     * @param string $activity (e.g. LOGIN, LOGOUT, LOGIN_FAILED, PROFILE_UPDATED, PASSWORD_CHANGED, USER_CREATED, USER_UPDATED, USER_DISABLED)
     * @param string|null $description
     * @param int|null $userId
     * @return ActivityLog
     */
    public static function log(string $activity, ?string $description = null, ?int $userId = null): ActivityLog
    {
        $id = $userId ?? Auth::id();

        return ActivityLog::create([
            'user_id' => $id,
            'activity' => strtoupper($activity),
            'description' => $description,
            'ip' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'Unknown Agent',
        ]);
    }
}
