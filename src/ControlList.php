<?php
/**
 * SepaDocumenter
 *
 * @license   GNU LGPL v3.0 - For details have a look at the LICENSE file
 * @copyright ©2017 Alexander Schickedanz
 * @link      https://github.com/AbcAeffchen/SepaDocumenter
 *
 * @author    Alexander Schickedanz <abcaeffchen@gmail.com>
 */

namespace AbcAeffchen\SepaDocumenter;


class ControlList extends BasicDocumenter
{
    const TRANSACTION_BLOCK_REGEX = '#\{\{TRANSACTION!\}\}([\s\S]+)?\{\{/TRANSACTION!\}\}#';
    const TRANSACTION_NEXT = '{{NEXT_TRANSACTION!}}';

    /**
     * @param string     $templateFile  File name of a standard template file or a path to a custom template file
     * @param string[]   $data          The data that will be inserted into the template. The keys
     *                                  need to match the keys in the template. There are only `bic` is a reserved keyword.
     * @param string[][] $transactions  An array of arrays. Each of the arrays contains key value
     *                                  pairs just as it is with $data. The only reserved keyword is again `bic`
     * @return string
     */
    public static function createText($templateFile, array $data, array $transactions)
    {
        $template = self::getTemplate($templateFile);

        // get transaction template
        if(preg_match(self::TRANSACTION_BLOCK_REGEX, $template, $matches) !== 1)
            throw new \InvalidArgumentException('Template does not contain a transaction block.');

        $transactionTemplate = $matches[1] . self::TRANSACTION_NEXT;

        // replace transaction template by next transaction marker
        $template = preg_replace(self::TRANSACTION_BLOCK_REGEX, self::TRANSACTION_NEXT, $template);

        // remove bic if not provided
        $template = self::evalTemplateConditionals($template, $data);

        $output = self::textInsertion($template, $data);


        $transactionConditionals = self::getTemplateConditionals($transactionTemplate);
        // insert transactions
        foreach($transactions as $transaction)
        {
            // evaluate template conditionals for the current transaction
            $transactionCode = self::evalTemplateConditionals($transactionTemplate, $transaction, $transactionConditionals);

            // insert the current transaction data
            $transactionCode = self::textInsertion($transactionCode, $transaction);

            // insert transaction element into the document. the transactionCode contains placeholder
            // for the next transaction. So we don't need to append this.
            $output = str_replace(self::TRANSACTION_NEXT, $transactionCode, $output);
        }

        // remove the unused placeholders including the final next transaction placeholder.
        return self::removeUnusedPlaceholders($output);
    }

    /**
     * Calls ControlList::createText and uses the result as source for a PDF file.
     *
     * @param string   $templateFile   The path to the template file or the name of a file in the
     *                                 template directory. This template should contain HTML code
     *                                 and can contain some special commands used by mPDF.
     * @param string[] $data           See ControlList::createText() for Details
     * @param string[][] $transactions See ControlList::createText() for details.
     * @return string                  The PDF file content. Write this string into a PDF file.
     * @throws \MpdfException
     */
    public static function createPDF($templateFile, array $data, array $transactions)
    {
        $controlListHTML = self::createText($templateFile, $data, $transactions);

        return self::mPDFWrapper($controlListHTML);
    }
}