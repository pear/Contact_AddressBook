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
 * File contains AddressBook_Builder_eudora. Class for building Eudora
 * address book format.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Builder
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 */

/**
 * Load AddressBook_Builder as base class.
 */
require_once 'Contact/AddressBook/Builder.php';

/**
 * Class for building Eudora address book format.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddresBook
 * @subpackage Builder
 */
class Contact_AddressBook_Builder_eudora extends Contact_AddressBook_Builder
{
    // {{{ Constructor

    /**
     * PHP 4 compatible constructor.
     *
     * @param array $data (optional) Data.
     * @param array $options (optional) Options.
     *
     * @access public
     */
    function Contact_AddressBook_Builder_eudora($data = null, $options = null)
    {
        parent::__construct($data, $options);
    }

    // {{{ build()

    /**
     * Build the structure format.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function build()
    {
        $this->result = '';
        foreach ($this->data as $record) {
            $alias = isset($record['nickname']) ? $record['nickname'] : '';
            $this->result = "alias  $alias {$record['email']}" .
                             $this->options['line_break'];

            $this->result .= "note $alias ";
            if (isset($record['nickname'])) {
                unset($record['nickname']);
            }

            foreach ($record as $field => $value) {
                $this->result .= "<$field: $value>";
            }
            $this->result .= $this->options['line_break'];
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
