<?php

if (! function_exists('user')) {
    /**
     * Get current auth user.
     *
     * @return mixed
     */
    function user()
    {
        return auth()->user();
    }
}

if (! function_exists('trans_validation_attribute')) {
    /**
     * Translation for validation attribute.
     *
     * @param  string  $key
     * @return string
     */
    function trans_validation_attribute($key)
    {
        return trans("validation.attributes.{$key}");
    }
}

if (! function_exists('msg')) {
    /**
     * Flash success message.
     *
     * @param  string  $message
     * @param  string  $level
     * @param  string|null  $message_bag
     * @return void
     */
    function msg($message, $level = 'success', $message_bag = null)
    {
        $look_up = [
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-primary',
        ];

        session()->flash(
            'status',
            [
                'level' => $look_up[$level],
                'message' => $message,
                'message_bag' => $message_bag,
            ]
        );
    }
}

if (! function_exists('msg_success')) {
    /**
     * Flash success message.
     *
     * @param  string  $message
     * @param  string|null  $message_bag
     * @return void
     */
    function msg_success($message, $message_bag = null)
    {
        msg($message, 'success', $message_bag);
    }
}

if (! function_exists('msg_error')) {
    /**
     * Flash error message.
     *
     * @param  string  $message
     * @param  string|null  $message_bag
     * @return void
     */
    function msg_error($message, $message_bag = null)
    {
        msg($message, 'error', $message_bag);
    }
}

if (! function_exists('msg_info')) {
    /**
     * Flash info message.
     *
     * @param  string  $message
     * @param  string|null  $message_bag
     * @return void
     */
    function msg_info($message, $message_bag = null)
    {
        msg($message, 'info', $message_bag);
    }
}

if (! function_exists('msg_warning')) {
    /**
     * Flash warning message.
     *
     * @param  string  $message
     * @param  string|null  $message_bag
     * @return void
     */
    function msg_warning($message, $message_bag = null)
    {
        msg($message, 'warning', $message_bag);
    }
}
