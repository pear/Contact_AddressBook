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
 * File contains Address_Book_Parser_eudora class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Parser
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD Lisence
 */

/**
 * Require Contact_AddressBook_Parser as base class.
 */
require_once 'Contact/AddressBook/Parser.php';

/**
 * Class for handling Eudora address book format parse.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Parser
 */
class Contact_AddressBook_Parser_eudora extends Contact_AddressBook_Parser
{
    // {{{ Constructor

    /**
     * PHP4 compatible constructor.
     *
     * @param string $file (optional) Input filename.
     * @see setInput()
     */
    function Contact_AddressBook_Parser_eudora($file = null)
    {
        parent::__construct($file);
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
        $contents = $this->getFileContents();

        if ($contents == '') {
            return PEAR::raiseError("File '{$this->file}' is empty");
        }

        $data = array();

        // Grab all alias keyword.
        if (preg_match_all('/alias\s+(\w+)\s*(.*)\n/', $contents,
                           $matches, PREG_SET_ORDER))
        {
            $i = 0;
            foreach ($matches as $raw) {
                $emails = explode(',', $raw[2]);
                $data[$i]['nickname'] = $raw[1];
                foreach ($emails as $j => $email) {
                    if ($j == 0) {
                        $data[$i]['email'] = $email;
                    } else {
                        $data[$i]['email' . ($j + 1)] = $email;
                    }
                }
                $i++;
            }
        }

        // For count alias, grab the note keyword for nickname.
        // Where detail informations are place here.
        foreach ($data as $i => $val) {
            $regex = '/note\s+' . $val['nickname'] . '\s+(<.*\>)(.*)\n/';
            if (preg_match_all($regex, $contents, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $detail) {
                    $data[$i]['notes'] = $detail[2]; 
                    if (preg_match_all('|<([^>]+)>|U', $detail[1], $matches_a))
                    {
                        foreach ($matches_a[1] as $data_a) {
                            list($field, $value) = explode(':', $data_a, 2);
                            if ($field == 'otheremail') {
                                $emails = explode(chr(3), $value);
                                foreach ($emails as $j => $email) {
                                    if ($j == 0) {
                                        $data[$i]['otheremail'] = $email;
                                    } else {
                                        $data[$i]['otheremail' . ($j + 1)] = $email;
                                    }
                                }
                                continue;
                            }
                            $data[$i][$field] = $value;
                        }
                    }
                }
            }
        }

        $this->result = $data;
        return true;
    }
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
