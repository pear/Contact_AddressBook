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
 * File contains Contact_AddressBook class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 */

/**
 * Use PEAR for handling error.
 */
require_once 'PEAR.php';

/**
 * Main Address_Book class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_Address_Book
 */
class Contact_AddressBook
{
    // {{{ Properties

    /**
     * Options.
     *
     * @var array
     * @access protected
     */
    var $options = array(
        'def_dir'       => '',         // Definition directory.
        'languange'     => 'en',       // Language.
        'line_break'    => "\n"        // Line break character for writing a file.
    );

    // }}}
    // {{{ Constructor

    /**
     * PHP4 compatible constructor.
     *
     * @param array $options Options.
     *
     * @access public
     */
    function Contact_AddressBook($options = null)
    {
        $this->__construct($options);
    }

    /**
     * PHP5 compatible constructor.
     *
     * @param array $options Options.
     *
     * @access public
     */
    function __construct($options = null)
    {
        if (!isset($options['def_dir'])){
            require_once 'PEAR/Config.php';
            $config = new PEAR_Config;
            $options['def_dir'] = $config->get('data_dir') .
                                  '/Contact_AddressBook/definitions/';
        }

        $this->set($options);
    }

    // }}}
    // {{{ set()

    /**
     * Set the option(s).
     *
     * Set a single option or multiple options.
     *
     * @param mixed $option A string with option name as value for
     *                      single option or An associative array contains
     *                      options array('<option>' => <value>) for multiple
     *                      options.
     * @param mixed $value (optional) Option value. Require when $option is
     *                                string (single option mode).
     *
     * @return bool|PEAR_Error TRUE on success, PEAR_Error on failure.
     * @access public
     */
    function set($option, $value = null)
    {
        if (is_array($option)) {
            foreach ($option as $key => $val) {
                $err = $this->set($key, $val);
                if (PEAR::isError($err)) {
                    return $err;
                }
            }
        } elseif (is_string($option)) {
            if (isset($this->options[$option])) {
                if (gettype($this->options[$option]) == gettype($value)) {
                    $this->options[$option] = $value;
                } else {
                    return PEAR::raiseError("Type mismatch for option '$option' value");
                }
            }
        }
    }

    // }}}
    // {{{ createObject()

    /**
     * Create AddressBook component object.
     *
     * @param string $file Filename.
     * @param string $class Classname.
     *
     * @return object Component object on success or PEAR_Error on failure.
     * @access public
     * @static
     */
    function &createObject($file, $class)
    {
        include_once $file;

        if (!class_exists($class)) {
            return PEAR::raiseError("Undefined class '$class'");
        }

        $obj =& new $class;
        return $obj;
    }

    // }}}
    // {{{ createParser()

    /**
     * Create parser object for specified driver.
     *
     * @param string $driver Driver name.
     *
     * @return object Parser object on success or PEAR_Error on failure.
     * @access public
     */
    function &createParser($driver)
    {
        if (substr($driver, 0, 3) == 'csv') {
            $class = 'Contact_AddressBook_Parser_csv';
            $file = 'Contact/AddressBook/Parser/csv.php';
        } else {
            $class = 'Contact_AddressBook_Parser_' . $driver;
            $file = "Contact/AddressBook/Parser/{$driver}.php";
        }

        $obj =& Contact_AddressBook::createObject($file, $class);
        return $obj;
    }

    // }}}
    // {{{ createBuilder()

    /**
     * Create builder object for specified driver.
     *
     * @param string $driver Driver name.
     *
     * @return object Builder object on success or PEAR_Error on failure.
     * @access public
     */
    function &createBuilder($driver, $options = null)
    {
        $class = 'Contact_AddressBook_Builder_' . $driver;
        if (substr($driver, 0, 3) == 'csv') {
            $driver = str_replace('csv_', '', $driver);
            $file = "Contact/AddressBook/Builder/csv/{$driver}.php";
        } else {
            $file = "Contact/AddressBook/Builder/{$driver}.php";
        }

        $obj =& Contact_AddressBook::createObject($file, $class);
        if (is_a($obj, 'Contact_AddressBook_Builder')) {
            $obj->set($this->options);
        }

        return $obj;
    }

    // }}}
    // {{{ createConverter()

    /**
     * Create converter object for specified driver.
     *
     * @param string $driver Driver name.
     *
     * @return object Converter object on success or PEAR_Error on failure.
     * @access public
     */
    function &createConverter($driver)
    {
        $class = 'Contact_AddressBook_Converter';
        $file = 'Contact/AddressBook/Converter.php';

        $obj =& Contact_AddressBook::createObject($file, $class);
        if (is_a($obj, 'Contact_AddressBook_Converter')) {
            $obj->setDefinitionDir($this->options['def_dir']);
            $obj->loadDefinitionFile($driver . '.def');
        }

        return $obj;
    }

    // }}}
    // {{{ isExportable()

    /**
     * Find out the whether address book format is exportable.
     *
     * @param string $format Address book format name.
     *
     * @return bool TRUE if exportable, otherwise FALSE.
     * @access public
     */
    function isExportable($format)
    {
        if (substr($driver, 0, 3) == 'csv') {
            $format = str_replace('csv_', '', $format);
            $file = "Contact/AddressBook/Builder/csv/{$format}.php";
        } else {
            $file = "Contact/AddressBook/Builder/{$format}.php";
        }

        return file_exists($file);
    }

    // }}}
    // {{{ isImportable()

    /**
     * Find out the whether address book format is importable.
     *
     * @param string $format Address book format name.
     *
     * @return bool TRUE if importable, otherwise FALSE.
     * @access public
     */
    function isImportable($format)
    {
        $file = $this->options['def_dir'] . $format . '.def';
        return file_exists($file);
    }

    // }}}
    // {{{ import()

    /**
     * Import data from specified format structure to internal structure.
     *
     * @param array $data Data.
     * @param string $format Structure format.
     *
     * @return array|PEAR_Error An array result on success or PEAR_Error on
     *                          failure.
     * @access public
     */
    function import($data, $format)
    {
        $conv =& $this->createConverter($format);
        if (PEAR::isError($conv)) {
            return $conv;
        }

        return $conv->convertFrom($data);
    }

    // }}}
    // {{{ export()

    /**
     * Export data from internal structure to external format structure.
     *
     * @param array $data Data.
     * @param string $format Structure format.
     *
     * @return array|PEAR_Error An array result on success or PEAR_Error on
     *                          failure.
     * @access public
     */
    function export($data, $format)
    {
        $conv =& $this->createConverter($format);
        if (PEAR::isError($conv)) {
            return $conv;
        }

        $conv->convertTo($data);
        return $conv->convertTo($data);
    }

    // }}}
    // {{{ importFromFile()

    /**
     * Import data from specified format structure saved in the file to
     * internal structure.
     *
     * @param string $filename Filename.
     * @param string $format File format.
     *
     * @return array|PEAR_Error An array result on success or PEAR_Error on
     *                          failure.
     * @access public
     */
    function importFromFile($filename, $format)
    {
        $parser =& $this->createParser($format);
        if (PEAR::isError($parser)) {
            return $parser;
        }

        $parser->setInput($filename);
        $parser->parse();
        $res = $parser->getResult();

        return $this->import($res, $format);
    }

    // }}}
    // {{{ exportToFile()

    /**
     * Export data from internal format to external data structure and save
     * it to file.
     *
     * @param string $filename Filename.
     * @param string $format File format.
     * @param array $data Address book contacts data.
     * @param bool $via_browser Tell the whether to output via browser
     *                          (downloading) or not.
     *
     * @return array|PEAR_Error An array result on success or PEAR_Error on
     *                          failure.
     * @access public
     */
    function exportToFile($filename, $format, $data, $via_browser = true)
    {
        $data = $this->export($data, $format);
        if (PEAR::isError($data)) {
            return $data;
        }

        $builder =& $this->createBuilder($format);
        if (PEAR::isError($builder)) {
            return $builder;
        }

        $builder->setData($data);
        $builder->build();

        if ($via_browser) {
            return $builder->download($filename);
        } else {
            return $builder->save($filename);
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
