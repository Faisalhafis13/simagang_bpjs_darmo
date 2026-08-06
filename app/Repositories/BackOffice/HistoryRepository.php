<?php

namespace App\Repositories\BackOffice;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;

class HistoryRepository
{
    public function index()
    {
        return View::make('back-office.history.index');
    }

    public function getData()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'data' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user' => optional($log->user)->name ?? 'System',
                    'module' => $log->module,
                    'action' => $log->action,
                    'description' => $log->description,
'old_data' => $log->old_data
    ? json_encode(json_decode($log->old_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    : null,

'new_data' => $log->new_data
    ? json_encode(json_decode($log->new_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    : null,                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];

        return response()->json($data);
    }
}
