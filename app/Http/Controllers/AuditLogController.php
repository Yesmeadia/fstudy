<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Strictly authorize Super Admin & Administrator
        if (! auth()->user()->isSuperAdmin() && ! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to system audit logs.');
        }

        $query = AuditLog::with('user')->latest();

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('model_type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query->paginate(25)->withQueryString();

        // High-level KPI metrics for Super Admin
        $stats = [
            'total_today' => AuditLog::whereDate('created_at', today())->count(),
            'logins_today' => AuditLog::where('action', 'user_login')->whereDate('created_at', today())->count(),
            'status_changes' => AuditLog::where('action', 'status_changed')->count(),
            'total_all_time' => AuditLog::count(),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('audit_logs.index', compact('logs', 'stats', 'users'));
    }
}
