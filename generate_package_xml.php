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
    'version'           => '0.5.0',
    'packagedirectory'  => $packagedir,
    'pathtopackagefile' => $packagedir,
    'state'             => 'alpha',
    'filelistgenerator' => 'file',
    'notes'             => "* Added new support for Gmail address book",
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
