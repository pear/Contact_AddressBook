<?php
/**
 * Example for importing the address book.
 *
 * $Id$
 *
 * @author Firman Wandayandi <firman@php.net>
 * @package Contact_AddressBook
 * @subpackage Examples
 * @category FileFormats
 * @license http://www.opensource.org/licenses/bsd-license.php
 *          BSD License
 */

/**
 * Load Contact_AddressBook class.
 */
require_once 'Contact/AddressBook.php';

function echo_result($header, $res)
{
    print "<strong>$header</strong><br />\n";
    print "<pre>\n";
    print_r($res);
    print "</pre>\n";
}

function die_error($err)
{
    if (PEAR::isError($err)) {
        print "<strong>Error</strong><br />\n";
        print $err->toString();
        die;
    }
}

// Create AddressBook object instance.
$addbook = new Contact_AddressBook;

print "<style>pre {border: 1px dashed #bbbbbb; background: #eeeeee;}</style>\n";

// CSV Netscape address book format.
$res = $addbook->importFromFile('books/csv_netscape.csv', 'csv_netscape');
die_error($res);
echo_result('CSV Netscape Address Book Import Result', $res);
print "<br /><br />\n";

// CSV Outlook Express address book format.
$res = $addbook->importFromFile('books/csv_outlook_express.csv',
                                'csv_outlook_express');
die_error($res);
echo_result('CSV Outlook Express Address Book Import Result', $res);
print "<br /><br />\n";

// Eudora address book format.
$res = $addbook->importFromFile('books/eudora.txt', 'eudora');
die_error($res);
echo_result('Eudora Address Book Import Result', $res);
?>
