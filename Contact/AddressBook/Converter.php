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
 * File contains Contact_AddressBook_Converter class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 */

/**
 * Class for handling address book data structure converting.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 */
class Contact_AddressBook_Converter
{
    // {{{ Properties

    /**
     * Address Book default definition.
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

    // }}}
    // {{{ Constructor

    /**
     * PHP4 Compatible contructor.
     *
     * @param string $def_dir Definition files directory.
     *
     * @access public
     */
    function Contact_AddressBook_Converter($def_dir = null)
    {
        $this->__construct($def_dir);
    }

    /**
     * PHP5 Compatible contructor.
     *
     * @param string $def_dir Definition files directory.
     *
     * @access public
     */
    function __construct($def_dir = null)
    {
        $this->setDefinitionDir($def_dir);
    }

    // }}}
    // {{{ __toString()

    /**
     * PHP5 __toString magic method.
     *
     * @return string
     * @access public
     */
    function __toString()
    {
        return var_export($this, true);
    }

    // }}}
    // {{{ setDefinitionDir()

    function setDefinitionDir($dir)
    {
        if ($dir === null) {
            return;
        }

        if (trim($dir) != '' && (substr($dir, -1) != DIRECTORY_SEPARATOR &&
            substr($dir, -1) != '/'))
        {
            $dir .= DIRECTORY_SEPARATOR;
        }

        $this->def_dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
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
    function loadDefinitionFile($file)
    {
        $file = $this->def_dir . $file;
        ini_set('track_error', true);
        $res = parse_ini_file($file);
        if (empty($res)) {
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
        if (!is_array($definition)) {
            return PEAR::raiseError('Definition type mismatch');
        }

        $this->definition = $definition;

        // Set isPrepared flag to false, we need to reset the maps with new
        // definition.
        if ($this->isPrepared) {
            $this->isPrepare = false;
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
    function prepare()
    {
        if (empty($this->definition)) {
            return PEAR::raiseError('Definition never set');
        }

        $this->mapFrom =& $this->definition;

        $tmp = array_flip($this->mapFrom);
        foreach ($tmp as $key => $val) {
            if ($key === '' || $val === '') {
                unset($tmp[$key]);
            }
        }

        $this->mapTo = $tmp;
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
            $this->prepare();
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
            $this->prepare();
        }

        return $this->performConvert($this->mapFrom, $data);
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
