<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_Builder class.
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
 * Load File for handling files.
 */
require_once 'File.php';

/**
 * Load Net_UserAgent_Detect for send the right header.
 */
require_once "Net/UserAgent/Detect.php";

// }}}
// {{{ Class: Contact_AddressBook_Builder

/**
 * Abstract class for building address book.
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 * @abstract
 */
class Contact_AddressBook_Builder
{
    // {{{ Properties

    /**
     * Cache for data.
     *
     * @var array
     * @access protected
     */
    var $data = array();

    /**
     * Cache for result.
     *
     * @var string
     * @access protected
     */
    var $result = '';

    /**
     * MIME Type when send the output to the browser.
     *
     * @var string
     * @access protected
     */
    var $mime = 'text/plain';

    /**
     * File extension.
     *
     * @var string
     * @access protected
     */
    var $extension = 'txt';

    // }}}
    // {{{ Constructor

    function Contact_AddressBook_Builder($data = null)
    {
        if (is_array($data)) {
            $this->setData($data);
        }
    }

    // }}}
    // {{{ setData()

    /**
     * Set data to build.
     *
     * @param array $data Data.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function setData($data)
    {
        if (!is_array($data)) {
            return PEAR::raiseError('Type mismatch for data, only array allowed');
        }

        $this->data = $data;
        return true;
    }

    // }}}
    // {{{ build()

    /**
     * Build the structure format.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function build()
    {
        return PEAR::raiseError('Not implemented');
    }

    // }}}
    // {{{ show()

    /**
     * Print out the result.
     *
     * @access public
     */
    function show()
    {
        print htmlspecialchars($this->result);
    }

    // }}}
    // {{{ save()

    /**
     * Save the result into file.
     *
     * @param string $filename Filename.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function save($filename)
    {
        $filename .= '.' . $this->extension;
        return File::write($filename, $this->result, FILE_MODE_WRITE);
    }

    // }}}
    // {{{ donwload()

    /**
     * Download the result via browser.
     *
     * @param string $filename Filename.
     *
     * @access public
     */
    function download($filename)
    {
        if (headers_sent()) {
            return PEAR::raiseError('Cannot process headers, headers already sent');
        }

        $filename .= '.' . $this->extension;
        if (Net_UserAgent_Detect::isIE())
        {
            // IE need specific headers
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
        }

        header('Content-Type: ' . $this->mime);
        print $this->show();
        exit;
    }

    // }}}
    // {{{ stripLineBreak()

    /**
     * Strip any line break characters then replace it with space.
     *
     * @param string $str Value.
     *
     * @return string Result string.
     * @access public
     * @static
     */
    function stripLineBreak($str)
    {
        return preg_replace('/\r\n|\r|\n/', ' ', $str);
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
