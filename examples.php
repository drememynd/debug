<?php

/**
 * PHP file with usage examples
 *
 * NOTES:
 * The file path will only be printed when the file changes between calls.
 * There will be one line of spacing before backtrace information
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
require_once 'print_d.php';
require_once 'debug.php';

$test = 'example';
$array = array('foo' => 'value', 'bar' => 'value');

/* basic usage - no parameters
 * outputs backtrace and timer information */
print_d();
// C:\xampp\htdocs\debug\examples.php
// 22::none::none 16:20:35:109 || 00:00:00:000

/* basic usage - variable
 * notice that because we are in the same file, there is no file path */
print_d($test);
// 28::none::none 16:20:35:109 || 00:00:00:000
// $test: example

/* basic usage, defined constant */
print_d(__LINE__);
// 33::none::none 16:29:01:228 || 00:00:00:000
// __LINE__: 29

/* basic usage, string */
print_d('hello world');
// 38::none::none 16:33:36:943 || 00:00:00:001
// hello world


/* basic usage, array in variable */
print_d($array);
// 44::none::none 16:44:30:386 || 00:00:00:001
// $array: Array
// (
//     [foo] => value
//     [bar] => value
// )


/* basic usage, varaible, backtrace level == 0
 * no backtrace or timing information, also no spacing before
 * this will visually append the output to the output above */
print_d($test, 0);
// $test: example

/* basic usage, variable, backtrace level 3
 * three lines of backtrace information
 * timer information only appears on the first
 * backtrace line.  */
print_d($test, 3);
// 63::none::none 16:41:45:527 || 00:00:00:001
// none::none::none
// none::none::none
// $test: example

/* using the debug class works exactly the same way
 * notice how subsequent calls with backtrace level 0
 * are appended to output */
$bug = new debug();
$bug->out($test);
$bug->out(__LINE__, 0);
$bug->out($array, 0);
// 73::none::none 16:54:52:084 || 00:00:00:001
// $test: example
// __LINE__: 75
// $array: Array
// (
//     [foo] => value
//     [bar] => value
// )

/* using the multi parameter call
 * produces the same output as the three calls above
 * these two calls are equivalent */
multi_d($test, __LINE__, $array);
$bug->multi($test, __LINE__, $array);
// 89::none::none 17:47:17:478 || 00:00:00:001
// $test: example
// __LINE__: 90
// $array: Array
// (
//     [foo] => value
//     [bar] => value
// )

/* there are configuration values which can be changed.
 *
 * addTime - whether to use the program timing feature<br>
 * fileName - the name of the output file<br>
 * filePath - the directory of the output file<br>
 * printToFile - output to a file if true, standard output if false<br>
 * traceLevel - the number of backtrace lines to include in output<br>
 * useLabels - whether to use the program labeling system<br>
 * useWebTags - format with tags if true, do not if false
 *
 * note that the sample below prints no timer information,two levels of
 * backtrace data and does not print a label for the value */
$config['addTime'] = false;
$config['traceLevel'] = 2;
$config['useLabels'] = false;
setup_d($config);
print_d($test);
// 115::none::none
// none::none::none
// example

/* this setup will effect the default instance - used by both print_d and
 * by the class unless it's instantiated otherwise */
$bug->out($test);
// 122::none::none
// none::none::none
// example

/* one of the thigs which makes the class more versitle, is the ability to set
 * up more than one instace by passing in an identifier upon instantiation
 * note that the output from this class will be the default output and will
 * include the file path, because the it's a new instance */
$new = new debug('new');
$new->out($test);
// C:\xampp\htdocs\debug\examples.php
// 131::none::none 18:23:04:653 || 00:00:00:001
// $test: example

/* the default instance is still set up the way it was */
print_d($test);
// 138::none::none
// none::none::none
// example

/* if we change the configuration on the new instance, it effects only that
 * instance.  this feature is especially useful for printing different kinds
 * of output to multiple output files
 * note that the output is formatted poorly because it's being printed to
 * a web page and is formatted for file output */
$new->setup(array('useWebTags' => false));
$new->out($array);
// 149::none::none 18:37:13:294 || 00:00:00:001 $array: Array ( [foo] => value [bar] => value )
