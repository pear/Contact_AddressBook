<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_CSV class.
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
 * @since File available since Release 0.4.0alpha1
 */

// }}}
// {{{ Global Variables

/**
 * CSV Formats informations.
 *
 * @global array $GLOBALS['_Contact_AddressBook_CSV_configs']
 * @name $_Contact_AddressBook_CSV_configs
 */
$GLOBALS['_Contact_AddressBook_CSV_configs'] = array(
    'wab'               => 'WAB',
    'outlookexpress'    => 'WAB',
    'outlook'           => 'Outlook',
    'mozilla'           => 'Mozilla',
    'nestcape'          => 'Mozilla',
    'thunderbird'       => 'Mozilla',
    'yahoo'             => 'Yahoo',
    'palm'              => 'Palm',
    'kmail'             => 'KMail',
    'gmail'             => 'Gmail'
);

/**
 * CSV Formats informations.
 *
 * @global array $GLOBALS['_Contact_AddressBook_CSV_headers']
 * @name $_Contact_AddressBook_CSV_headers
 */
$GLOBALS['_Contact_AddressBook_CSV_headers'] = array(
    'wab'               => 'WAB',
    'outlookexpress'    => 'WAB',
    'outlook'           => 'Outlook',
    'yahoo'             => 'Yahoo',
    'kmail'             => 'KMail',
    'gmail'             => 'Gmail'
);

// }}}
// {{{ Class: Contact_AddressBook_CSV

/**
 * Global class for CSV class.
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 * @since Class available since Release 0.4.0alpha1
 */
class Contact_AddressBook_CSV
{
    // {{{ getConfig()

    /**
     * Load CSV file format configuration for the specified CSV address book,
     * then return it.
     *
     * @param string $format Address book format.
     *
     * @return array|PEAR_Error An array of config on success or
     *                          PEAR_Error on failure.
     * @access public
     * @static
     */
    function getConfig($format)
    {
        if (!isset($GLOBALS['_Contact_AddressBook_CSV_configs'][$format])) {
            return PEAR::raiseError('No such config for \'' . $format .'\'');
        }

        $dir = Contact_AddressBook::getDataDir() . '/CSV/Config/';

        $file = $dir .
                $GLOBALS['_Contact_AddressBook_CSV_configs'][$format] . '.php';
        include_once $file;

        if (!isset($GLOBALS['_Contact_AddressBook_CSV_config']))
        {
            return PEAR::raiseError('Undefined config for \'' . $format .'\'');
        }

        $default = array(
            'quote'             => '"',
            'linebreak'         => 'auto',
            'selectablefields'  => false
        );

        return array_merge($default,
                           $GLOBALS['_Contact_AddressBook_CSV_config']);
    }

    // }}}
    // {{{ getHeader()

    /**
     * Load CSV file format configuration for the specified CSV address book,
     * then return it.
     *
     * @param string $format Address book format.
     * @param string $language (optional) Address book languange
     *                                    (use language code), default is "en".
     *
     * @return array|PEAR_Error An array of header on success or
     *                          PEAR_Error on failure.
     * @access public
     * @static
     */
    function getHeader($format, $language = null)
    {
        if (!isset($GLOBALS['_Contact_AddressBook_CSV_headers'][$format])) {
            return PEAR::raiseError('No such header for \'' . $format .'\'');
        }

        if ($language === null) {
            $language = 'en';
        } else {
            $language = strtolower($language);
        }

        $dir = Contact_AddressBook::getDataDir() . '/CSV/Header/';
        $file = $dir .
                $GLOBALS['_Contact_AddressBook_CSV_headers'][$format] . '/' .
                $language . '.php';
        include_once $file;

        if (!isset($GLOBALS['_Contact_AddressBook_CSV_header'])) {
            return PEAR::raiseError('Undefined header for \'' . $format .'\'');
        }

        return $GLOBALS['_Contact_AddressBook_CSV_header'];
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
