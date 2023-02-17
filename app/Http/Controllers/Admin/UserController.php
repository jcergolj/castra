<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request, User $user): View
    {
        $users = $user->filter($request->only(['search', 'role']))
            ->orderBy($request->get('order_by', 'id'), $request->get('order_by_direction', 'asc'))
            ->paginate($request->get('per_page', config('castra.per_page')));

        return view('admin.users.index', [
            'users' => $users,
            'per_page' => $request->get('per_page', config('castra.per_page')),
            'order_by' => $request->get('order_by', 'id'),
            'order_by_direction' => $request->get('order_by_direction', 'asc'),
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(! $user->exists, Response::HTTP_NOT_FOUND, 'User do not exists.');

        if ($user->isItMe()) {
            msg_error('You cannot delete yourself.');

            return back();
        }

        $user->delete();

        msg_success('User has been successfully deleted.');

        $request = Request::create(URL::previous());

        $paginator = $user->select('id')->filter($request->only(['search', 'role']))
            ->orderBy($request->get('order_by', 'id'), $request->get('order_by_direction', 'asc'))
            ->paginate($request->get('per_page', config('castra.per_page')));

        $toPage = $paginator->lastPage() <= (int) $request->page ? $paginator->lastPage() : $request->page;
        $request->merge(['page' => $toPage]);

        return redirect($request->fullUrlWithQuery($request->all()));
    }
}
