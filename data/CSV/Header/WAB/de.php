<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains Microsoft WAB (Windows Address Book) Deutsch header, this
 * address book integrated to Ms Outlook Express.
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * BSD License
 *
 * Copyright (c) 2005 Firman Wandayandi
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
 * @package AddressBook
 * @subpackage CSV
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version CVS: $Id$
 * @since File available since Release 0.4.0alpha1
 */

// }}}

/**
 * Microsoft WAB (Windows Address Book) address book CSV Deutsch header.
 *
 * @global array $GLOBALS['_Contact_AddressBook_CSV_header']
 * @name $_Contact_AddressBook_CSV_header
 */
$GLOBALS['_Contact_AddressBook_CSV_header'] = array(
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

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
