<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_Parser class.
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * BSD License
 *
 * Copyright (c) 2004-2005 Firman Wandayandi
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above
 *    copyright notice, this list of conditions and the following
 *    disclaimer in the documentation and/or other materials provided
 *    with the distribution.
 * 3. Neither the name of Firman Wandayandi nor the names of
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version CVS: $Id$
 */

// }}}
// {{{ Dependencies

/**
 * Require File for handling files.
 */
require_once 'File.php';

// }}}
// {{{ Class: Contact_AddressBook_Parser

/**
 * Base class for Contact_AddressBook parser classes.
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 */
class Contact_AddressBook_Parser
{
    // {{{ Properties

    /**
     * Parse result.
     *
     * @var array
     * @access protected
     */
    var $result = array();

    /**
     * File to parse.
     *
     * @var string
     * @access protected
     */
    var $file = '';

    // }}}
    // {{{ setFile()

    /**
     * Set the input file to parse.
     *
     * @param string $file Input filename.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access public
     */
    function setFile($file)
    {
        if (!file_exists($file)) {
            return PEAR::raiseError('No such file \'' . $file . '\'');
        }

        $this->file = $file;
        return true;
    }

    // }}}
    // {{{ parse()

    /**
     * Parse input file to gets address book data.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access public
     */
    function parse()
    {
        return PEAR::raiseError('Not implemented');
    }

    // }}}
    // {{{ getResult()

    /**
     * Get parse result data.
     *
     * @return array The result array.
     * @access public
     */
    function getResult()
    {
        return $this->result;
    }

    // }}}
    // {{{ numRows()

    /**
     * Returns the number of rows in a result.
     *
     * @return int The number of rows.
     * @access public
     */
    function numRows()
    {
        return count($this->result);
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
    // {{{ getFileContents()

    /**
     * Read the input file to gets file contents.
     *
     * @return string|PEAR_Error String file contents on succeed or
     *                           PEAR_Error on failure.
     * @access protected
     * @see File::read()
     * @static
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
}

// }}}

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
