<?php

namespace App\View\Components;

use App\Models\User;
use Exception;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /** @var array */
    public $menu;

    /** @var \App\Models\User */
    protected $user;

    /**
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        if ($user->id === null) {
            $this->user = user();
        } else {
            $this->user = $user;
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->menu = $this->menuStrategy()->build();

        return view('components.layouts.sidebar');
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function menuStrategy()
    {
        $menus = [
            'admin' => 'AdminNav',
            'member' => 'MemberNav',
        ];

        if (! array_key_exists($this->user->role->value, $menus)) {
            throw new Exception('Nav class does not exists.');
        }

        $class = "App\Services\\".$menus[$this->user->role->value];

        return new $class();
    }
}
