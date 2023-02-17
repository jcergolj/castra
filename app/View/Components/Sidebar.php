<?php

namespace App\View\Components;

use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /** @var array */
    public $menu;

    public function __construct(protected User $user)
    {
        if ($user->id === null) {
            $this->user = user();
        } else {
            $this->user = $user;
        }
    }

    public function render(): View
    {
        $this->menu = $this->menuStrategy()->build();

        return view('components.layouts.sidebar');
    }

    /**
     * @throws Exception
     */
    public function menuStrategy(): mixed
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
