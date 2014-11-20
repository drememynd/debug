Author Katrina Wolfe
Copyright (c) 2014, Katrina Wolfe
License http://drememynd.github.io/debug/license.html

debug
=====

PHP debug output

Files:

README.md : the readme file
LICENSE : please read it

print_d.php : a file of static functions which can be called independently
    These functions will always access the same builder object.

debug.php : a class which calls the same functions as print_d
    By default, instantiating this in different locations in your code will
    retrieve the same builder object. By passing in a $type argument, you can
    create more than one instance, and retrieve each instance wherever you want
    by passing in the same $type argument.  Each instance can be set up
    differently, so for example, you could print output to different files.

inc/debugBacktrace.php : gets the php backtrace and parses it for information
inc/debugBuilder.php : coordinates building the output string
inc/debugFinder.php : finds the parameter string of the calling function
inc/debugLabel.php : gets the output label
inc/debugPrinter.php : prints the output to the screen, or to a file
inc/debugSetup.php : keeps the setup information
inc/debugString.php : makes the debug string
inc/debugTimer.php : keeps and calculates timing information

================

Setup Parameters:

addTime - whether to use the program timing feature
    The timing feature adds a timing string to the end of the backtrace string.
    This allows the program to be used to do program timing to the microsecond.
    DEFAULT: true

fileName - the name of the output file
    If printToFile is true, this is the file name which will be used.
    DEFAULT: default.txt

filePath - the directory of the output file
    If printToFile is true, this is the directory the output file will be in.
    DEFAULT: the top level of this program

printToFile - output to a file if true, standard output if false
    DEFAULT: false

traceLevel - the number of backtrace lines to include in output
    This is the global default of the program.  The single value parameter
    functions also take a parameter which overrides the global default
    DEFAULT: 1

useLabels - whether to use the program labeling system
    If this is false, the system will not create and print labels.  Labels are
    created by parsing the calling PHP files for information about the parameters
    passed in.  You may want to turn this off if you have huge PHP source
    code files in your project
    DEFAULT: true

useWebTags - format with tags if true, do not if false
    You may want to change this if, for example, you are outputting to standard
    output from a PHP script that is not being used on the web, or if you are
    outputting to a file, but want to view that file in a web browser.
    DEFAULTS:
        if printToFile = true then useWebTags = false
        if printToFile = false then useWebTags = true
