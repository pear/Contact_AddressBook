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
 * File contains Contact_AddressBook_Builder_csv class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Builder
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Load the base class.
 */
require_once 'Contact/AddressBook/Builder.php';

/**
 * Abstract class for building CSV address book format.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Builder
 */
class Contact_AddressBook_Builder_csv extends Contact_AddressBook_Builder
{
    // {{{ Properties

    /**
     * Flag for set to write the header or not, FALSE by default.
     *
     * @var bool
     * @access protected
     */
    var $writeHeader = false;

    /**
     * Address book field header.
     *
     * @var array
     * @access protected
     */
    var $header = array();

    /**
     * Address book fields count.
     *
     * @var int
     * @access protected
     */
    var $fieldsCount = 0;

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
    function Contact_AddressBook_Builder_csv($data = null, $options = null)
    {
        parent::__construct($data, $options);
    }

    // }}}
    // {{{ build()

    /**
     * Build the address book.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function build()
    {
        if ($this->writeHeader) {
            if (empty($this->header)) {
                return PEAR::raiseError('Cannot write header, header never set');
            }

            if (count($this->header) != $this->fieldsCount) {
                return PEAR::raiseError('Cannot write header, header count ' .
                                        "doesn't match with fields count ".
                                        "{$this->fieldsCount}");
            }

            // Insert the header into data.
            array_unshift($this->data, $this->header);
        }

        // Fill values with empty, to deal with cvs rule
        // (same fields count for all fields).
        $record_orig = array_fill(0, $this->fieldsCount, '');
        $this->result = '';
        foreach ($this->data as $record) {
            $record = $record + $record_orig;
            ksort($record, SORT_NUMERIC);
            $this->result .= implode(',', $record).$this->options['line_break'];
        }
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
