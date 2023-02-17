<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordRule implements ImplicitRule
{
    public const MIN_PASSWORD_LENGTH = 8;

    /** @var array */
    public $failedRules = null;

    /** @var string */
    private $message = '';

    public function __construct(protected string|null $confirmationValue = null)
    {
    }

    /**
     * @throws ValidationException
     */
    public function passes(mixed $attribute, mixed $value): bool
    {
        $validator = Validator::make([
            $attribute => $value,
            $attribute.'_confirmation' => $this->confirmationValue,
        ], [
            $attribute => $this->rules(),
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            $this->message = $validator->getMessageBag()->first();
            $this->failedRules = $validator->failed();

            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function passwordDefaultsRules(): void
    {
        Password::defaults(function () {
            $rule = Password::min(self::MIN_PASSWORD_LENGTH);

            return config('app.env') === 'production'
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });
    }

    protected function rules(): array
    {
        $this->passwordDefaultsRules();

        $rules = ['required', Password::defaults()];

        if ($this->confirmationValue !== null) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }
}
