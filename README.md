SepaDocumenter - A Sephpa module to generate documentation files (PDF) for SEPA xml files
===============
[![Build Status](https://travis-ci.org/AbcAeffchen/SepaDocumenter.svg?branch=master)](https://travis-ci.org/AbcAeffchen/SepaDocumenter)

## General
**SepaDocumenter** is a module for [Sephpa](https://github.com/AbcAeffchen/Sephpa)
to create file routing slips and control lists as PDF file(s) while generating SEPA xml files.

## Requirements
SepaDocumenter requires PHP >= 5.6 and mPDF 6.1.0. It works with Sephpa >= 2.0 but you can also
use it with other SEPA projects.

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
There is only one function for each type of document:

- `FileRoutingSlip::createPDF($templateFile, $data, $options)` for file routing slips
 - `$templateFile` is the file name of a default template or a path to a custom template. A template
 can use any variable name you like written as `{{var_name}}`.
- ``

[add documentation here]

## License
Licensed under the LGPL v3.0 License.
