<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityEvents;
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

        $this->logDeletedUsers($request->ids);

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

    /**
     * @param  array  $ids
     * @return void
     */
    private function logDeletedUsers($ids)
    {
        foreach($ids as $id) {
            $deletedUser = User::withTrashed()->find($id);

            activity()
                ->performedOn($deletedUser)
                ->causedBy(user())
                ->withProperty('restore_url', route('admin.users.restore', $deletedUser))
                ->event(ActivityEvents::user_deleted->name)
                ->log(ActivityEvents::user_deleted->name);
        }
    }
}
