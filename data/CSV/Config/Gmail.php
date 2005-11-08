<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

// {{{ Header

/**
 * File contains GMail CSV address book configuration.
 *
 * PHP versions 4 and 5
 *
 * @category File Formats
 * @package Contact_AddressBook
 * @subpackage CSV
 * @author Firman Wandayandi <firman@php.net>
 * @copyright Copyright (c) 2004-2005 Firman Wandayandi
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 * @version CVS: $Id$
 * @since File available since Release 0.5.0
 */

// }}}

/**
 * Gmail CSV address book configuration.
 *
 * @global array $GLOBALS['_Contact_AddressBook_CSV_config']
 * @name $_Contact_AddressBook_CSV_config
 */
$GLOBALS['_Contact_AddressBook_CSV_config'] = array(
    'fields'        => 13,
    'quote'         => '"',
    'line_break'    => "\n",
    'header'        => true,
    'multilines'    => true
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