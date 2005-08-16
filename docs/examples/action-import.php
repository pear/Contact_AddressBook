<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker */

// Ignore this line.
include_once 'include/head.php';

if (isset($_POST['submit'])) {
    $error = false;
    if ($_POST['mode'] == 'sim') {
        include_once 'include/simulations.php';
        if (!isset($GLOBALS['_Contact_AddressBook_simulations'][$_POST['simId']])) {
            print 'Error: no simulation file selected';
            $error = true;
        } else {
            $file = dirname(__FILE__) . '/files/' .
                    $GLOBALS['_Contact_AddressBook_simulations'][$_POST['simId']]['file'];
            $format = $GLOBALS['_Contact_AddressBook_simulations'][$_POST['simId']]['format'];
        }
    } else {
        if (isset($_FILES['file'])) {
            if ($_FILES['file']['error'] !== 0) {
                print 'An error occurs when uploading file';
                $error = true;
            } else {
                $file = $_FILES['file']['tmp_name'];
                $format = $_POST['format'];
            }
        }
    }

    if (!$error) {
        // Load the Contact_AddressBook.
        require_once 'Contact/AddressBook.php';

        // Create a new instance of Contact_AddressBook object.
        $cab = new Contact_AddressBook;

        // Call the import method.
        $res = $cab->importFromFile($file, $format);

        // Done!
        print '<h3>File contents</h3>' . "\n";
        print '<pre>';
        print htmlspecialchars(file_get_contents($file));
        print '</pre>';

        print '<h3>Converted data</h3>' . "\n";
        print '<pre>';
        var_export($res);
        print '</pre>';
    }
}

// Ignore this line.
include_once 'include/footer.php';

/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
