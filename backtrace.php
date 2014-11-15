<?php

/*
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 */

class debug_backtrace
{

    private $trace;
    private $empty;

    public function __construct()
    {
        $this->empty = array(
            'file' => 'none',
            'line' => 'none',
            'class' => 'none',
            'function' => 'none',
        );
    }

    private function useBacktraceArg()
    {
        $version = PHP_VERSION;
        $vers = explode('.', $version);
        $test = array('5', '3', '6');

        return ($vers >= $test);
    }

    public function getTraces()
    {
        $this->makeTraces();
        return $this->trace;
    }

    public function getTrace($level = 0)
    {
        $this->makeTraces();
        $cnt = count($this->trace);
        if($level < $cnt) {
            return $this->trace[$level];
        } else {
            return $this->empty;
        }
    }

    private function makeTraces()
    {
        if($this->useBacktraceArg()) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        } else {
            $trace = debug_backtrace();
        }

        $this->trace = array();
        if(!empty($trace)) {

            $this->trace[0]['file'] = __FILE__;
            $this->trace[0]['line'] = __LINE__;
            $this->trace[0]['class'] = __CLASS__;
            $this->trace[0]['function'] = __FUNCTION__;

            $cnt = count($trace);

            $alt = 0;
            for($a = 0; $a < ($cnt); $a++) {

                $i = $a - $alt;
                $j = $i + 1;
                $x = $a;
                $y = $a + 1;

                $trace[$x]['file'] = empty($trace[$x]['file']) ? 'none' : $trace[$x]['file'];
                $trace[$x]['line'] = empty($trace[$x]['line']) ? 'none' : $trace[$x]['line'];
                $trace[$y]['class'] = empty($trace[$y]['class']) ? 'none' : $trace[$y]['class'];
                $trace[$y]['function'] = empty($trace[$y]['function']) ? 'none' : $trace[$y]['function'];

                $this->trace[$j]['file'] = empty($trace[$i]['file']) ? 'none' : $trace[$i]['file'];
                $this->trace[$j]['line'] = empty($trace[$i]['line']) ? 'none' : $trace[$i]['line'];
                $this->trace[$j]['class'] = empty($trace[$j]['class']) ? 'none' : $trace[$j]['class'];
                $this->trace[$j]['function'] = empty($trace[$j]['function']) ? 'none' : $trace[$j]['function'];
            }
        }
    }

}
