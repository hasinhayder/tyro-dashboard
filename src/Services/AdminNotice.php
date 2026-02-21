<?php

namespace HasinHayder\TyroDashboard\Services;

class AdminNotice
{
    protected static $message = null;
    protected static $bgColor = null;
    protected static $textColor = null;
    protected static $align = null;

    public static function show($message, $bgColor = null, $textColor = null, $align = null)
    {
        self::$message = $message;
        self::$bgColor = $bgColor;
        self::$textColor = $textColor;
        self::$align = $align;
    }

    public static function hasNotice()
    {
        return !empty(self::$message) || (config('tyro-dashboard.admin_bar.enabled', false) && !empty(config('tyro-dashboard.admin_bar.message')));
    }

    public static function getMessage()
    {
        $message = self::$message ?: config('tyro-dashboard.admin_bar.message', '');
        return strip_tags($message, '<p><a><b><i><s><u><span>');
    }

    public static function getBgColor()
    {
        return self::$bgColor ?: config('tyro-dashboard.admin_bar.bg_color', '#000000');
    }

    public static function getTextColor()
    {
        return self::$textColor ?: config('tyro-dashboard.admin_bar.text_color', '#ffffff');
    }

    public static function getAlign()
    {
        return self::$align ?: config('tyro-dashboard.admin_bar.align', 'left');
    }

    public static function getHeight()
    {
        return config('tyro-dashboard.admin_bar.height', '40px');
    }
}
