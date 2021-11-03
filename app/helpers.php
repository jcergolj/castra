<?php

if (! function_exists('trans_validation_attribute')) {
    /**
     * Translation for validation attribute.
     *
     * @param  string  $message
     * @return void
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
     * @return void
     */
    function msg($message, $level = 'success')
    {
        $look_up = [
            'success' => 'green',
            'error' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
        ];

        session()->flash('status', ['message' => $message, 'level' => $look_up[$level]]);
    }
}

if (! function_exists('msg_success')) {
    /**
     * Flash success message.
     *
     * @param  string  $message
     * @return void
     */
    function msg_success($message)
    {
        msg($message, 'success');
    }
}

if (! function_exists('msg_error')) {
    /**
     * Flash error message.
     *
     * @param  string  $message
     * @return void
     */
    function msg_error($message)
    {
        msg($message, 'error');
    }
}

if (! function_exists('msg_info')) {
    /**
     * Flash info message.
     *
     * @param  string  $message
     * @return void
     */
    function msg_info($message)
    {
        msg($message, 'info');
    }
}

if (! function_exists('msg_warning')) {
    /**
     * Flash warning message.
     *
     * @param  string  $message
     * @return void
     */
    function msg_warning($message)
    {
        msg($message, 'warning');
    }
}
