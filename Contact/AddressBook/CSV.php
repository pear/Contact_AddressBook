<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * $ShortFileDescription$
 *
 * $LongFileDescription$
 *
 * PHP version $PHPVersion$
 *
 * LICENSE:
 *
 * BSD License
 *
 * Copyright (c) $CopyrightYear$ Firman Wandayandi
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
 * @category $CategoryName$
 * @package $PackageName$
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) $CopyrightYear$ Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version CVS: $Id$
 * @since File available since Release 0.2.0dev1
 */

// }}}
// {{{ Dependencies

/**
 * Load PEAR for error handling.
 */
require_once 'PEAR.php';

// }}}
// {{{ Global Variables

/**
 * CSV Formats informations.
 *
 * @global array $GLOBALS['Contact_AddressBook_CSV_configs']
 * @name $Contact_AddressBook_CSV_configs
 */
$GLOBALS['Contact_AddressBook_CSV_configs'] = array(
    'wab'               => 'Contact/AddressBook/CSV/Config/WAB.php',
    'outlookexpress'    => 'Contact/AddressBook/CSV/Config/WAB.php',
    'outlook'           => 'Contact/AddressBook/CSV/Config/Outlook.php',
    'mozilla'           => 'Contact/AddressBook/CSV/Config/Mozilla.php',
    'nestcape'          => 'Contact/AddressBook/CSV/Config/Mozilla.php',
    'thunderbird'       => 'Contact/AddressBook/CSV/Config/Mozilla.php',
    'yahoo'             => 'Contact/AddressBook/CSV/Config/Yahoo.php',
    'palm'              => 'Contact/AddressBook/CSV/Config/Palm.php'
);

/**
 * CSV Formats informations.
 *
 * @global array $GLOBALS['Contact_AddressBook_CSV_headers']
 * @name $Contact_AddressBook_CSV_headers
 */
$GLOBALS['Contact_AddressBook_CSV_headers'] = array(
    'wab'               => 'WAB',
    'outlookexpress'    => 'WAB',
    'outlook'           => 'Outlook',
    'yahoo'             => 'Yahoo'
);

/**
 * Field definitions.
 *
 * @global array $GLOBALS['Contact_AddressBook_CSV_definitions']
 * @name $Contact_AddressBook_CSV_definitions
 */
$GLOBALS['Contact_AddressBook_CSV_definitions'] = array(
    'wab'               => 'WAB.def',
    'outlookexpress'    => 'WAB.def',
    'outlook'           => 'Outlook.def',
    'mozilla'           => 'Mozilla.def',
    'nestcape'          => 'Mozilla.def',
    'thunderbird'       => 'Mozilla.def',
    'yahoo'             => 'Yahoo.def',
    'palm'              => 'Palm.def'
);

// }}}
// {{{ Class: $ClassName$

/**
 * $ShortClassDescription$
 *
 * $LongClassDescription$
 *
 * @category $CategoryName$
 * @package $PackageName$
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) $CopyrightYear$ Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 * @since Class available since Release 0.2.0dev1
 */
class Contact_AddressBook_CSV
{
    // {{{ loadConfig()

    /**
     * Load CSV file format configuration for the specified CSV address book.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access protected
     */
    function loadConfig($format)
    {
        if (!isset($GLOBALS['Contact_AddressBook_CSV_configs'][$format]))
        {
            return PEAR::raiseError('Uknown format \'' . $format .'\'');
        }

        include_once $GLOBALS['Contact_AddressBook_CSV_configs'][$format];
        if (!isset($GLOBALS['Contact_AddressBook_CSV_config']))
        {
            return PEAR::raiseError('Undefined configuration');
        }

        return $GLOBALS['Contact_AddressBook_CSV_config'];
    }

    // }}}
    // {{{ getConfig()

    function getConfig($format)
    {
        $default = array(
            'quote'             => '"',
            'linebreak'         => 'auto',
            'selectablefields'  => false
        );

        $config = Contact_AddressBook_CSV::loadConfig($format);
        if (PEAR::isError($config))
        {
            return $config;
        }

        return array_merge($default, $config);
    }

    // }}}
    // {{{ getDefaultDefinitionFile()

    function getDefaultDefinitionFile($format)
    {
        if (!isset($GLOBALS['Contact_AddressBook_CSV_definitions'][$format]))
        {
            return PEAR::raiseError('Uknown format \'' . $format .'\'');
        }

        include_once 'PEAR/Config.php';
        $config = new PEAR_Config;
        $dir = $config->get('data_dir') .
               '/Contact_AddressBook/definitions/CSV/';
        $file = $dir . $GLOBALS['Contact_AddressBook_CSV_definitions'][$format];
        return $file;
    }

    // }}}
    // {{{ loadDefinition()

    function loadDefinition($file)
    {
        ini_set('track_error', true);
        $res = parse_ini_file($file);
        if (empty($res))
        {
            return PEAR::raiseError($php_errormsg);
        }

        return $res;
    }

    // }}}
    // {{{ loadHeader()

    /**
     * Load CSV file format configuration for the specified CSV address book.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access protected
     */
    function loadHeader($format, $languange = null)
    {
        if (!isset($GLOBALS['Contact_AddressBook_CSV_headers'][$format]))
        {
            return PEAR::raiseError('Uknown format \'' . $format .'\'');
        }

        if ($languange !== null)
        {
            $languange = 'en';
        }
        else
        {
            $languange = strtolower($languange);
        }

        $dir = 'Contact/AddressBook/CSV/Header/';
        $file = $GLOBALS['Contact_AddressBook_CSV_headers'][$format] . '/' .
                $languange . '.php';

        include_once $dir . $file;
        if (!isset($GLOBALS['Contact_AddressBook_CSV_header']))
        {
            return PEAR::raiseError('Undefined header');
        }

        return $GLOBALS['Contact_AddressBook_CSV_header'];
    }

    // }}}
}

// }}}

print_r(Contact_AddressBook_CSV::loadHeader('yahoo', 'en'));

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
