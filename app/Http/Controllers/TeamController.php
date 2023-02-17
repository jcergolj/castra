<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
    }

    public function create(): View
    {
    }

    public function store(Request $request): RedirectResponse
    {
    }

    public function show(Team $team): View
    {
    }

    public function edit(Team $team): View
    {
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
    }

    public function destroy(Team $team): RedirectResponse
    {
    }
}
