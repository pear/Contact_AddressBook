================================================================================
                             Contact_AddressBook
           Copyright (C) 2004 Firman Wandayandi. All rights reserved.
================================================================================

$Id$


About This Document

  This document refers to Contact_AddressBook version 0.1.0dev.


Author

  Firman Wandayandi <firman@php.net>


License

  Contact_AddressBook is free software that is licensed under the BSD License.
  http://www.opensource.org/licenses/bsd-license.php


Introduction

  Contact_AddressBook is a PEAR package provide import-export address book
  mechanism. Contact_AddressBook refers to needed structure, convert the
  various address book structure format into it, then you can easily store it
  into file, database or another storage media.


Supported Address Book

  Contact_AddressBook supports following address book format.

  +--------------------------------------------------------+--------+--------+
  | Format                                                 | Import | Export |
  +--------------------------------------------------------+--------+--------+
  | CVS Outlook Express (any language)                     |  Yes   |  Yes   |
  | CVS Netscape/Mozilla Mailer/Mozilla Thunderbird        |  Yes   |  Yes   |
  | Eudora                                                 |  Yes   |  Yes   |
  +--------------------------------------------------------+--------+--------+


How Contact_AddressBook Working?

  Import Procedure

  +--------------------- --+   +--------------------+   +--------------------+
  | Address Book Structure |==>| Gateaway Structure |==>| Internal Structure |
  +---------------------- -+   +--------------------+   +--------------------+

  Export Procedure

  +--------------------+   +--------------------+   +------------------------+
  | Internal Structure |==>| Gateaway Structure |==>| Address Book Structure |
  +--------------------+   +--------------------+   +------------------------+

  Explanation

  All address book structure will be convert into this structure,
  for example:

    Import

      1. Outlook Express and Netscape/Mozilla Mailer/Mozilla Thunderbird

         Contact_AddressBook will be parse "First Name" as key "0", then
         convert it into key "firstname".

         +------------+   +---+   +-----------+
         | First Name |==>| 0 |==>| firstname |
         +------------+   +---+   +-----------+

      2. Eudora

         Contact_AddressBook will be parse "First Name" as key "first",
         then convert it into key "firstname".

         +------------+   +-------+   +-----------+
         | First Name |==>| first |==>| firstname |
         +------------+   +-------+   +-----------+

    Export

      1. Outlook Express and Netscape/Mozilla Mailer/Mozilla Thunderbird

         Set the "firstname" key, the converter will be convert it as
         key "0", builder write it as "First Name".

         +-----------+   +---+   +------------+
         | firstname |==>| 0 |==>| First Name |
         +-----------+   +---+   +------------+

      2. Eudora

         Set the "firstname" key, the converter will be convert it as
         key "first", builder write it as "First Name".

         +-----------+   +-------+   +------------+
         | firstname |==>| first |==>| First Name |
         +-----------+   +-------+   +------------+


What is Internal Structure?

  Internal structure is your structure, what's the structure that you want?


What is Gateaway Structure?

  Gateaway structure is Contact_AddressBook internal structure.


What is Address Book Structure?

  It is the specified address book structure e.g CSV Outlook Express or
  Netscape.

Default Internal Structure

  +-------------------+-----------------------------+
  | Key               | Description                 |
  +-------------------+-----------------------------+
  | department        | Job Department              |
  | displayname       | Display Name                |
  | email             | Primary Email Address       |
  | firstname         | First Name                  |
  | home_address      | Home Street Address         |
  | home_city         | Home City                   |
  | home_country      | Home Country                |
  | home_fax          | Home Fax                    |
  | home_phone        | Home Phone                  |
  | home_state        | Home State                  |
  | home_zipcode      | Home ZipCode                |
  | homepage          | Personal Web Page           |
  | job_title         | Job Title                   |
  | lastname          | Last Name                   |
  | mobile            | Mobile phone                |
  | middlename        | Middle Name                 |
  | title             | Title                       |
  | nickname          | Nickname                    |
  | notes             | Notes                       |
  | organization      | Organization                |
  | pager             | Pager                       |
  | work_address      | Work Address                |
  | work_city         | Work City                   |
  | work_country      | Work Country                |
  | work_fax          | Work Fax                    |
  | work_homepage     | Work Web Page               |
  | work_phone        | Work Phone                  |
  | work_state        | Work State                  |
  | work_zipcode      | Work ZipCode                |
  +-------------------+-----------------------------+


Customize Internal Structure

  You can customize internal structure as necessary, but before do that, it's
  stongly recommended refers to gateaway structure documentation, to make sure
  it's working properly. You can find gateaway structure at doc directory
  in subfolder "gateaway", there are currently 3 files gateaway structure
  documentation, a listed below.

    [1] csv_netscape.txt            CVS Netscape/Mozilla Mailer/
                                    Mozilla Thunderbird address book gateaway
                                    structure documentation.

    [2] csv_outlook_express.txt     CSV Ms Outlook Express Address book gateaway
                                    structure documentation.

    [3] eudora.txt                  Eudora address book gateaway structure
                                    documentation.

  If you already read gateaway structure documentation files, now you ready for
  next step, that is understand about the definition files. Definition file is
  text plain format, and it's same as php.ini but the extension is "def", it's
  contains some values that are gateaway structure key mapping to internal
  structure e.g in csv_outlook_express.def 0 = firstname, is it like a diagram
  flow above?

  You can find that files at PEAR data directory subfolder
  Contact_AddressBook/definitions. If you already known with gateaway structure
  and definition file, now you ready to customizing the internal structure.

  To customize the internal structure, there are 2 method for do that, first
  modified a definition file or second pointed the definition file to your
  definition file. For a second you should be do is set the option "def_dir"
  to your definition file directory, be notes the name of file must be same
  with classname e.g csv_outlook_express.def, csv_netscape.def and eudora.def.


Ok finish now, sorry for my poor english. If you have any questions or something
related with this package, just send an email to firman@php.net.

Good Luck.
