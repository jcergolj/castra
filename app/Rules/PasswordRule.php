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
    protected $confirmationValue;

    /** @var string */
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @param  string  $confirmationValue
     * @return void
     */
    public function __construct($confirmationValue = null)
    {
        $this->confirmationValue = $confirmationValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passes($attribute, $value)
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

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Set validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        $rules = ['required', Password::defaults()];

        if ($this->confirmationValue !== null) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }
}
