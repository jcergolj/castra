<?php

if (! function_exists('user')) {
    function user(): mixed
    {
        return auth()->user();
    }
}

if (! function_exists('trans_validation_attribute')) {
    function trans_validation_attribute(string $key): string
    {
        return trans("validation.attributes.{$key}");
    }
}

if (! function_exists('msg')) {
    function msg(string $message, string $level = 'success', string|null $message_bag = null): void
    {
        $look_up = [
            'success' => 'sky',
            'error' => 'sky',
            'warning' => 'sky',
            'info' => 'sky',
        ];

        session()->flash(
            'status',
            [
                'color' => $look_up[$level],
                'message' => $message,
                'message_bag' => $message_bag,
            ]
        );
    }
}

if (! function_exists('msg_success')) {
    function msg_success(string $message, string|null $message_bag = null): void
    {
        msg($message, 'success', $message_bag);
    }
}

if (! function_exists('msg_error')) {
    function msg_error(string $message, string|null $message_bag = null): void
    {
        msg($message, 'error', $message_bag);
    }
}

if (! function_exists('msg_info')) {
    function msg_info(string $message, string|null $message_bag = null): void
    {
        msg($message, 'info', $message_bag);
    }
}

if (! function_exists('msg_warning')) {
    function msg_warning(string $message, string|null $message_bag = null): void
    {
        msg($message, 'warning', $message_bag);
    }
}
