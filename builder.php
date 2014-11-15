<?php

require_once 'timer.php';

class debug_builder
{

    private static $place;

    public function build($args, $level = 1, $place = false)
    {
        self::$place = false;

        $level = $this->setLevel($level);
        $space = ($level == 0) ? '' : "\n";
        $str = $space . $this->getDebugString('z||z', $level);

        $temp = array();
        if(!empty($args)) {
            foreach($args as $a) {
                $temp[] = $this->getDebugString($a, 0);
            }
        }
        $str .= implode("\n", $temp);

        $this->goPrint($str, $file);
    }

    private function getDebugString($value, $level = 1)
    {
        $printString = '';
        $traceNum = 5;

        $bt = new debug_backtrace();
        $trace = $bt->getTrace($traceNum);

        $addTime = true;
        $temp = array();
        if($level > 0) {
            for($i = 0; $i < $level; $i++) {
                $t = $bt->getTrace($traceNum);
                $temp[] = $this->getTraceString($t, $addTime);
                $traceNum++;
                $addTime = false;
            }
        }
        $printString .= implode("\n", $temp);
        if(!empty($temp)) {
            $printString .= "\n";
        }


        if(is_string($value)) {
            $value = trim($value);
        }

        if($value !== 'z||z') {

            $label = $this->getValueLabel($trace['file'], $trace['line']);

            if(!empty($label)) {
                //$label = trim($label,'$');
                $printString .= $label . ': ';
            }
            $v = print_r($value, 1);
            $printString .= $v;
        }

        return $printString;
    }

    private function getValueLabel($file, $line, $d = false)
    {
        $label = '';
        $contents = file($file);

        $fLine = $contents[$line - 1];
        $len = strlen($fLine);
        $d = (self::$place === false) ? strpos($fLine, 'print_d') : self::$place;
        $dol = strpos($fLine, '$', $d);
        if($dol !== false) {
            $com1 = strpos($fLine, ',', $dol);
            $par1 = strpos($fLine, ')', $dol);
            $com = ($com1 === false) ? $len : $com1;
            $par = ($par1 === false) ? $len : $par1;
            $sub = min($com, $par, $len);
            $label = trim(substr($fLine, $dol, $sub - ($dol)));
            self::$place = $sub;
        }

        return $label;
    }

    private function getTraceString($trace, $time = false)
    {
        $print = '';
        if($trace['file'] != self::$lastFile) {
            $print = ($trace['file'] == 'none') ? '' : $trace['file'] . "\n";
        }
        self::$lastFile = $trace['file'];

        $print .= $trace['line'] . '::';
        $print .= empty($trace['class']) ? 'none' : $trace['class'];
        $print .= empty($trace['function']) ? '::none' : '::' . $trace['function'];
        $print .= ' ';
        $now = microtime(true);
        $print .= $this->microTimeStr($now);
        if($time) {
            $print .= ' || ';
            $print .= $this->elapsedTime($now);
        }

        return $print;
    }

}
