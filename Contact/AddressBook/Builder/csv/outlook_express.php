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
 * File contains Contact_AddressBook_Builder_csv_outlook_express_en class.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Builder
 * @subpackage CSV
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Load AddressBook_Builder_csv as base class.
 */
require_once 'Contact/AddressBook/Builder/csv.php';

/**
 * Class for building Ms Outlook Express CSV address book format.
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Builder
 * @subpackage CSV
 */
class Contact_AddressBook_Builder_csv_outlook_express
extends Contact_AddressBook_Builder_csv
{
    // {{{ Properties

    /**
     * Options.
     *
     * @var array
     * @access protected
     */
    var $options = array(
        'line_break' => "\n",
        'language'   => 'en'
    );

    /**
     * Flag for set to write the header or not.
     *
     * @var bool
     * @access protected
     */
    var $writeHeader = true;

    /**
     * Address book field English header.
     *
     * @var array
     * @access private
     */
    var $_header_en = array(
        'First Name',
        'Last Name',
        'Middle Name',
        'Name',
        'Nickname',
        'E-mail Address',
        'Home Street',
        'Home City',
        'Home Postal Code',
        'Home State',
        'Home Country/Region',
        'Home Phone',
        'Home Fax',
        'Mobile Phone',
        'Personal Web Page',
        'Business Street',
        'Business City',
        'Business Postal Code',
        'Business State',
        'Business Country/Region',
        'Business Web Page',
        'Business Phone',
        'Business Fax',
        'Pager',
        'Company',
        'Job Title',
        'Department',
        'Office Location',
        'Notes'
    );

    /**
     * Address book field Deutsch header.
     *
     * @var array
     * @access private
     */
    var $_header_de = array(
        'Vorname',
        'Nachname',
        '2. Vorname',
        'Name',
        'Rufname',
        'E-Mail-Adresse',
        'Straße (privat)',
        'Ort (privat)',
        'Postleitzahl (privat)',
        'Bundesland (privat)',
        'Land/Region (privat)',
        'Rufnummer (privat)',
        'Fax (privat)',
        'Mobiltelefon',
        'Webseite (privat)',
        'Straße (geschäftlich)',
        'Ort (geschäftlich)',
        'Postleitzahl (geschäftlich)',
        'Bundesland (geschäftlich)',
        'Land/Region (geschäftlich)',
        'Firmenwebseite',
        'Rufnummer (geschäftlich)',
        'Fax (geschäftlich)',
        'Pager',
        'Firma',
        'Position',
        'Abteilung',
        'Büro',
        'Kommentare'
    );

    /**
     * Address book fields count.
     *
     * @var int
     * @access protected
     */
    var $fieldsCount = 29;

    // }}}
    // {{{ Constructor

    /**
     * PHP 4 compatible constructor.
     *
     * @param array $data (optional) Data.
     * @param array $options (optional) Options.
     *
     * @access public
     */
    function Contact_AddressBook_Builder_csv_outlook_express($data = null,
                                                             $options = null)
    {
        parent::__construct($data, $options);
    }

    // }}}
    // {{{ build()

    /**
     * Build the structure format.
     *
     * @return bool|PEAR_Error TRUE on success or PEAR_Error on failure.
     * @access public
     */
    function build()
    {
        $headerVar = '_header_' . $this->options['language'];
        if (!isset($this->$headerVar)) {
            return PEAR::raiseError("Header not available for language ".
                                    strtoupper($this->options['language']));
        }

        $this->header = $this->$headerVar;
        parent::build();
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
