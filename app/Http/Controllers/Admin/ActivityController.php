<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

/**
 * Audit trail: who changed which company status, price, serial or user.
 */
class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::with(['causer', 'subject'])->latest();

        if ($request->filled('log')) {
            $query->where('log_name', $request->log);
        }
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('subject_id', $term)
                  ->orWhere('causer_id', $term)
                  ->orWhere('properties', 'like', "%{$term}%");
            });
        }

        $activities = $query->paginate(50)->withQueryString();

        $logs   = ['companies', 'assortiment', 'prices', 'serials', 'users'];
        $events = ['created', 'updated', 'deleted'];

        return view('admin.activity.index', compact('activities', 'logs', 'events'));
    }
}
