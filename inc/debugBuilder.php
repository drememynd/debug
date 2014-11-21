<?php

require_once 'debugPrinter.php';
require_once 'debugString.php';
require_once 'debugSetup.php';

/**
 * builds and outputs the debug string
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugBuilder
{

    const _EMPTY_PARAM = 'z||z';
    const _DEFAULT_BUILDER_TYPE = 'default_type';

    /**
     * @var array[debugBuilder]
     */
    private static $dbs;

    /**
     * @var debugPrinter
     */
    private $printer;

    /**
     * @var debugString
     */
    private $string;

    /**
     * @var debugSetup
     */
    private $ds;

    private function __construct()
    {
        $this->ds = new debugSetup();
        $this->string = new debugString($this->ds);
        $this->printer = new debugPrinter($this->ds);
    }

    /**
     * retrieves a debugBuilder object<br>
     * NOTE: there can be multiple objects with different setups
     *
     * @return printer
     */
    public static function getDebugBuilder($type = self::_DEFAULT_BUILDER_TYPE)
    {
        if(empty(self::$dbs[$type])) {
            self::$dbs[$type] = new debugBuilder();
        }

        return self::$dbs[$type];
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
    public function setup($params = array())
    {
        ksort($params);
        foreach($params as $name => $value) {
            $this->ds->$name = $value;
        }
    }

    /**
     * build and output the debug string
     *
     * @param array $args the arguments to print
     * @param type $level the trace level, will override global
     */
    public function build($args, $level = NULL)
    {
        if($level === NULL) {
            $level = $this->ds->getTraceLevel();
        }

        $str = $this->string->get(debugBuilder::_EMPTY_PARAM, $level);

        $temp = array();
        $new = true;
        if(!empty($args)) {
            foreach($args as $a) {
                $temp[] = $this->string->get($a, 0, $new);
                $new = false;
            }
        }
        $str .= implode("\n", $temp);

        $space = $this->determineSpace($level);

        $str = $space . trim($str);

        $this->printer->goPrint($str);
    }

    private function determineSpace($level)
    {
        echo '<br>';
        $space = '';
        $tSpace = $this->ds->getTimerSpacing();
        $newFile = $this->string->getIsNewFile();
        if(($tSpace & $newFile) || (!$tSpace && $level > 0)) {
            $space = "\n";
        }

        return $space;
    }

}

/**
 * Mimimal version of debug print. Useful when developing this application.
 *
 * @param type $value
 * @param type $label
 */
function print_it($value, $label = '')
{
    $printString = '';

    $search = array("\n", " ");
    $replace = array('<br>', '&nbsp;');
    $string = str_replace($search, $replace, print_r($value, 1));

    $printString .= '<span style="font-family: monospace; font-weight:bold; text-align: left; padding: 0px 0px 5px 0px; margin 0px;">';
    $printString .= (empty($label) ? '' : $label . ': ');
    $printString .= $string;
    $printString .= '</span>';

    echo $printString;
}
