<?php

/**
 * A timer - creates and returns a string with current and elapsed time
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugTimer
{

    private static $lasttime = false;

    /**
     * gets a string with current and elapsed time
     *
     * @return string the time string
     */
    public function getTimeString()
    {
        $time = '';

        $now = microtime(true);
        $time .= $this->microTimeStr($now);
        $time .= ' || ';
        $time .= $this->elapsedTime($now);

        return $time;
    }

    /**
     * builds the elapsed time string
     *
     * @param float $now microtime value as float
     * @return string the elapsed time string
     */
    private function elapsedTime($now)
    {
        $last = (self::$lasttime === false) ? $now : self::$lasttime;
        $diff = $now - $last;

        $elapsed = $this->microDiffStr($diff);

        self::$lasttime = $now;
        return $elapsed;
    }

    /**
     * builds a string from a microtime float
     *
     * @param float $microtime a microtime float
     * @return string a string representing the time passed in
     */
    private function microTimeStr($microtime)
    {
        $long = floor($microtime);
        $micro = round(($microtime - $long) * 1000);
        $time = date('H:i:s', $long);
        $time .= ':';
        $time .= str_pad($micro, 3, '0', STR_PAD_LEFT);

        return $time;
    }

    /**
     * builds a string from the difference between two microtime floats
     *
     * @param float $microtime the difference between two microtime floats
     * @return string the string that was built
     */
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
