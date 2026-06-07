<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->action) {
            $query->where('action', $request->action);
        }
        if ($request->model_type) {
            $query->where('model_type', $request->model_type);
        }
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%'.$request->search.'%'));
        }

        $logs = $query->paginate(20);

        return view('admin.audit-logs', compact('logs'));
    }
}