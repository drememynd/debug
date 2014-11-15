<?php

require_once 'backtrace.php';
require_once 'builder.php';

/**
 * this class creates debug output and writes it to the
 * screen or to a file
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license MIT /
 * @lic
 */
class debug_printer
{

    private static $debugPrinter;
    private static $filename;
    private static $filepath;
    private static $tofile;
    private static $place;
    private static $lastFile;
    private static $level;

    /**
     * @var debug_timer
     */
    private static $timer;

    private function __construct()
    {
        self::$place = false;
        self::$filename = 'debug.txt';
        self::$filepath = __DIR__;
        self::$lastFile = '';
        self::$tofile = false;
        self::$level = 1;
        self::$timer = new timer();
    }

    /**
     * retrieves the singleton DebugPrinter object
     *
     * @return debug_printer
     */
    public static function getDebugPrinter()
    {
        if(!self::$debugPrinter) {
            self::$debugPrinter = new debug_printer();
        }

        return self::$debugPrinter;
    }

    /**
     * change whether printing to a file or to the web
     *
     * @param boolean $tofile print to a file or not
     */
    public function setPrintToFile($tofile)
    {
        self::$tofile = $tofile;
    }

    /**
     * set a new filepath
     *
     * will fail if path does not exist
     * failure leaves existing filepath
     *
     * @param string $filepath a directory
     * @return boolean success or failure
     */
    public function setPath($filepath)
    {
        $filepath = $this->cleanPath($filepath);
        if($filepath !== false) {
            self::$filepath = $filepath;
            $filepath = true;
        }

        return $filepath;
    }

    /**
     * set a new filename - uses basename only if path
     * will fail if parameter is empty
     * failure leaves existing filename
     *
     * @param string $filename
     * @return boolean success or failure
     */
    public function setFile($filename)
    {
        $filename = $this->cleanName($filename);

        if($filename !== false) {
            self::$filename = $filename;
            $filename = true;
        }

        return $filename;
    }

    /**
     * level represents the number of levels of backtrace output
     * which will be printed
     *
     * if level is not numeric, will set level to 1
     *
     * @param int $level the level
     * @return int the level set
     */
    public function setLevel($level)
    {
        $level = (is_numeric($level)) ? $level : 1;
        self::$level = $level;
        return $level;
    }

    public function out($value = 'z||z', $level = 1, $file = '')
    {
        self::$place = false;

        $level = $this->setLevel($level);
        $space = ($level == 0) ? '' : "\n";
        $str = $space . $this->getDebugString($value, $level) . "\n";

        $this->goPrint($str, $file);
    }

    public function multi()
    {

    }

    public function multi($args, $level = 1)
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

    private function goPrint($str, $file)
    {
        if(self::$tofile) {
            $this->setPath($file);
            $this->setFile($file);
            $this->filePrint($str);
        } else {
            $this->webPrint($str);
        }
    }

    private function filePrint($string)
    {
        $filepath = self::$filepath . DIRECTORY_SEPARATOR . self::$filename;
        file_put_contents($filepath, $string, FILE_APPEND);
    }

    private function webPrint($string)
    {
        $string = $this->webFormat($string);
        print_r($string);
    }

    private function webFormat($string)
    {
        $printString = '';

        $string = str_replace("\n", '<br>', $string);

        $string = str_replace(" ", '&nbsp;', $string);

        $printString .= '<span style="font-family: monospace; text-align: left; padding: 0px 0px 5px 0px; margin 0px;">';
        $printString .= $string;
        $printString .= '</span>' . "\n";

        return $printString;
    }

    /**
     * clean the path
     *
     * @param string $filepath
     * @return string|boolean path if success or false
     */
    private function cleanPath($filepath)
    {
        $testpath = realpath($filepath);
        $filepath = false;

        if(!empty($testpath)) {
            if(is_dir($testpath)) {
                $filepath = $testpath;
            } else if(is_file($testpath)) {
                $filepath = dirname($testpath);
            }
        }

        return $filepath;
    }

    /**
     * clean the filename
     *
     * @param string $filename
     * @return string|boolean name if success or false
     */
    private function cleanName($filename)
    {
        $testname = $filename;
        $filename = false;

        if(!empty($testname)) {
            $filename = basename($testname);
        }

        return $filename;
    }

}
