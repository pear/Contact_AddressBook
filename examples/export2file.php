<?php
/**
 * Example for exporting the address book.
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
    print htmlspecialchars($res);
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

// Create Contact_AddressBook object instance.
$addbook = new Contact_AddressBook;

// Data may be take from storage media e.g file or database.
// But should be converted into an 2D array, like below.
$data = array(
    array(
        'firstname'     => 'foo',
        'lastname'      => 'bar',
        'middlename'    => 'mid',
        'displayname'   => 'foo bar',
        'nickname'      => 'foo',
        'email'         => 'foo@bar.com',
        'work_phone'    => '9999999',
        'home_phone'    => '9999999',
        'home_fax'      => '9999999',
        'pager'         => '9999999',
        'mobile'        => '999999999',
        'home_address'  => 'Foo Street 8',
        'home_city'     => 'Foo City',
        'home_state'    => 'Foo State',
        'home_zipcode'  => '99999',
        'home_country'  => 'Foo',
        'work_address'  => 'Foo Street 8',
        'work_city'     => 'Foo',
        'work_state'    => 'Foo',
        'work_zipcode'  => '99999',
        'work_country'  => 'Foo',
        'job_title'     => 'Web Developer',
        'department'    => 'Foo Net',
        'organization'  => 'Foo',
        'work_homepage' => 'http://www.foo.com',
        'homepage'      => 'http://foo.bar.com',
        'notes'         => 'Nothing about foo!'
    )
);

print "<style>pre {border: 1px dashed #bbbbbb; background: #eeeeee;}</style>\n";

// CSV Netscape address book format.
$err = $addbook->exportToFile('outputs/csv_netscape.csv', 'csv_netscape',
                              $data, false);
die_error($err);
echo_result('CSV Netscape Export Address Book File Content',
            file_get_contents('outputs/csv_netscape.csv'));
print "<br /><br />\n";

// CSV Outlook Express address book format.
$err = $addbook->exportToFile('outputs/csv_outlook_express.csv',
                              'csv_outlook_express', $data, false);
die_error($err);
echo_result('CSV Outlook Express Address Book Export File Content',
            file_get_contents('outputs/csv_outlook_express.csv'));
print "<br /><br />\n";

// Eudora address book format.
$err = $addbook->exportToFile('outputs/eudora.txt', 'eudora', $data, false);
die_error($err);
echo_result('Eudora Address Book Export File Content',
            file_get_contents('outputs/eudora.txt'));
?>
