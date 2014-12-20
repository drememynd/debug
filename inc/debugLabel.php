<?php

require_once "debugFinder.php";

/**
 * Finds the parameter inputs into the function to make labels
 *
 * @author Katrina Wolfe
 * @copyright (c) 2014, Katrina Wolfe
 * @license http://drememynd.github.io/debug/license.html
 */
class debugLabel
{

    private $pstring = '';
    private $isMulti = false;
    private $pStrings = array();
    private $error;

    const _SINGLE = "'";
    const _DOUBLE = '"';

    /**
     * Return the label for the parameter
     *
     * @param string $file the fully qualified file path
     * @param int $line the line number from the trace
     * @param string $function the function name
     * @param mixed $value the parameter value
     * @param boolean $new starting a new function call
     * @return string the label
     */
    public function getValueLabel($file, $line, $function, $value, $new)
    {
        $this->isMulti = (strpos($function, 'multi') !== false);

        if($new || $this->isMulti === false) {
            $this->pStrings = array();
            if($this->setParamString($file, $line, $function) === false) {
                return $this->error;
            }

            $this->parseString();
        }

        $pstring = '';
        if(!empty($this->pStrings)) {
            $pstring = array_shift($this->pStrings);
        }


        return $this->makeLabelFromPstring($pstring, $value);
    }

    /**
     * Sets $this->pstring to the text between the parameter parenthesis
     *
     * @param string $file the fully qualified file path
     * @param int $line the line number from the trace
     * @param string $function the function name
     * @return boolean success or failure
     */
    private function setParamString($file, $line, $function)
    {
        $parser = new debugFinder();
        $this->pstring = $parser->getParamString($file, $line, $function);
        if($this->pstring === false) {
            $this->error = $parser->getErrors();
            return false;
        }

        return true;
    }

    /**
     * cleans up the label, passing back a blank label if the value
     * is a string which matches the label text
     *
     * if the parameter string and value match at the begining, it's
     * an odd case where the parameter is a variable holding a string
     * which is it's own name, including the dollar sign.
     *
     * @param string $pstring the potential label string
     * @param mixed $value the paremeter value
     * @return string the string to use as a label (or blank if not)
     */
    private function makeLabelFromPstring($pstring, $value)
    {
        $label = '';

        if($pstring === $value) {
            $label = $pstring;
        } else {
            $test1 = $this->miniQuoteTrim($pstring);

            if($test1 !== $value) {
                $label = $pstring;
            }
        }

        return substr($label, 0, 79);
    }

    /**
     * remove single or double quotes only if they match and are the first
     * and last characters of the string
     *
     * @param string $string the string to trim
     * @return string the trimmed string
     */
    private function miniQuoteTrim($string)
    {
        $first = substr($string, 0, 1);
        $last = substr($string, -1);
        if($first == $last && in_array($first, array(self::_DOUBLE, self::_SINGLE))) {
            $string = substr($string, 1, -1);
        }
        return $string;
    }

    /**
     * Set an array with all of the valid parameters in the parameter string.
     *
     * single parameter calls break after finding the first parameter
     */
    private function parseString()
    {
        $string = '';
        $chars = str_split($this->pstring);
        $inDelims = array();
        $prev = '';

        foreach($chars as $c) {

            $inDelims = $this->setInDelims($c, $prev, $inDelims);

            if($c == ',' && empty($inDelims)) {
                if($this->isMulti) {
                    $this->pStrings[] = trim($string);
                    $string = '';
                    continue;
                } else {
                    break;
                }
            }
            $string .= $c;
            $prev = $c;
        }

        $this->pStrings[] = trim($string);
    }

    /**
     * Figure out if our position is inbetween parameters of interest or not
     *
     * @param type $c
     * @param type $prev
     * @param type $inDelims
     * @return string|boolean
     */
    private function setInDelims($c, $prev, $inDelims)
    {
        $options = array(
            self::_SINGLE => self::_SINGLE,
            self::_DOUBLE => self::_DOUBLE,
            '(' => ')'
        );

        $c = $this->checkEscaped($c, $prev, $inDelims);

        foreach($options as $begin => $end) {


            if(!in_array($begin, $inDelims) && $c == $begin) {
                $inDelims[$begin] = $begin;
            } else if(in_array($begin, $inDelims) && $c == $end) {
                unset($inDelims[$begin]);
            }
        }

        return $inDelims;
    }

    /**
     * Looks to see if a character which could be a delimiter
     * should actually be used that way, and returns an empty string if not.
     *
     * @param string $c the character to check
     * @param string $prev the previous character
     * @param array $inDelims array of delimiters we're within
     * @return string $c the same or set to an empty string
     */
    private function checkEscaped($c, $prev, $inDelims)
    {
        $quotes = array(self::_SINGLE, self::_DOUBLE);
        $inSingle = in_array(self::_SINGLE, $inDelims);
        $inDouble = in_array(self::_DOUBLE, $inDelims);
        $inQuotes = $inSingle || $inDouble;

        if($prev == '\\' && in_array($c, $quotes)) {
            $c = '';
        }
        if($inSingle && $c == self::_DOUBLE) {
            $c = '';
        }
        if($inDouble && $c == self::_SINGLE) {
            $c = '';
        }
        if($inQuotes && in_array($c, array('(', ')'))) {
            $c = '';
        }

        return $c;
    }

}
