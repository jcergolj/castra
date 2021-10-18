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
