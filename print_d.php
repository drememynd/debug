<?php

/**
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
require_once 'inc/debugBuilder.php';

/**
 * prints debug information
 *
 * $level parameter is optional - will use default (1 if not set otherwise)
 *
 * 0 in the $level parameter will suppress all backtrace information
 * printing the value only
 *
 * @param mixed $value the value to print - blank for only trace/timing info
 * @param int $level number of levels of backtrace to print
 */
function print_d($value = debugBuilder::_EMPTY_PARAM, $level = NULL)
{
    $d = debugBuilder::getDebugBuilder();
    $d->build(array($value), $level);
}

/**
 * prints debug information - for multiple parameters
 *
 * @param mixed $value,... the values to print - blank for only trace/timing info
 */
function multi_d($value = debugBuilder::_EMPTY_PARAM)
{
    $d = debugBuilder::getDebugBuilder();
    $d->build(func_get_args());
}

/**
 * Set up the program, include one or more of:
 *
 * addTime - whether to use the program timing feature<br>
 * fileName - the name of the output file<br>
 * filePath - the directory of the output file<br>
 * printToFile - output to a file if true, standard output if false<br>
 * timerSpacing - do not space between lines except for new file<br>
 * traceLevel - the number of backtrace lines to include in output<br>
 * useLabels - whether to use the program labeling system<br>
 * useWebTags - format with tags if true, do not if false
 *
 * @param array $params the array of setup variable names and values
 */
function setup_d($params = array())
{
    $d = debugBuilder::getDebugBuilder();
    $d->setup($params);
}
