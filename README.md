# SilverStripe Pure Modal module

[![Build Status](https://travis-ci.com/lekoala/silverstripe-pure-modal.svg?branch=master)](https://travis-ci.com/lekoala/silverstripe-pure-modal/)
[![scrutinizer](https://scrutinizer-ci.com/g/lekoala/silverstripe-pure-modal/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lekoala/silverstripe-pure-modal/)
[![Code coverage](https://codecov.io/gh/lekoala/silverstripe-pure-modal/branch/master/graph/badge.svg)](https://codecov.io/gh/lekoala/silverstripe-pure-modal)

## Intro

Dead simple modals for SilverStripe (works in the admin too!)

## Sample usage

Simply pass the content to display in the modal. The modal will be accessible through a button in the fieldset

You can also set an iframe to be displayed within the modal in case you want to display a form for example.

```php
$myHtmlContent = "<p>Some content here</p>";
$ImportStuff = new PureModal('ImportStuff', 'Import Stuff', $myHtmlContent);
$fields->addFieldToTab('Root.Stuff', $ImportStuff);
$ImportStuff->setIframeAction('import_stuff');
$ImportStuff->setIframeTop(true);
```

And here is a sample `import_stuff` method

```php
public function import_stuff(HTTPRequest $req)
{
    $Stuff = $this->getRequestedRecord();
    $fields = new FieldList([
        new FileField('File'),
        new HiddenField('StuffID', null, $Stuff->ID),
    ]);
    $actions = new FieldList([
        new FormAction('doUpload', 'Upload')
    ]);
    $form = new Form(Controller::curr(), 'MyForm', $fields, $actions);

    return PureModal::renderDialog($this, ['Form' => $form]);
}
```

## Modal action

This feature require my [cms-actions](https://github.com/lekoala/silverstripe-cms-actions) module.

```php
    public function getCMSActions()
    {
        $actions = parent::getCMSActions();
        $doDeny = new PureModalAction("doDeny", "Deny");
        $predefinedText = <<<TEXT
        Dear Customer,

        Your request has been denied.

        Best regards,
        TEXT;
        $iframeFields = new FieldList([
            new DropdownField("SelectReason", "Select reason"),
            new TextareaField("EnterText", "Enter custom text", $predefinedText),
        ]);
        $doDeny->setFieldList($iframeFields);
        $doDeny->setShouldRefresh(true);
        $actions->push($doDeny);
    }
```

It creates a button that opens a modal with a set of fields. These fields
are submitted alongside the form.

```php
    public function doDeny($data)
    {
        $this->DeniedReason = $data['EnterText'];
        $this->Status = "denied";
        $this->write();
        return 'Denied';
    }
```

## Compatibility

Tested with 4.x

## Maintainer

LeKoala - thomas@lekoala.be
