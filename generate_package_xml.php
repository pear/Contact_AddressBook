<?php
require_once('PEAR/PackageFileManager.php');

$pkg = new PEAR_PackageFileManager;

$packagedir = dirname(__FILE__);
$self = basename(__FILE__);

$desc = "Package provide export-import address book\n".
        "mechanism. Contact_AddressBook refers to\n".
        "needed structure, convert the various address\n".
        "book structure format into it, then you can\n".
        "easily store it into file, database or another\n".
        "storage media.\n";

$options = array(
    'doctype'           => 'D:\Net\www\htdocs\PEAR\PEAR\data\PEAR\package.dtd',
    'package'           => 'Contact_AddressBook',
    'license'           => 'BSD License',
    'baseinstalldir'    => '',
    'version'           => '0.1.0dev1',
    'packagedirectory'  => $packagedir,
    'pathtopackagefile' => $packagedir,
    'state'             => 'devel',
    'filelistgenerator' => 'file',
    'notes'             => 'Initial release of Contact_AddressBook',
    'summary'           => 'Address book export-import class',
    'description'       => $desc,
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
$pkg->addDependency('File', '1.0.3', 'ge', 'pkg');
$pkg->addDependency('Net_UserAgent_Detect', '2.0.1', 'ge', 'pkg');

$e = $pkg->writePackageFile();
if (PEAR::isError($e)) {
    echo $e->getMessage();
}
?>
