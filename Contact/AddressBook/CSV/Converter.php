<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Contact_AddressBook_CSV_Converter class.
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
 * @subpackage CSV
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
 * Load Contact_AddressBook_Converter as base class.
 */
require_once 'Contact/AddressBook/Converter.php';

// }}}
// {{{ Class: Contact_AddressBook_CSV_Converter

/**
 * Class for convert CSV data structure.
 *
 * @category FileFormats
 * @package Contact_AddressBook
 * @subpackage CSV
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 * @since Class available since Release 0.2.0dev1
 */
class Contact_AddressBook_CSV_Converter extends Contact_AddressBook_Converter
{
    // {{{ Constructor

    /**
     * PHP4 compatible constructor.
     *
     * @param string $format Address book format name.
     * @param string $language (optional) Language to use (affected if
     *                                    the selectablefields config is TRUE).
     *
     * @access public
     * @see __construct()
     */
    function Contact_AddressBook_CSV_Converter($format, $language = null)
    {
        $this->__construct($format, $language);
    }

    /**
     * PHP5 compatible constructor.
     *
     * @param string $format Address book format name.
     * @param string $language (optional) Language to use (affected if
     *                                    the selectablefields config is TRUE).
     *
     * @see Contact_AddressBook_Converter::__construct()
     * @see loadConfig()
     */
    function __construct($format, $language = null)
    {
        parent::__construct($format, $language);
    }

    // }}}
    // {{{ synchronizeHeader()

    /**
     * Synchronize data structure base on header.
     *
     * @param array $data Data.
     *
     * @access protected.
     * @return array|PEAR_Error An array result on success or
     *                          PEAR_Error on failure.
     * @see Contact_AddressBook_CSV::loadHeader()
     */
    function synchronizeHeader($data)
    {
        $header = Contact_AddressBook_CSV::loadHeader($this->format,
                                                      $this->languange);
        if (PEAR::isError($header))
        {
            return $header;
        }

        $res = array();
        $default = array_fill(0, count($header), '');
        foreach ($data as $i => $row)
        {
            $res[$i] = $default;
            foreach ($row as $name => $value)
            {
                if (($num = array_search($name, $header)) !== false)
                {
                    $res[$i][$num] = $value;
                }
            }
        }

        return $res;
    }

    // {{{ convertFrom()

    /**
     * Convert from default data structure into external structure.
     *
     * @param array $data Address book data.
     *
     * @return array|PEAR_Error An array of converted data on succeed or
     *                          PEAR_Error on failure
     * @access public
     * @see synchronizeHeader()
     * @see Contact_AddressBook_Converter::convertFrom()
     */
    function convertFrom($data)
    {
        // Contact_AddressBook default CSV config.
        $sysconfig = Contact_AddressBook_CSV::getConfig($this->format);
        if (PEAR::isError($sysconfig))
        {
            return $sysconfig;
        }

        if ($sysconfig['selectablefields'])
        {
            $data = $this->synchronizeHeader($data);
        }

        return parent::convertFrom($data);
    }
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
