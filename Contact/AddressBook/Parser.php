<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | PEAR, the PHP Extension and Application Repository                   |
// +----------------------------------------------------------------------+
// | Copyright (C) 2004  Firman Wandayandi                                |
// | All rights reserved.                                                 |
// +----------------------------------------------------------------------+
// | This LICENSE is in the BSD license style.                            |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// |                                                                      |
// | Redistribution and use in source and binary forms, with or without   |
// | modification, are permitted provided that the following conditions   |
// | are met:                                                             |
// |                                                                      |
// |   Redistributions of source code must retain the above copyright     |
// |   notice, this list of conditions and the following disclaimer.      |
// |                                                                      |
// |   Redistributions in binary form must reproduce the above            |
// |   copyright notice, this list of conditions and the following        |
// |   disclaimer in the documentation and/or other materials provided    |
// |   with the distribution.                                             |
// |                                                                      |
// |   Neither the name of Firman Wandayandi nor the names of             |
// |   contributors may be used to endorse or promote products derived    |
// |   from this software without specific prior written permission.      |
// |                                                                      |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS    |
// | FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE       |
// | COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,  |
// | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, |
// | BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;     |
// | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER     |
// | CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT   |
// | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN    |
// | ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE      |
// | POSSIBILITY OF SUCH DAMAGE.                                          |
// +----------------------------------------------------------------------+
// | Authors: Firman Wandayandi <firman@php.net>                          |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * File contains Contact_AddressBook_Parser class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD Lisence
 */

/**
 * Require PEAR for errors handling.
 */
require_once 'PEAR.php';

/**
 * Require File for handling files.
 */
require_once 'File.php';

/**
 * An abstract class for address book parser.
 *
 * This class contains common methods for parse the address book.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @abstract
 */
class Contact_AddressBook_Parser
{
    // {{{ Properties

    /**
     * Input filename.
     *
     * @var string
     * @access protected
     */
    var $file = '';
    
    /**
     * Parse result.
     *
     * @var array
     * @access protected
     */
    var $result = array();

    // }}}
    // {{{ Constructor

    /**
     * PHP4 compatible constructor.
     *
     * @param string $file (optional) Input filename.
     * @see setInput()
     */
    function Contact_AddressBook_Parser($file = null)
    {
        $this->__construct($file);
    }

    /**
     * Constructor.
     *
     * @param string $file (optional) Input filename.
     * @see setInput()
     */
    function __construct($file = null)
    {
        if ($file !== null) {
            $this->setInput($file);
        }
    }

    // }}}
    // {{{ __toString()

    /**
     * PHP5 __toString magic method.
     *
     * @return string
     * @access public
     */
    function __toString()
    {
        return var_export($this, true);
    }

    // }}}
    // {{{ setInput()

    /**
     * Set the input file to parse.
     *
     * @param string $file Input filename.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access public
     */
    function setInput($file)
    {
        if (!file_exists($file)) {
            return PEAR::raiseError("No such file '$file'");
        }

        $this->file = $file;
        return true;
    }

    // }}}
    // {{{ getFileContents()

    /**
     * Read the input file to gets file contents.
     *
     * @return string|PEAR_Error String file contents on succeed or
     *                           PEAR_Error on failure.
     * @access protected
     * @see File::read()
     */
    function getFileContents()
    {
        $contents = '';
        while($res = File::read($this->file)) {
            if (PEAR::isError($res)) {
                return $res;
            }

            $contents .= $res;
        }

        return $contents;
    }

    // }}}
    // {{{ parse()

    /**
     * Parse input file to gets address book data.
     *
     * @param array $conf (optional) Parse configuration.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access public
     */
    function parse($conf = null)
    {
        // Abstract method, implemented in extended classes.
    }

    // }}}
    // {{{ reset()

    /**
     * Reset the parser.
     * This method set the result into empty array.
     *
     * @access public
     */
    function reset()
    {
        $this->result = array();
    }

    // }}}
    // {{{ getResult()

    /**
     * Get the parse result.
     *
     * @return array An array of address book parse result.
     * @access public
     */
    function getResult()
    {
        return $this->result;
    }

    // }}}
}

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
