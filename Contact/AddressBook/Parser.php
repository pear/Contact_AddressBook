<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_CSV_Parser class.
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
 * @since File available since Release 0.2.0dev1
 */

// }}}
// {{{ Dependencies

/**
 * Load Contact_AddressBook_Parser as the base class.
 */
require_once 'Contact/AddressBook/Parser.php';

/**
 * Load Contact_AddressBook_CSV for common functions.
 */
require_once 'Contact/AddressBook/CSV.php';

/**
 * Load File_CSV for handling CSV parser.
 */
require_once 'File/CSV.php';

// }}}
// {{{ Class: Contact_AddressBook_CSV_Parser

/**
 * Class for parse CSV address book format.
 *
 * @category FileFormats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 * @see File_CSV
 * @since Class available since Release 0.2.0dev1
 */
class Contact_AddressBook_CSV_Parser extends Contact_AddressBook_Parser
{
    // {{{ Properties

    /**
     * Configuration mapping to File_CSV.
     *
     * @var array
     * @access private
     */
    var $_configMap = array(
        'fields'    => 'fields',
        'quote'     => 'quote',
        'header'    => 'header',
        'crlf'      => 'linebreak'
    );

    // }}}
    // {{{ Constructor

    /**
     * PHP4 compatible contructor.
     *
     * @param string $format CSV address book format. One of supported formats
     *                       WAB, OutlookExpress, Outlook, Mozilla, Netscape,
     *                       Thunderbird, Palm, Yahoo (case-insensitive and
     *                       only alphanumeric letters).
     *
     * @access public
     * @see __contruct()
     */
    function Contact_AddressBook_CSV_Parser($format)
    {
        $this->__construct($format);
    }

    /**
     * PHP5 compatible contructor.
     *
     * @param string $format CSV address book format. One of supported formats
     *                       WAB, OutlookExpress, Outlook, Mozilla, Netscape,
     *                       Thunderbird, Palm, Yahoo (case-insensitive and
     *                       only alphanumeric letters).
     *
     * @access public
     * @see __contruct()
     */
    function __construct($format)
    {
        $this->_format = strtolower($format);
    }

    // }}}
    // {{{ _parseConfig()

    /**
     * Parse configuration for use in File_CSV.
     *
     * @access private
     * @return array An array of configuration.
     */
    function _parseConfig()
    {
        $sysconfig = Contact_AddressBook_CSV::getConfig($this->_format);
        if (PEAR::isError($sysconfig))
        {
            return $sysconfig;
        }

        $config = array(
            'sep'   => ','
        );

        foreach ($this->_configMap as $key => $name) {
            if (isset($sysconfig[$name])) {
                if ($name == 'linebreak' && $sysconfig[$name] == 'auto') {
                    continue;
                }

                $config[$key] = $sysconfig[$name];
            }
        }
        return $config;
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
        // File_CSV detected config.
        $config = File_CSV::discoverFormat($this->file);
        if (PEAR::isError($config))
        {
            return $config;
        }

        // Contact_AddressBook default CSV config.
        $sysconfig = Contact_AddressBook_CSV::getConfig($this->_format);
        if (PEAR::isError($sysconfig))
        {
            return $sysconfig;
        }

        // File_CSV config.
        $csvconfig = $this->_parseConfig();
        if (PEAR::isError($csvconfig))
        {
            return $csvconfig;
        }

        // Force override fields count with system configuration, there's
        // some bugs in File_CSV::discoverFormat().
        if (isset($csvconfig['fields']))
        {
            unset($config['fields']);
        }

        $config = array_merge($config, $csvconfig);

        if (isset($sysconfig['selectablefields']) &&
            $sysconfig['selectablefields'])
        {
            $config['header'] = false;
        }

        $data = array();
        while ($res = File_CSV::readQuoted($this->file, $config)) {
            $data[] = $res;
        }

        if (!empty($data)) {
            // Finalize header for address book selectable fields such
            // WAB instead of Outlook Express.
            if (isset($sysconfig['selectablefields']) &&
                $sysconfig['selectablefields'])
            {
                $data = Contact_AddressBook_CSV_Parser::finalizeHeader($data);
            }

            $this->result = $data;
        }

        return true;
    }

    // }}}
    // {{{ finalizeHeader()

    /**
     * Change the array of data from sequential array into associative array,
     * using header elements as keys.
     *
     * This function only called if configuration 'selectablefields' is TRUE.
     *
     * @return array() An associative array of result.
     * @access public
     * @static
     */
    function finalizeHeader($data)
    {
        $header = array_shift($data);
        $res = array();
        if (!is_array($header)) {
            return $data;
        }

        foreach ($header as $i => $field) {
            foreach ($data as $j => $values) {
                if (isset($values[$i])) {
                    $res[$j][$field] = $values[$i];
                }
            }
        }
        return $res;
    }

    // }}}
}

// }}}

$obj = new Contact_AddressBook_CSV_Parser('wab');
$obj->setFile('files/WAB-selectable.csv');
$obj->parse();
$data = $obj->getResult();

require_once 'Contact/AddressBook/CSV/Converter.php';
$conv = new Contact_AddressBook_CSV_Converter('wab');
$res = $conv->convertFrom($data);
print_r($res);

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
