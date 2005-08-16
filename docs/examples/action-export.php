<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker */

// Load the Contact_AddressBook.
require_once 'Contact/AddressBook.php';

// Include our records.
include_once 'include/records.php';

if (isset($_POST['submit'])) {
    if (!empty($_POST['format'])) {
        // Create a new instance of Contact_AddressBook object.
        $cab = new Contact_AddressBook;

        if ($_POST['mode'] == 'print') {
            include_once 'include/head.php';
            print '<pre>';

            // Call export to print method.
            $cab->exportToPrint($_POST['format'],
                                $GLOBALS['_Contact_AddressBook_records']);

            print '</pre>';
            include_once 'include/footer.php';
        } else {
            // Call export to file method.
            $cab->exportToFile('addressbook', $_POST['format'],
                               $GLOBALS['_Contact_AddressBook_records']);
        }
    }
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
