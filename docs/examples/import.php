<?php
include_once 'include/head.php';
include_once 'include/header.php';
include_once 'include/simulations.php';
?>
<script language="javascript" type="text/javascript">
function checkImportForm()
{
	var form = document.ImportForm;
	
	if (form.mode[0].checked) {
		for (var i = 0; i < form.simId.length; i++) {
			if (form.simId[i].checked) {
				return true;
			}
		}
		alert('Please pick a file first');
		return false;
	} else {	
		if (form.file.value == '') {
			alert('Please pick a file to import');
			return false;
		}
		if (form.format.value == '') {
			alert('Please select the format of file first');
			return false;
		}
	}
	return true;
}
function hideElement(id)
{
	var obj = document.getElementById(id);
	if (obj != null) {
		obj.style.display = 'none';
	}
}
function showElement(id)
{
	var obj = document.getElementById(id);
	if (obj != null) {
		obj.style.display = '';
	}
}
</script>
<div class="nav"><a href="index.php">Home</a></div>
<h2>Options</h2>
<dl>
<dt><b>Here the options to import the address book:</b></dt>
<dt>1. <i>defs_dir</i>, Definition directory (default is PEAR data directory)</dt>
<dt>2. <i>language</i>, Address book language in code (required only if the format is the selectable fields such as WAB, default is "en" english)</dt>
</dl>
<h2>In Action</h2>
<form name="ImportForm" method="post" action="action-import.php" enctype="multipart/form-data" target="result" onSubmit="javascript: return checkImportForm();">
<b>What do you want to do?</b><br />
<input name="mode" type="radio" value="sim" id="mode_sim" onClick="if (this.checked) { showElement('sim'); hideElement('own'); }" checked /><label for="mode_sim">Use simulation file</label>
<input name="mode" type="radio" value="own" id="mode_own" onClick="if (this.checked) { showElement('own'); hideElement('sim'); }" /><label for="mode_own">Use my own file</label><br />
<div id="sim">
<b>Pick a file:</b><br />
<?php
foreach ($GLOBALS['_Contact_AddressBook_simulations'] as $key => $value) {
?>
<input type="radio" name="simId" value="<?php print $key; ?>" id="<?php print $value['format'] . '_' . $key; ?>" /><label for="<?php print $value['format'] . '_' . $key; ?>"><?php print '<b>' . $value['file'] . '</b>, ' . $value['desc']; ?></label><br />
<?php
}
?>
</div>
<div id="own" style="display:none;">
<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
<b>Pick a file:</b><br />
<input name="file" type="file" size="50" />
<br />
<b>Select the format of file:</b><br /> 
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
</div>
<input name="submit" type="submit" id="submit" value="Import" />
</form>
<br />
<b>Result:</b>
<iframe name="result" style="width:100%; height: 400px" frameborder="1"></iframe>
<h2>Source</h2>
<div class="code">
<?php
highlight_file(dirname(__FILE__) . '/action-import.php');
?>
</div>
<?php
include_once 'include/footer.php';
?>
