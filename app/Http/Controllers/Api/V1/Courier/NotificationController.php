<?php

namespace App\Http\Controllers\Api\V1\Courier;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $notifications = $request->user()
            ->systemNotifications()
            ->where('company_id', $request->user()->company_id)
            ->latest()
            ->paginate(20);

        return $this->success([
            'items' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'unread_count' => $request->user()
                    ->systemNotifications()
                    ->where('company_id', $request->user()->company_id)
                    ->whereNull('read_at')
                    ->count(),
            ],
        ], 'Notificações carregadas.');
    }

    public function markAsRead(Request $request, UserNotification $notification)
    {
        abort_unless(
            $notification->user_id === $request->user()->id
            && $notification->company_id === $request->user()->company_id,
            404
        );

        $notification->forceFill([
            'read_at' => $notification->read_at ?? now(),
        ])->save();

        return $this->success($notification->fresh(), 'Notificação marcada como lida.');
    }
}
