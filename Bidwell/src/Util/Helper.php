<?php
namespace Bidwell\Util;

use DateInterval;

class Helper {
    public static function intervalToMilliseconds(DateInterval $interval) : int {
        return ((($interval->h * 60) + $interval->i) * 60 + $interval->s) * 1000 + $interval->f / 1000;
    }

    public static function intervalToSeconds(DateInterval $interval) : int {
        return (($interval->h * 60) + $interval->i) * 60 + $interval->s;
    }
}