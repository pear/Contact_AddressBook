<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains main class of Contact_AddressBook.
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
 */

// }}}
// {{{ Dependencies

/**
 * Load PEAR as main framework.
 */
require_once 'PEAR.php';

// }}}
// {{{ Global Variables

/**
 * Classes file path informations.
 *
 * @global array $GLOBALS['_Contact_AddressBook_paths']
 * @name $_Contact_AddressBook_paths
 */
$GLOBALS['_Contact_AddressBook_paths'] = array(
    'csv'       => array(
        'parser'    => array(
            'Contact/AddressBook/Parser/CSV.php',
            'Contact_AddressBook_Parser_CSV'
        ),
        'converter' => array(
            'Contact/AddressBook/Converter/CSV.php',
            'Contact_AddressBook_Converter_CSV'
        ),
        'builder'   => array(
            'Contact/AddressBook/Builder/CSV.php',
            'Contact_AddressBook_Builder_CSV'
        )
    ),
    'eudora'    => array(
        'parser'    => array(
            'Contact/AddressBook/Parser/Eudora.php',
            'Contact_AddressBook_Parser_Eudora'
        ),
        'converter' => array(
            'Contact/AddressBook/Converter.php',
            'Contact_AddressBook_Converter'
        ),
        'builder'   => array(
            'Contact/AddressBook/Builder/Eudora.php',
            'Contact_AddressBook_Builder_Eudora'
        )
    )
);

// }}}
// {{{ Initial Codes

// PHP_EOL Available since PHP 4.3.10 and PHP 5.0.2
if (!defined('PHP_EOL')) {
    // Fix this for MAC eol enable.
    if (PEAR_OS == 'Windows') {
        define('PHP_EOL', "\r\n");
    } else {
        define('PHP_EOL', "\n");
    }
}

// }}}
// {{{ Class: Contact_AddressBook

/**
 * Main Contact_AddressBook class.
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version Release: @package_version@
 *
 * @todo Add Vcard, LDIF and WAB (Binary) support.
 * @todo Mac format complain.
 * @todo PHPUnit tests.
 * @todo Add storage backend (is it necessary needed?)
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
    var $_options = array(
        'def_dir'   => '',         // Definitions directory.
        'language'  => 'en'        // Language.
    );

    /**
     * Supported format names.
     * Formats (case-insensitive):
     * <pre>
     * csv_wab              Ms Windows Address Book CSV.
     * csv_outlookexpress   Ms Windows Outlook Express CSV (equal with WAB).
     * csv_outlook          Ms Outlook CSV.
     * csv_mozilla          Mozilla Mailer CSV.
     * csv_thunderbird      Mozilla Thunderbird CSV (equal with Mozilla).
     * csv_netscape         Netscape Mailer CSV (equal with Mozilla).
     * csv_yahoo            Yahoo! CSV.
     * csv_palm             Palm CSV.
     * eudora               Eudora address book.
     * </pre>
     *
     * @var array
     * @access private
     */
    var $_supportedFormats = array(
        'csv_wab',
        'csv_outlookexpress',
        'csv_outlook',
        'csv_mozilla',
        'csv_thunderbird',
        'csv_netscape',
        'csv_yahoo',
        'csv_palm',
        'csv_kmail',
        'csv_gmail',
        'eudora'
    );

    // }}}
    // {{{ Constructor

    /**
     * Constructor.
     *
     * @param array $options (optional) The options.
     *
     * @access public
     */
    function Contact_AddressBook($options = null)
    {
        if (is_array($options)) {
            $this->set($options);
        }
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
            if (isset($this->_options[$option])) {
                if (gettype($this->_options[$option]) == gettype($value)) {
                    $this->_options[$option] = $value;
                } else {
                    return PEAR::raiseError("Type mismatch for option '$option' value");
                }
            }
        }
    }

    // }}}
    // {{{ get()

    /**
     * Get single or all options.
     *
     * @param string $option (optional) Option name.
     *
     * @return mixed Value of option or an array of all options.
     * @access public
     */
    function get($option = null)
    {
        if ($option !== null) {
            if (isset($this->_options[$option])) {
                return $this->_options[$option];
            } else {
                return PEAR::raiseError('Unknown option "' . $option . '"');
            }
        }
        return $this->_options;
    }

    // }}}
    // {{{ getDataDir()

    /**
     * Get PEAR data directory.
     *
     * @return string PEAR data directory.
     * @access public
     * @static
     */
    function getDataDir()
    {
        require_once 'PEAR/Config.php';
        $config = new PEAR_Config;
        return $config->get('data_dir') . '/Contact_AddressBook/data';
    }

    // }}}
    // {{{ isSupported()

    /**
     * Find the whether address book format is supported or not.
     *
     * @param string $format Address book format.
     *
     * @return bool TRUE if supported, otherwise FALSE.
     * @access public
     */
    function isSupported($format)
    {
        return in_array($format, $this->_supportedFormats);
    }

    // }}}
    // {{{ createParser()

    /**
     * Create parser object for the specified address book format.
     *
     * @param string $format The address book format name.
     *
     * @return object Parser object on success or PEAR_Error on failure.
     * @access public
     */
    function createParser($format)
    {
        $format = strtolower($format);
        if (!$this->isSupported($format)) {
            return PEAR::raiseError('Not supported format \'' . $format .'\'');
        }

        if (substr($format, 0, 3) == 'csv') {
            $format = substr($format, 4);
            $file = $GLOBALS['_Contact_AddressBook_paths']['csv']['parser'][0];
            $class = $GLOBALS['_Contact_AddressBook_paths']['csv']['parser'][1];

            include_once $file;
            $obj = new $class($format);
            $res = $obj->setFormat($format);
            if (PEAR::isError($res)) {
                return $res;
            }
        } else {
            $file = $GLOBALS['_Contact_AddressBook_paths'][$format]['parser'][0];
            $class = $GLOBALS['_Contact_AddressBook_paths'][$format]['parser'][1];

            include_once $file;
            $obj = new $class;
        }

        return $obj;
    }

    // }}}
    // {{{ createConverter()

    /**
     * Create converter object for the specified address book format.
     *
     * @param string $format The address book format name.
     *
     * @return object Converter object on success or PEAR_Error on failure.
     * @access public
     */
    function createConverter($format)
    {
        $format = strtolower($format);
        if (!$this->isSupported($format)) {
            return PEAR::raiseError('Not supported format \'' . $format .'\'');
        }

        if (substr($format, 0, 3) == 'csv') {
            $format = substr($format, 4);
            $file = $GLOBALS['_Contact_AddressBook_paths']['csv']['converter'][0];
            $class = $GLOBALS['_Contact_AddressBook_paths']['csv']['converter'][1];

            include_once $file;
            $obj = new $class;

            if (!empty($this->_options['def_dir'])) {
                $obj->setDefinitionDir($this->_options['def_dir']);
            }

            $res = $obj->setFormat($format);
            if (PEAR::isError($res)) {
                return $res;
            }

            $obj->setLanguage($this->_options['language']);
        } else {
            $file = $GLOBALS['_Contact_AddressBook_paths'][$format]['converter'][0];
            $class = $GLOBALS['_Contact_AddressBook_paths'][$format]['converter'][1];

            include_once $file;
            $obj = new $class;

            $res = $obj->setFormat($format);
            if (PEAR::isError($res)) {
                return $res;
            }
        }

        return $obj;
    }

    // }}}
    // {{{ createBuilder()

    /**
     * Create builder object for the specified address book format.
     *
     * @param string $format The address book format name.
     *
     * @return object Builder object on success or PEAR_Error on failure.
     * @access public
     */
    function createBuilder($format)
    {
        $format = strtolower($format);
        if (!$this->isSupported($format)) {
            return PEAR::raiseError('Not supported format \'' . $format .'\'');
        }

        if (substr($format, 0, 3) == 'csv') {
            $format = substr($format, 4);
            $file = $GLOBALS['_Contact_AddressBook_paths']['csv']['builder'][0];
            $class = $GLOBALS['_Contact_AddressBook_paths']['csv']['builder'][1];

            include_once $file;
            $obj = new $class;

            $res = $obj->setFormat($format);
            if (PEAR::isError($res)) {
                return $res;
            }

            $obj->setLanguage($this->_options['language']);
        } else {
            $file = $GLOBALS['_Contact_AddressBook_paths'][$format]['builder'][0];
            $class = $GLOBALS['_Contact_AddressBook_paths'][$format]['builder'][1];

            include_once $file;
            $obj = new $class;
        }

        return $obj;
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
        $conv = $this->createConverter($format);
        if (PEAR::isError($conv)) {
            return $conv;
        }

        return $conv->convertFrom($data);
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

        $parser->setFile($filename);
        $parser->parse();
        $res = $parser->getResult();

        return $this->import($res, $format);
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
    // {{{ exportToFile()

    /**
     * Export data from internal format to external data structure and save
     * it to file.
     *
     * @param string $filename Filename.
     * @param string $format File format.
     * @param array $data Address book contacts data.
     * @param bool $via_browser (optional) Tell the whether to output via
     *                                     browser (downloading) or not.
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
    // {{{ exportToPrint()

    /**
     * Export data from internal format to external data structure and print it.
     *
     * @param string $format File format.
     * @param array $data Address book contacts data.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function exportToPrint($format, $data)
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

        $builder->show();
        return true;
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
