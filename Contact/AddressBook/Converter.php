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
// {{{ Class: Contact_AddressBook_Converter

/**
 * Class for handling address book data structure converting.
 *
 * @category FileFormats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 */
class Contact_AddressBook_Converter
{
    // {{{ Properties

    var $definitionFile = '';

    /**
     * Address Book fields definition.
     *
     * @var array
     * @access protected
     */
    var $definition = array();

    /**
     * Map for converting from external structure into default structure.
     *
     * @var array
     * @access protected
     */
    var $mapFrom = array();

    /**
     * Map for converting from default structure into external structure.
     *
     * @var array
     * @access protected
     */
    var $mapTo = array();

    /**
     * Flag tell the whether the map is prepared or not.
     *
     * @var bool
     * @access protected
     */
    var $isPrepared = false;

    var $format = '';
    var $languange = 'en';

    // }}}
    // {{{ Constructor

    /**
     * PHP4 Compatible contructor.
     *
     * @param string $definitionFile Definition filename.
     *
     * @access public
     * @see __construct()
     */
    function Contact_AddressBook_Converter($format, $languange = null)
    {
        $this->__construct($format, $languange);
    }

    /**
     * PHP5 Compatible contructor.
     *
     * @param string $definitionFile Definition filename.
     *
     * @access public
     */
    function __construct($format, $language = null)
    {
        $this->format = $format;
        if ($language !== null)
        {
            $this->languange = $language;
        }
    }

    // }}}
    // {{{ setDefinitionFile()

    function setDefinitionFile($file)
    {
        $this->definitionFile = $file;
        return $this->loadDefinition();
    }

    // }}}
    // {{{ loadDefinitionFile()

    /**
     * Load the definition file.
     * The bundle default definition files was placed at PEAR data dir.
     *
     * @param string $file Definition filename.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access public
     */
    function loadDefinition()
    {
        $res = Contact_AddressBook_CSV::loadDefinition($this->definitionFile);
        if (empty($res))
        {
            return PEAR::raiseError($php_errormsg);
        }

        $this->setDefinition($res);
        return true;
    }

    // }}}
    // {{{ setDefinition()

    /**
     * Set custom definition on runtime.
     *
     * @param array $definition An array of definition structure.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access public
     */
    function setDefinition($definition)
    {
        if (!is_array($definition))
        {
            return PEAR::raiseError('Type mismatch for definition variable');
        }

        $this->definition = $definition;

        // Set isPrepared flag to false, we need to reset the maps with new
        // definition.
        if ($this->isPrepared)
        {
            $this->isPrepared = false;
        }

        return true;
    }

    // }}}
    // {{{ _prepare()

    /**
     * Prepare map for converting.
     *
     * @return bool|PEAR_Error TRUE on succeed or PEAR_Error on failure.
     * @access private
     */
    function _prepare()
    {
        if (empty($this->definition)) {
            $res = $this->setDefinitionFile(
                Contact_AddressBook_CSV::getDefaultDefinitionFile($this->format)
            );

            if (PEAR::isError($res))
            {
                return $res;
            }
        }

        $this->mapFrom =& $this->definition;

        $tmp = array_flip($this->mapFrom);
        foreach ($tmp as $key => $val) {
            if ($key === '' || $val === '') {
                unset($tmp[$key]);
            }
        }

        $this->mapTo = $tmp;
        $this->isPrepared = true;
        return true;
    }

    // }}}
    // {{{ performConvert()

    /**
     * Perform data converting.
     *
     * @param array $map Converting map.
     * @param array $data Address book data to convert.
     *
     * @return array|PEAR_Error An array of converted data on succeed or
     *                          PEAR_Error on failure
     * @access protected
     */
    function performConvert($map, $data)
    {
        if (!is_array($data)) {
            return PEAR::raiseError('Data is empty');
        }

        $res = array();
        foreach ($data as $i => $row) {
            foreach ($row as $data_key => $val) {
                if (!isset($map[$data_key]) || $map[$data_key] === '') {
                    continue;
                }

                $map_key = $map[$data_key];
                $res[$i][$map_key] = trim($val);
            }
        }
        return $res;
    }

    // }}}
    // {{{ convertTo()

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
    function convertTo($data)
    {
        if (!$this->isPrepared) {
            $this->_prepare();
        }

        return $this->performConvert($this->mapTo, $data);
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
        if (!$this->isPrepared) {
            $this->_prepare();
        }

        return $this->performConvert($this->mapFrom, $data);
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
