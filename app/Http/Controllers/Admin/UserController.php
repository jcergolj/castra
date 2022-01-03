<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityEvents;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userModel = app()->make(User::class);
        $users = $userModel->filter($request->only(['search', 'role']))
            ->orderBy($request->get('order_by', 'id'), $request->get('order_by_direction', 'asc'))
            ->paginate($request->get('per_page', AppServiceProvider::PER_PAGE));

        return view('admin.users.index', [
            'users' => $users,
            'per_page' => $request->get('per_page', AppServiceProvider::PER_PAGE),
            'order_by' => $request->get('order_by', 'id'),
            'order_by_direction' => $request->get('order_by_direction', 'asc'),
        ]);
    }

    /** @return \Illuminate\View\View */
    public function create()
    {
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
    }

    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        abort_if(! $user->exists, Response::HTTP_NOT_FOUND, 'User do not exists.');

        if ($user->isItMe()) {
            msg_error('You cannot delete yourself.');

            return back();
        }

        $user->delete();

        activity()
            ->performedOn($user)
            ->causedBy(user())
            ->withProperty('restore_url', route('admin.users.restore', $user))
            ->event(ActivityEvents::user_deleted->name)
            ->log(ActivityEvents::user_deleted->name);

        msg_success_with_undo('User has been successfully deleted.', route('admin.users.restore', $user));

        return back();
    }
}
