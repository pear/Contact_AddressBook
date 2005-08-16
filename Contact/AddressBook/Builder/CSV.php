<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_Builder_CSV class.
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
 * Load Contact_AddressBook_Builder as the base class.
 */
require_once 'Contact/AddressBook/Builder.php';

// }}}
// {{{ Class: Contact_AddressBook_Builder_CSV

/**
 * Class for building CSV address book format.
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 */
class Contact_AddressBook_Builder_CSV extends Contact_AddressBook_Builder
{
    // {{{ Properties

    /**
     * MIME Type when send the output to the browser.
     *
     * @var string
     * @access protected
     */
    var $mime = 'text/x-csv';

    /**
     * File extension.
     *
     * @var string
     * @access protected
     */
    var $extension = 'csv';

    /**
     * CSV address book format.
     *
     * @var string
     * @access private
     */
    var $_format = '';

    /**
     * Header language, this header use only when the address book format
     * show the header.
     *
     * @var string
     * @access private
     */
    var $_language = 'en';

    // }}}
    // {{{ setFormat()

    /**
     * Set the address book format.
     *
     * @param string $format Address book format.
     *
     * @access public
     */
    function setFormat($format)
    {
        $this->_format = $format;
    }

    // }}}
    // {{{ setLanguage()

    /**
     * Set the language, only use address book show the header.
     *
     * @param string $language Language.
     *
     * @access public
     */
    function setLanguage($language)
    {
        $this->_language = $language;
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
        $config = Contact_AddressBook_CSV::getConfig($this->_format);
        if (PEAR::isError($config)) {
            return $config;
        }

        if (isset($config['header']) && $config['header'] == true) {
            $header = Contact_AddressBook_CSV::getHeader($this->_format,
                                                         $this->_language);
            if (PEAR::isError($header)) {
                return $header;
            }

            // Insert the header into data.
            array_unshift($this->data, $header);
        }

        $escapes = array(',', "\r", "\n", "\r\n", $config['quote']);

        // Fill values with empty, to deal with csv rule
        // (same fields count for all fields).
        $default = array_fill(0, $config['fields'], '');
        $this->result = '';
        foreach ($this->data as $record) {
            $record = $record + $default;
            ksort($record, SORT_NUMERIC);
            if (isset($config['quoted_field']) &&
                $config['quoted_field'] == true) {
                $this->result .= $config['quote'] .
                                 implode($config['quote'] . ',' . $config['quote'],
                                         $record) . $config['quote'];
            } else {
                foreach ($record as $key => $value) {
                    // Replace any line break if multilines support doesn't capable.
                    if (!isset($config['multilines']) || $config['multilines'] === false) {
                        $value = Contact_AddressBook_Builder::stripLineBreak($value);
                    }

                    // Quote any escape characters values.
                    if (preg_match('/[' . implode($escapes) . ']+/', $value)) {
                        $value = '"' . $value . '"';
                    }

                    $record[$key] = $value;
                }

                $this->result .= implode(',', $record);
            }
            $this->result .= ($config['line_break'] == 'auto') ? PHP_EOL : $config['line_break'];
        }
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
