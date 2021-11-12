<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $users = User::filter($request->all())
            ->orderByField($request->get('order_by', 'id'), $request->get('order_by_direction', 'asc'))
            ->paginate($request->get('per_page', AppServiceProvider::PER_PAGE));

        return view('admin.users.index', [
            'users' => $users,
            'per_page' => $request->get('per_page', AppServiceProvider::PER_PAGE),
            'order_by' => $request->get('order_by', 'email'),
            'order_by_direction' => $request->get('order_by_direction', 'asc')
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
    }

    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
    }

    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
    }
}
