<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;

class ActivityController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $activityModel = app()->make(Activity::class);

        $activities = $activityModel->filter($request->only(['event', 'causerId', 'subjectType', 'logName']))
            ->orderBy($request->get('order_by', 'id'), $request->get('order_by_direction', 'asc'))
            ->paginate($request->get('per_page', AppServiceProvider::PER_PAGE));

        return view('activities.index', [
            'activities' => $activities,
            'per_page' => $request->get('per_page', AppServiceProvider::PER_PAGE),
            'order_by' => $request->get('order_by', 'created_at'),
            'order_by_direction' => $request->get('order_by_direction', 'desc'),
        ]);
    }
}
