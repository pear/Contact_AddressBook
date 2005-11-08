<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_Converter_CSV class.
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
// {{{ Dependencies

/**
 * Load Contact_AddressBook_CSV for common CSV operation.
 */
require_once 'Contact/AddressBook/CSV.php';

/**
 * Load Contact_AddressBook_Converter as the base class.
 */
require_once 'Contact/AddressBook/Converter.php';

// }}}
// {{{ Global Variables

/**
 * Definition aliases.
 *
 * @global array $GLOBALS['_Contact_AddressBook_CSV_definitions']
 * @name $_Contact_AddressBook_definitions
 */
$GLOBALS['_Contact_AddressBook_CSV_definitions'] = array(
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

// }}}
// {{{ Class: Contact_AddressBook_Converter_CSV

/**
 * Class for handling CSV address book data structure converting.
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
class Contact_AddressBook_Converter_CSV extends Contact_AddressBook_Converter
{
    // {{{ Properties

    /**
     * Header language, use only if the address book is selectable fiels
     * such as Ms WAB.
     *
     * @var string
     * @access private
     */
    var $_language = 'en';

    /**
     * Address book format (promoted as needed)
     *
     * @var string
     * @access private
     */
    var $_format = '';

    /**
     * Tell the whether converting is based on header or not.
     *
     * @var bool
     * @access private
     */
    var $_useHeader = false;

    // }}}
    // {{{ Constructor

    /**
     * Constructor.
     *
     * @access public
     */
    function Contact_AddressBook_Converter_CSV()
    {
        $this->setDefinitionDir(Contact_AddressBook::getDataDir() . '/CSV/Defs/');
    }

    // }}}
    // {{{ setFormat()

    /**
     * Set the address book format to convert.
     *
     * @param string $format The address book format.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     *
     * @see Contact_AddressBook_Converter::setDefinitionFile()
     */
    function setFormat($format)
    {
        $this->_format = strtolower($format);

        $config = Contact_AddressBook_CSV::getConfig($format);
        if (PEAR::isError($config)) {
            return $config;
        }

        if (isset($config['selectable_fields']) &&
            $config['selectable_fields'] == true) {
            $this->_useHeader = true;
        }

        if (!isset($GLOBALS['_Contact_AddressBook_CSV_definitions'][$format])) {
            return PEAR::raiseError('No such definition for \'' . $format .'\'');
        }

        $file = $this->definitionDir .
                $GLOBALS['_Contact_AddressBook_CSV_definitions'][$format] .
                '.def';

        return $this->setDefinitionFile($file);
    }

    // }}}
    // {{{ setLanguage()

    /**
     * Set the language.
     *
     * @param string $language Language.
     *
     * @access public
     */
    function setLanguage($language)
    {
        $this->_language = strtolower($language);
    }

    // }}}
    // {{{ performConvertByHeader()

    /**
     * Perform converting based on header.
     *
     * @param array $map Converting map.
     * @param array $data Address book data to convert.
     *
     * @return array|PEAR_Error An array of converted data on succeed or
     *                          PEAR_Error on failure
     * @access protected
     * @see Contact_AddressBook_Converter::performConvert()
     */
    function performConvertByHeader($map, $data)
    {
        if (!is_array($data)) {
            return PEAR::raiseError('Data is empty');
        }

        $header = Contact_AddressBook_CSV::getHeader($this->_format,
                                                     $this->_language);
        if (PEAR::isError($header)) {
            return $header;
        }

        // Flip the keys and values.
        $header = array_flip($header);
        foreach ($header as $key => $value) {
            $header[$key] = '';
        }

        // Convert the row from associative array into sequence array.
        $tmp = array();
        foreach ($data as $row) {
            $tmp[] = array_values(array_merge($header, $row));
        }

        return $this->performConvert($map, $tmp);
    }

    // }}}
    // {{{ convertFrom()

    /**
     * Convert from default data structure into external structure.
     *
     * @param array $data Address book data.
     *
     * @return array|PEAR_Error An array of converted data on succeed or
     *                          PEAR_Error on failure
     * @access public
     * @see performConvert()
     */
    function convertFrom($data)
    {
        if ($this->_useHeader) {
            if (!$this->isPrepared) {
                $res = $this->prepare();
                if (PEAR::isError($res)) {
                    return $res;
                }
            }

            return $this->performConvertByHeader($this->mapFrom, $data);
        }

        return parent::convertFrom($data);
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
