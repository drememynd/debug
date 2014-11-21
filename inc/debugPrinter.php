<?php

require_once "debugSetup.php";

/**
 * Prints the output string to standard output or to a specified file
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugPrinter
{

    /**
     * @var debugSetup
     */
    private $ds;

    /**
     * sets the debugSetup object
     */
    public function __construct(debugSetup $ds)
    {
        $this->ds = $ds;
    }

    /**
     * outputs the string to file or standard
     *
     * @param string $str the string to print
     */
    public function goPrint($str)
    {
        if($this->ds->getUseWebTags()) {
            $str = $this->webFormat($str);
        }

        if($this->ds->getPrintToFile()) {
            $this->filePrint($str);
        } else {
            $this->standardPrint($str);
        }
    }

    /**
     * output to a file
     *
     * @param string $str the string to print
     */
    private function filePrint($string)
    {
        $filepath = $this->ds->getFullFilePath();

        file_put_contents($filepath, $string, FILE_APPEND);
    }

    /**
     * output to standard
     *
     * @param string $str the string to print
     */
    private function standardPrint($string)
    {
        echo $string;
    }

    /**
     * format the string with tags for the web, carriage returns added
     * for code readability
     *
     * @param type $string
     * @return string
     */
    private function webFormat($string)
    {
        $printString = '';
        $cr = "\n";

        $search = array($cr, " ");
        $replace = array("<br>{$cr}", '&nbsp;');
        $string = str_replace($search, $replace, $string);

        $printString .= '<span style="font-family: monospace; text-align: left; padding: 0px 0px 5px 0px; margin 0px;">';
        $printString .= $cr . $string;
        $printString .= '</span>' . $cr . $cr;

        return $printString;
    }

}
