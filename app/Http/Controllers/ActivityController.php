<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activityModel = app()->make(Activity::class);

        $activities = $activityModel->with(['causer', 'subject'])
            ->filter($request->only(['activity_event', 'search']))
            ->orderBy($request->get('order_by', 'created_at'), $request->get('order_by_direction', 'desc'))
            ->paginate($request->get('per_page', config('castra.per_page')));

        return view('activities.index', [
            'activities' => $activities,
            'per_page' => $request->get('per_page', config('castra.per_page')),
            'order_by' => $request->get('order_by', 'created_at'),
            'order_by_direction' => $request->get('order_by_direction', 'desc'),
        ]);
    }
}
