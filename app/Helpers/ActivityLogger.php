<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $module,
        string $action,
        string $description,
        $oldData = null,
        $newData = null
    ) {

        ActivityLog::create([

            'user_id' => Auth::id(),

            'module' => $module,

            'action' => strtoupper($action),

            'description' => $description,

            'old_data' => $oldData
                ? json_encode($oldData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                : null,

            'new_data' => $newData
                ? json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                : null,

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),

        ]);

    }
}