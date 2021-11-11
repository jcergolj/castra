<?php

if (! function_exists('user')) {
    /**
     * Get current auth user.
     *
     * @return mixed
     */
    function user()
    {
        return request()->user();
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
     * @param  string|null  $undo_url
     * @return void
     */
    function msg($message, $level = 'success', $message_bag = null, $undo_url = null)
    {
        $look_up = [
            'success' => ['color' => 'green', 'title' => 'Success'],
            'error' => ['color' => 'red', 'title' => 'Error'],
            'warning' => ['color' => 'yellow', 'title' => 'Warning'],
            'info' => ['color' => 'blue', 'title' => 'Info'],
        ];

        session()->flash(
            'status',
            [
                'title' => $look_up[$level]['title'],
                'color' => $look_up[$level]['color'],
                'message' => $message,
                'svg' => "svg.{$level}",
                'message_bag' => $message_bag,
                'undo_url' => $undo_url,
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

if (! function_exists('msg_success_with_undo')) {
    /**
     * Flash success message.
     *
     * @param  string  $message
     * @param  string  $undo_url
     * @param  string|null  $message_bag
     * @return void
     */
    function msg_success_with_undo($message, $undo_url, $message_bag = null)
    {
        msg($message, 'success', $message_bag, $undo_url);
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
