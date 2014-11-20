<?php

defined('_DEFAULT_FILE_PATH') || define('_DEFAULT_FILE_PATH', realpath('..'));

/**
 * Holds setup values for desired behavior
 *
 * boolean $printToFile default: false : toggles between standard output and file output<br>
 * string $fileName default: debug.txt : the name of the output file<br>
 * string $filePath default: top directory of this program<br>
 * boolean $useWebTags default: true : whether to format output with tags for the web<br>
 * int $traceLevel default: 1 : the number of backtrace lines to include in output<br>
 * boolean $useLabels default: true : whether to use the program labeling system<br>
 * boolean $addTime default: true : whether to use the program timing feature
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugSetup
{

    private $printToFile = false;
    private $fileName = 'debug.txt';
    private $filePath = _DEFAULT_FILE_PATH;
    private $useWebTags = true;
    private $traceLevel = 1;
    private $useLabels = true;
    private $addTime = true;

    /**
     * call the appropriate setter for the named parameter
     *
     * @param string $name the name of the parameter
     * @param mixed $value the value to set
     */
    public function __set($name, $value)
    {
        $methodName = 'set' . $name;
        if(method_exists($this, $methodName)) {
            $this->$methodName($value);
        }
    }

    /**
     * set whether or not to use the program labeling function
     *
     * @param boolean $addTime use labels or not
     */
    public function setAddTime($addTime)
    {
        $this->addTime = $addTime;
    }

    /**
     * get whether or not to use the program timing feature
     *
     * @return boolean whether to use timing
     */
    public function getAddTime()
    {
        return $this->addTime;
    }

    /**
     * set whether or not to use the program timing feature
     *
     * @param boolean $useLabels use timing
     */
    public function setUseLabels($useLabels)
    {
        $this->useLabels = $useLabels;
    }

    /**
     * get whether or not to use the program labeling function
     *
     * @return boolean whether to use labels or not
     */
    public function getUseLabels()
    {
        return $this->useLabels;
    }

    /**
     * Set Print to File toggle AND formatting with tags toggle
     *
     * @param boolean $toFile whether to print to a file or not
     * @param boolean $useWebTags DEFAULT: !$toFile
     * @return boolean success
     */
    public function setPrintToFile($toFile, $useWebTags = NULL)
    {
        $this->printToFile = $toFile;
        $this->useWebTags = ($useWebTags === NULL) ? !$toFile : $useWebTags;

        return true;
    }

    /**
     * Get Print to File value
     *
     * @return boolean whether to print to a file or not
     */
    public function getPrintToFile()
    {
        return $this->printToFile;
    }

    /**
     * Get use web tags value
     *
     * @return boolean whether to format with web tags or not
     */
    public function getUseWebTags()
    {
        return $this->useWebTags;
    }

    /**
     * set a new filename - uses basename only
     * will fail if parameter is empty,
     * failure leaves existing filename
     *
     * @param string $filename
     * @return boolean success or failure
     */
    public function setFileName($name)
    {
        $success = false;
        $name = $this->cleanName($name);

        if($name !== false) {
            $this->fileName = $name;
            $success = true;
        }

        return $success;
    }

    /**
     * Get the file name
     *
     * @return string the file name
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * setting a new filepath
     * will fail if path does not exist,
     * failure leaves existing filepath
     *
     * @param string $filepath a directory
     * @return boolean success or failure
     */
    public function setFilePath($path)
    {
        $success = false;
        $path = $this->cleanPath($path);

        if($path !== false) {
            $this->filePath = $path;
            $success = true;
        }

        return $success;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getFullFilePath()
    {
        return $this->filePath . DIRECTORY_SEPARATOR . $this->fileName;
    }

    /**
     * set the number of backtrace lines to print<br>
     * NOTE: Maximum == 10
     *
     * @param int $level the backtrace level
     */
    public function setTraceLevel($level = 1)
    {
        if(!is_numeric($level) || $level < 0 || $level > 10) {
            return false;
        }

        $intval = floor($level + 0);
        if($intval != $level) {
            return false;
        }

        $this->traceLevel = $intval;

        return true;
    }

    /**
     * get the number of backtrace lines to print
     *
     * @return int the backtrace level
     */
    public function getTraceLevel()
    {
        return $this->traceLevel;
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
