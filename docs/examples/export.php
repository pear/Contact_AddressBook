<?php
include_once 'include/head.php';
include_once 'include/header.php';
include_once 'include/records.php';
?>
<div class="nav"><a href="index.php">Home</a></div>
<h2>Options</h2>
<dl>
<dt><b>Here the options to import the address book:</b></dt>
<dt>1. <i>defs_dir</i>, Definition directory (default is PEAR data directory)</dt>
<dt>2. <i>language</i>, Address book language in code (required only if the format is the selectable fields such as WAB, default is "en" english)</dt>
</dl>
<h2>In Action</h2>
<b>Here an Array we have:</b>
<div class="code">
<?php
print '<pre>';
var_export($GLOBALS['_Contact_AddressBook_records']);
print '</pre>';
?>
</div>
<form method="post" action="action-export.php" target="_blank">
<b>Select the export format:</b><br /> 
<select name="format" id="format">
<option value="" selected="selected">Select ...</option>
<option value="eudora">Eudora</option>
<option value="csv_kmail">KMail (KDE Mailer) CSV</option>
<option value="csv_mozilla">Mozilla/Thunderbird/Netscape CSV</option>
<option value="csv_outlook">Microsoft Outlook CSV</option>
<option value="csv_wab">Microsoft Windows Address Book (WAB)/Outlook Express CSV</option>
<option value="csv_palm">Palm Pilot CSV</option>
<option value="csv_yahoo">Yahoo! Mail Address Book</option>
<option value="csv_gmail">Gmail Address Book</option>
</select><br />
<b>Output options:</b><br />
<input name="mode" type="radio" value="print" id="mode_print" checked><label for="mode_print">Print it</label>
<input name="mode" type="radio" value="send" id="mode_send"><label for="mode_send">Donwload it</label><br />
<input name="submit" type="submit" id="submit" value="Export" />
</form>
<h2>Source</h2>
<div class="code">
<?php
highlight_file(dirname(__FILE__) . '/action-export.php');
?>
</div>
<?php
include_once 'include/footer.php';
?>