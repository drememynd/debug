<?php

require_once 'debugBacktrace.php';
require_once 'debugTimer.php';
require_once 'debugLabel.php';

/*
 * Put the debug string together
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */

class debugString
{

    /**
     * @var debugLabel
     */
    private $label;

    /**
     * @var debugTimer
     */
    private $timer;

    /**
     * @var debugSetup
     */
    private $ds;

    /**
     * The trace number to look at for initial trace information
     *
     * @var int
     */
    private $traceNum = 4;
    private $lastFile = '';

    public function __construct($ds)
    {
        $this->ds = $ds;
        $this->timer = new debugTimer();
        $this->label = new debugLabel($this->ds);
    }

    public function get($value, $level = NULL, $new = true)
    {
        $printString = '';

        if($level === NULL) {
            $level = $this->ds->getTraceLevel();
        }

        $bt = new debugBacktrace();
        $bt->makeTraces();

        if($level > 0) {
            $printString .= $this->buildTraces($bt, $level);
        }


        if(is_string($value)) {
            $value = trim($value);
        }

        if($value !== debugBuilder::_EMPTY_PARAM) {

            $label = '';
            if($this->ds->getUseLabels()) {
                $label = $this->labelString($bt, $value, $new);
            }

            if(!empty($label)) {
                $printString .= $label . ': ';
            }
            $v = print_r($value, 1);
            $printString .= $v;
        }

        return $printString;
    }

    private function labelString($bt, $value, $new)
    {
        $trace = $bt->getTrace($this->traceNum, false);
        $fTrace = $bt->getTrace($this->traceNum - 1, false);
        $function = ($fTrace['class'] == 'none') ? '' : '->';
        $function .= $fTrace['function'];

        return $this->label->getValueLabel($trace['file'], $trace['line'], $function, $value, $new);
    }

    private function buildTraces($bt, $level)
    {
        $temp = array();
        $traceNum = $this->traceNum;
        $addTime = false;
        if($this->ds->getAddTime()) {
            $addTime = true;
        }

        for($i = 0; $i < $level; $i++) {
            $t = $bt->getTrace($traceNum, false);
            $temp[$i] = $this->getTraceString($t);

            if($addTime) {
                $temp[$i] .= ' ';
                $temp[$i] .= $this->timer->getTimeString();
            }

            $traceNum++;
            $addTime = false;
        }

        $printString = '';
        if(!empty($temp)) {
            $printString .= implode("\n", $temp);
            $printString .= "\n";
        }

        return $printString;
    }

    private function getTraceString($trace)
    {
        $print = '';
        if($trace['file'] != $this->lastFile) {
            $print = ($trace['file'] == 'none' ) ? '' : $trace['file'] . "\n";
        }
        $this->lastFile = $trace['file'];

        $print .= $trace['line'] . '::';
        $print .= empty($trace['class']) ? 'none' : $trace['class'];
        $print .= empty($trace['function']) ? '::none' : '::' . $trace['function'];

        return $print;
    }

}
