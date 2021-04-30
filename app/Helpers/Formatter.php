<?php

namespace App\Helpers;

/**
 * Class Formatter
 * @package App\Helpers
 */
class Formatter
{
    /**
     * @param $expression
     * @return false|string
     */
    public static function dateTime($expression)
    {
        $fmt = new \IntlDateFormatter('en_US', \IntlDateFormatter::MEDIUM, \IntlDateFormatter::FULL);
        $fmt->setPattern('MMM d, y, h:mm:ss a z');
        return $fmt->format($expression);
    }

    /**
     * @param $expression
     * @return false|string
     */
    public static function date($expression)
    {
        return (new \IntlDateFormatter('en_US', \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE))
            ->format($expression)
            ;
    }

    /**
     * @param $expression
     * @return false|string
     */
    public static function time($expression)
    {
        $fmt = new \IntlDateFormatter('en_US', \IntlDateFormatter::NONE, \IntlDateFormatter::FULL);
        $fmt->setPattern('h:mm:ss a z');
        return $fmt->format($expression);
    }

    /**
     * @param $expression
     * @return false|string
     */
    public static function money($expression)
    {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $expression < 1.00 ? 8 : 2);
        $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
        return $fmt->format($expression);
    }

    /**
     * @param $expression
     * @return false|string
     */
    public static function number($expression)
    {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $expression < 1.00 ? 8 : 2);
        $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
        return $fmt->format($expression);
    }

    /**
     * @param $expression
     * @return false|string
     */
    public static function numberFull($expression)
    {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
        $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
        return $fmt->format($expression);
    }

    /**
     * @param $expression
     * @return false|string
     */
    public static function numberShort($expression)
    {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
        return $fmt->format($expression);
    }

    /**
     * @param $expression
     * @return string
     */
    public static function change($expression)
    {
        return "<span class=\"" . ($expression < 0 ? 'text-danger' : 'text-success') . "\">{$expression}%</span>";
    }
}
