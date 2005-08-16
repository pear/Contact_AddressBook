<?php
include_once 'include/head.php';
include_once 'include/header.php';
?>
<h2>Introduction</h2>
<p>Contact_AddressBook is a PEAR package provide import-export address book mechanism. Contact_AddressBook refers to needed structure, convert the various address book structure format into it, then you can easily store it into file, database or another storage media.</p>
<dl>
<dt><b>Currently supportted formats:</b></dt>
<dt>1. KMail (KDE Mailer) CSV</dt>
<dt>2. Mozilla/Thunderbird/Netscape CSV</dt>
<dt>3. Microsoft Outlook CSV</dt>
<dt>4. Palm Pilot CSV</dt>
<dt>5. Microsoft Windows Address Book (WAB) CSV</dt>
<dt>6. Yahoo! Mail Address Book</dt>
<dt>7. Eudora</dt>
</dl>
<dl>
<dt><b>Examples</b></dt>
<dt>1. <a href="import.php">Import</a></dt>
<dt>2. <a href="export.php">Export</a></dt>
</dl>
<?php
include_once 'include/footer.php';
?>