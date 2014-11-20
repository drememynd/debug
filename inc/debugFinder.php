<?php

/**
 * Finds and returns the entire string between the parameter parens
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugFinder
{

    private $error;
    private $contents;
    private $last;
    private $line;
    private $pstring;

    /**
     * Call to retrieve the parameter string
     *
     * @param string $file the fully qualified file path
     * @param int $line the line number from the trace
     * @param string $function the function name
     * @return string the parameter string
     */
    public function getParamString($file, $line, $function)
    {
        if(!$this->setUpData($file, $line, $function)) {
            return false;
        }

        if(!$this->makeParamString($function)) {
            return false;
        }

        return $this->pstring;
    }

    /**
     * retrieve the error string
     *
     * @return string contains error
     */
    public function getErrors()
    {
        return $this->error;
    }

    /**
     * set up the data environment
     *
     * @param string $file the fully qualified file path
     * @param int $line the line number from the trace
     * @param string $function the function name
     * @return boolean success or failure
     */
    private function setUpData($file, $line, $function)
    {
        $success = true;

        if(!$this->setContents($file)) {
            $success = false;
        }

        if(!$this->setLast()) {
            $success = false;
        }

        if(!$this->setLine($line, $function)) {
            $success = false;
        }

        return $success;
    }

    /**
     * read the file into an array
     *
     * @param string $file the fully qualified file path
     * @return boolean success or failure
     */
    private function setContents($file)
    {
        if(!is_file($file)) {
            $this->error = 'File Error: is not a file';
            return false;
        }

        $contents = file($file, FILE_IGNORE_NEW_LINES);
        if($contents === false) {
            $this->error = 'File Error: read error';
            return false;
        }

        $this->contents = array_combine(range(1, count($contents)), array_values($contents));

        return true;
    }

    /**
     * set the index of the last line in the file
     *
     * @return boolean success or failure
     */
    private function setLast()
    {
        $last = count($this->contents);
        if($last < 1) {
            $this->error = 'File Error: file empty';
        }

        $this->last = $last;

        return true;
    }

    /**
     * The line number from the trace is the last line of the function
     * call.  This finds and sets the first line of the function call.
     *
     * @param int $line the line number from the trace
     * @param string $function the function name
     * @return boolean success or failure
     */
    private function setLine($line, $function)
    {
        if($line > $this->last || $line < 1) {
            $this->error = 'Illegal Line No: ' . $line;
            return false;
        }

        do {
            $position = strpos($this->contents[$line], $function);
            $this->line = $line;
            $line--;
            if($line < 1) {
                $this->error = 'Function Not Found: ' . $function;
                return false;
            }
        } while($position === false);

        return true;
    }

    /**
     * loop through the lines, until the end of the function call is found
     *
     * @param string $function the function name
     * @return boolean success or failure
     */
    private function makeParamString($function)
    {
        $line = $this->line;
        $this->pstring = '';

        $start = strpos($this->contents[$line], $function) + strlen($function);

        $count = 0;

        do {
            if($line > $this->last) {
                $this->error = __LINE__ . '::' . __METHOD__ . ': line[' . __LINE__ . ']: $line: ' . $line;
                return false;
            }

            $search = substr($this->contents[$line], $start);
            $start = 0;

            $count = $this->parseString($search, $count);

            $line++;
        } while($count != 0);

        $this->pstring = trim($this->pstring);
        return true;
    }

    /**
     * add characters to the result string until the end of the function call
     *
     * @param string $search the string to parse
     * @param int $count in/decremented for parenthsis openings and closings
     * @return int $count
     */
    private function parseString($search, $count)
    {
        $string = '';
        $chars = str_split($search);

        $start = ($count == 0) ? false : true;

        foreach($chars as $c) {

            if($start) {

                if($c == ')') {
                    $count--;
                    if($count == 0) {
                        break;
                    }
                }

                $string .= $c;
            }

            if($c == '(') {
                $start = true;
                $count++;
            }
        }

        $this->pstring .= trim($string) . ' ';

        return $count;
    }

}
