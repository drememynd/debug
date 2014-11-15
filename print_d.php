<?php

require_once 'printer.php';

/*
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 */

/**
 * prints debug information
 *
 * 0 in the $level parameter will suppress all backtrace informatin
 * printing the value only
 *
 * @param mixed $value the value to print
 * @param int $level number of levels of backtrace to print
 * @param string $file a file name or path to print to *this time*
 */
function print_d($value = 'z||z', $level = 1)
{
    $d = debug_printer::getDebugPrinter();
    $d->out($value, $level);
}

/**
 * @param mixed $name any number of value args to print.
 */
function print_multi()
{
    $d = debug_printer::getDebugPrinter();
    $d->multi(func_get_args());
}

function set_print_d_to_file($tofile = false)
{
    $d = debug_printer::getDebugPrinter();
    $d->setPrintToFile($tofile);
}

function set_print_d_filepath($filepath = '')
{
    $d = debug_printer::getDebugPrinter();
    $d->setPath($filepath);
}

function set_print_d_filename($filename = '')
{
    $d = debug_printer::getDebugPrinter();
    $d->setFile($filename);
}
