<?php

namespace Tests;

use Illuminate\Validation\Rules\Password;

class FakePassword extends Password
{
    protected $password;

    protected $min;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public static function getMin()
    {
        return Password::default()->min;
    }

    public static function getUncompromised()
    {
        return Password::default()->uncompromised;
    }

    public function getMixedCase()
    {
        return Password::default()->mixedCase;
    }
}
