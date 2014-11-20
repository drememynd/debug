<?php

/**
 * Creates easy to understand backtrace array
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugBacktrace
{

    private $trace = array();
    private $empty;

    /**
     * sets up a default empty trace
     */
    public function __construct()
    {
        $this->empty = array(
            'file' => 'none',
            'line' => 'none',
            'class' => 'none',
            'function' => 'none',
        );
    }

    /**
     * checks the PHP version to see if a backtrace arg is required
     *
     * @return boolean whether or not the arg is required
     */
    private function useBacktraceArg()
    {
        $version = PHP_VERSION;
        $vers = explode('.', $version);
        $test = array('5', '3', '6');

        return ($vers >= $test);
    }

    /**
     * get all the traces
     *
     * @param boolean $make whether to make new traces, or just return what we have
     * @return array of all the traces
     */
    public function getTraces($make = true)
    {
        if($make || empty($this->trace)) {
            $this->makeTraces();
        }
        return $this->trace;
    }

    /**
     * gets a single trace, at the index $level
     *
     * @param int $level the trace index
     * @param boolean $make whether to make new traces, or just return what we have
     * @return array one array of trace information
     */
    public function getTrace($level, $make = true)
    {
        if($make || empty($this->trace)) {
            $this->makeTraces();
        }
        $cnt = count($this->trace);
        if($level < $cnt) {
            return $this->trace[$level];
        } else {
            return $this->empty;
        }
    }

    /**
     * build the array of traces
     */
    public function makeTraces()
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
                $trace = $this->fillTrace($trace, $a, $alt);
            }
        }
    }

    /**
     * fill one trace during the array build
     *
     * @param array $trace the trace array
     * @param int $a the loop index
     * @param int $alt an adjuster
     * @return array $trace the trace array
     */
    private function fillTrace($trace, $a, $alt)
    {
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

        return $trace;
    }

}
