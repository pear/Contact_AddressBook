<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_Converter class.
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
 * @category FileFormats
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

/**
 * Require Net_UserAgent_Detect for send the right header.
 */
require_once "Net/UserAgent/Detect.php";

// }}}
// {{{ Class: Contact_AddressBook_Converter

/**
 * Base class for build external formatted structure that may save to a file.
 *
 * @category FileFormats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 */
class Contact_AddressBook_Builder
{
    // {{{ Properties

    /**
     * Options.
     *
     * @var array
     * @access protected
     */
    var $options = array(
        'line_break' => "\n"
    );

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

    // }}}
    // {{{ Constructor

    /**
     * PHP 4 compatible constructor.
     *
     * @param array $data (optional) Data.
     * @param array $options (optional) Options.
     *
     * @access public
     */
    function Contact_AddressBook_Builder($data = null, $options = null)
    {
        $this->__construct($data, $options);
    }

    /**
     * PHP 5 compatible constructor.
     *
     * @param array $data (optional) Data.
     * @param array $options (optional) Options.
     *
     * @access public
     */
    function __construct($data = null, $options = null)
    {
        if ($data !== null && is_array($data)) {
            $this->setData($data);
        }

        if ($options !== null) {
            $this->set($options);
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
    // {{{ set()

    /**
     * Set the option(s).
     *
     * Set a single option or multiple options.
     *
     * @param mixed $option A string with option name as value for
     *                      single option or An associative array contains
     *                      options array('<option>' => <value>) for multiple
     *                      options.
     * @param mixed $value (optional) Option value. Require when $option is
     *                                string (single option mode).
     *
     * @return bool|PEAR_Error TRUE on success, PEAR_Error on failure.
     * @access public
     */
    function set($option, $value = null)
    {
        if (is_array($option)) {
            foreach ($option as $key => $val) {
                $err = $this->set($key, $val);
                if (PEAR::isError($err)) {
                    return $err;
                }
            }
        } elseif (is_string($option)) {
            if (isset($this->options[$option])) {
                if (gettype($this->options[$option]) == gettype($value)) {
                    $this->options[$option] = $value;
                } else {
                    return PEAR::raiseError('Type mismatch for option '.
                                            "'$option' value");
                }
            }
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
            return PEAR::raiseError('Type mismatch for map only array allowed');
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
        return PEAR::raiseError('unsupported');
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
        print $this->result;
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
        return File::write($filename, $this->result, FILE_MODE_WRITE);
    }

    // }}}
    // {{{ donwload()

    /**
     * Download the result via browser.
     *
     * @param string $filename (optional) Filename.
     *
     * @access public
     */
    function download($filename = null)
    {
        if ($filename === null) {
            $filename = 'addressbook';

            // Add specific extension depending classname.
            if (substr(get_class($this), 28, 3) == 'csv') {
                $filename .= '.csv';
            } elseif (substr(get_class($this), 28, 4) == 'ldif') {
                $filename .= '.ldif';
            } else {
                $filename .= '.txt';
            }
        }

        $extension = '';
        if (($pos = strrpos($filename, '.')) !== false) {
            $extension = substr($filename, $pos + 1);
        }

        switch (strtolower($extension)) {
            case 'csv':
                $mimetype = 'text/x-csv';
                break;
            default:
                $mimetype = 'text/plain';
                break;
        }

        if (headers_sent()) {
            return PEAR::raiseError('Cannot process headers, headers already sent');
        }

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

        header('Content-Type: ' . $mimetype);
        print $this->result;
        exit;
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
