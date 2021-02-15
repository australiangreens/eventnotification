# eventnotification

This extension allows for the sending of an email notification to a specified email address if an event is created within the domain or by virtue of a specified financial type is used with an event.

This also sets `is_active` field for events to not active by default for users in the NSW Domain (domain id 8) and whom doesn't have administer CiviCRM permission

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.0+
* CiviCRM 5.25

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl eventnotification@https://github.com/australiangreens/eventnotification/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/eventnotification.git
cv en eventnotification
```

## Usage

To configure you need to go to enable the extension then go to `civicrm/admin/setting/eventnotification` to configure the extension's settings.
