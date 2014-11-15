<?php

class debug_timer
{

    private static $lasttime;

    public function __construct()
    {
        self::$lasttime = false;
    }

    private function elapsedTime($now)
    {
        $last = (self::$lasttime === false) ? $now : self::$lasttime;
        $diff = $now - $last;

        $elapsed = $this->microDiffStr($diff);

        self::$lasttime = $now;
        return $elapsed;
    }

    private function microTimeStr($microtime)
    {
        $long = floor($microtime);
        $micro = round(($microtime - $long) * 1000);
        $time = date('H:i:s', $long);
        $time .= ':';
        $time .= str_pad($micro, 3, '0', STR_PAD_LEFT);

        return $time;
    }

    private function microDiffStr($microtime)
    {
        $m = 60;
        $h = $m * 60;

        $diff = floor($microtime);
        $micro = round(($microtime - $diff) * 1000);

        $secs = $diff % $m;
        $diff -= $secs;
        $mins = $diff % $h;
        $diff -= $mins;
        $hours = $diff;

        $hours /= $h;
        $mins /= $m;

        $elapsed = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $elapsed .= ':';
        $elapsed .= str_pad($mins, 2, '0', STR_PAD_LEFT);
        $elapsed .= ':';
        $elapsed .= str_pad($secs, 2, '0', STR_PAD_LEFT);
        $elapsed .= ':';
        $elapsed .= str_pad($micro, 3, '0', STR_PAD_LEFT);

        return $elapsed;
    }

}
