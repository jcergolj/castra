<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDeletedUserRequest;
use App\Models\User;

class DeletedUserController extends Controller
{
    /**
     * @param  \App\Http\Requests\Admin\StoreDeletedUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDeletedUserRequest $request)
    {
        User::destroy($request->ids);

        msg_success_with_undo('Users has been successfully deleted.', route('activities.index'));

        return back();
    }

    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->restore();

        msg_success('User has been successfully restored.');

        return back();
    }
}
