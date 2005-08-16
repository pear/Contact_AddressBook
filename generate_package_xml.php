<?php
require_once('PEAR/PackageFileManager.php');

$pkg = new PEAR_PackageFileManager;

$packagedir = dirname(__FILE__);
$self = basename(__FILE__);

$desc = "Package provide export-import address book ".
        "mechanism. Contact_AddressBook refers to ".
        "needed structure, convert the various address ".
        "book structure format into it, then you can ".
        "easily to store it into file, database or another ".
        "storage media.";

$options = array(
    'simpleoutput'      => true,
    'doctype'           => 'D:\Net\www\htdocs\PEAR\PEAR\data\PEAR\package.dtd',
    'package'           => 'Contact_AddressBook',
    'license'           => 'BSD License',
    'baseinstalldir'    => '',
    'version'           => '0.4.0alpha1',
    'packagedirectory'  => $packagedir,
    'pathtopackagefile' => $packagedir,
    'state'             => 'alpha',
    'filelistgenerator' => 'file',
    'notes'             => "* Fixed bug in Contact_AddressBook_Builder_Eudora::build(), wrong way on \"notes\" tag\n" .
                           "* Fixed bug causes by File_CSV with force config (thanks to Andy Crain <crain@fuse.net>)\n" .
                           "* Fixed bug in Contact_AddressBook_Converter::prepare(), Contact_AddressBook_Converter::\$isPrepared never assigned (thanks to Andy Crain <crain@fuse.net>)\n" .
                           "* Reconstructed file and directory structure\n" .
                           "* Renamed Contact_AddressBook_Builder_eudora to Contact_AddressBook_Eudora\n" .
                           "* Renamed Contact_AddressBook_Builder_csv to Contact_AddressBook_CSV\n" .
                           "* Renamed Contact_AddressBook_Parser_eudora to Contact_AddressBook_Parser_Eudora\n" .
                           "* Renamed Contact_AddressBook_Parser_CSV to Contact_AddressBook_Parser_csv\n" .
                           "* Renamed several files to pretty names\n" .
                           "* Moved all CSV address book related files under PEAR data dir \"Contact_AddressBook/CSV\"\n" .
                           "* Renamed format call names, see added logs\n" .
                           "* Changed the examples\n" .
                           "* Changed the release state to alpha\n" .
                           "- Removed \"definitions\" dir, now all definition files are under \"Defs\" dir\n" .
                           "- Removed Contact_AddressBook::isExportable(), each supported formats are exportable\n" .
                           "- Removed Contact_AddressBook::isImportable(), each supported formats are importable\n" .
                           "- Removed Contact_AddressBook_Builder_csv_netscape\n" .
                           "- Removed Contact_AddressBook_Builder_csv_outlook_express\n" .
                           "+ Added new method Contact_AddressBook::isSupported(), find out the whether the format is supported or not\n" .
                           "+ Added new class Contact_AddressBook_CSV for working with CSV\n" .
                           "+ Added new class Contact_AddressBook_Converter_CSV, for CSV converting\n" .
                           "+ Added several CSV related files in PEAR data dir \"Contact_AddressBook/CSV\"\n" .
                           "+ Added new support for KMail (KDE Mailer), Ms Outlook, Palm Pilot and Yahoo!\n" .
                           "+ Added several call names (case-insensitive),
csv_wab => Ms Windows Address Book CSV
csv_outlookexpress  => Ms Windows Outlook Express CSV (equal with csv_wab)
csv_outlook => Ms Outlook CSV
csv_mozilla => Mozilla Mailer CSV
csv_thunderbird => Mozilla Thunderbird CSV (equal with csv_mozilla and csv_netscape)
csv_netscape => Netscape Mailer CSV (equal with csv_mozilla and csv_thunderbird)
csv_yahoo => Yahoo! CSV
csv_palm => Palm CSV.
eudora => Eudora address book\n" .
                           "+ Upgraded File package dependency to version  >= 1.2.1",
    'summary'           => 'Address book export-import class',
    'description'       => $desc,
    'dir_roles'         => array(
        'docs'      => 'doc',
        'data'      => 'data'
    ),
    'ignore'            => array(
        'package.xml',
        '*.tgz',
        $self
    )
);

$e = $pkg->setOptions($options);
if (PEAR::isError($e)) {
    echo $e->getMessage();
    die;
}

// hack until they get their shit in line with docroot role
$pkg->addRole('tpl', 'php');
$pkg->addRole('png', 'php');
$pkg->addRole('gif', 'php');
$pkg->addRole('jpg', 'php');
$pkg->addRole('css', 'php');
$pkg->addRole('js', 'php');
$pkg->addRole('ini', 'php');
$pkg->addRole('inc', 'php');
$pkg->addRole('afm', 'php');
$pkg->addRole('pkg', 'doc');
$pkg->addRole('cls', 'doc');
$pkg->addRole('proc', 'doc');
$pkg->addRole('txt', 'doc');
$pkg->addRole('sh', 'script');

$pkg->addMaintainer('firman', 'lead', 'Firman Wandayandi', 'firman@php.net');

$pkg->addDependency('PEAR', '1.2.1', 'ge', 'pkg');
$pkg->addDependency('File', '1.2.1', 'ge', 'pkg');
$pkg->addDependency('Net_UserAgent_Detect', '2.0.1', 'ge', 'pkg');

$e = $pkg->addGlobalReplacement('package-info', '@package_version@', 'version');

$e = $pkg->writePackageFile();
if (PEAR::isError($e)) {
    echo $e->getMessage();
}
?>
