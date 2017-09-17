SepaDocumenter - A Sephpa module to generate documentation files (PDF) for SEPA xml files
===============

[![Build Status](https://travis-ci.org/AbcAeffchen/SepaDocumenter.svg?branch=master)](https://travis-ci.org/AbcAeffchen/SepaDocumenter)
[![Latest Stable Version](https://poser.pugx.org/abcaeffchen/sepa-documenter/v/stable)](https://packagist.org/packages/abcaeffchen/sepa-documenter)
[![Total Downloads](https://poser.pugx.org/abcaeffchen/sepa-documenter/downloads)](https://packagist.org/packages/abcaeffchen/sepa-documenter)
[![License](https://poser.pugx.org/abcaeffchen/sepa-documenter/license)](https://packagist.org/packages/abcaeffchen/sepa-documenter)
[![Gitter](https://badges.gitter.im/AbcAeffchen/SepaDocumenter.svg)](https://gitter.im/AbcAeffchen/SepaDocumenter?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

## General
**SepaDocumenter** is a module for [Sephpa](https://github.com/AbcAeffchen/Sephpa)
to create file routing slips and control lists as PDF file(s) while generating SEPA xml files.

## Requirements
SepaDocumenter works with PHP >= 5.6, 7.0, 7.1 and HHVM and requires mPDF 6.1.0. It is an optional module
of Sephpa >= 2.0 but you can also use it with other SEPA projects as well.

## Installation

### Composer
Just add

```json
{
    "require": {
        "abcaeffchen/sepa-documenter": "~1.0.0"
    }
}
```

to your `composer.json` and include the Composer autoloader to your script. If you want to use
SepaDocumenter with Sephpa, you also need to add the line `"abcaeffchen/sephpa": "~2.0.0"`

### Direct download
You can download it here on GitHub. You need to make it available in all files, where Sephpa is
used. You also have to download [mPDF](https://gitub.com/mpdf/mpdf) and make it available to SepaDocumenter.
It integrates with Sephpa, so you don't have to do anything else.

## What do the PDF files contain?
There are two files that can be generated: File Routing Slip and Control Lists.
 
### File Routing Slip
The File Routing Slip contains a summary of the SEPA file. This contains the user name of the 
creator and ID of the file,
the number of transactions and the sum of the transferred money and some more information.

### Control List
This contains a detailed list of all transactions that are contained in the SEPA file.

## Personalize the PDF files
You can modify the templates in `src/templates` to change the look of the PDF files.
This files also contain the localizations. There are a german and english template included but
there is a good chance that the english version is a very bad translations, since I have no
clue about finance speak. So I would be happy if someone will check this and send me a correct 
version :)

## How to use

### With Sephpa
Just use Sephpa as usual. When you call download or store, set the `addDocumentation` flag.
If you want to download a file, you have to download a zip file that contains the xml file(s) and
the documentation files as PDF files.

### With other SEPA libraries

#### Overview
There are four functions you can use:
- `FileRoutingSlip::createText($template, $data)`: Inserts the $data array into the template.
`$template` can be a path to a template or the name of a default template and it can have any format.
- `FileRoutingSlip::createPDF($template, $data)`: The same as `createText()`, but the template
needs to be in HTML format.
- `ControlList::createText($template, $data, $transactions)`: Inserts the $data array into the template and repeats
the transaction block as often as needed, each filled with the values from one of the arrays in `$transactions`
`$template` can be a path to a template or the name of a default template and it can have any format.
- `ControlList::createPDF($template, $data, $transactions)`: The same as `createText()`, but the template
needs to be in HTML format.

#### Templates
Templates can have any format. You can use
- placeholders of the form `{{KEYWORD}}`, where `KEYWORD` is a string mating `[a-zA-Z0-9_-]+`.
- conditionals
 - `{{ifdef KEYWORD}} BODY {{endif KEYWORD}}`: `BODY` is only printed if `KEYWORD` is set. `BODY`
 can contain placeholders and other conditionals as long as the nested conditionals use other keywords.
 - `{{ifndef KEYWORD}} BODY {{endif KEYWORD}}`: `BODY` is only printed if `KEYWORD` is *not* set.
 `BODY` can also contain placehodlers and other conditionals as long as the nested conditionals
 use other keywords.
- `{{TRANSACTION!}} BLOCK {{/TRANSACTION!}}`: This is a spacial command block only allowed for
control lists. `BLOCK` can contain any other placeholders or conditionals. This block will be
repeated and interpreted for each transaction.
Every unused placeholder will be removed from the output.

#### Inputs
- `$data` is a key => value array, where keys are the placeholders without the braces.
- `$transactions` is a array of key => value arrays, one for each transaction.

Not every placeholder needs to be present in the input. Keys without a corresponding placeholder
will be ignored.

## License
Licensed under the LGPL v3.0 License.
