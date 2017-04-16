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

class FileRoutingSlip extends BasicDocumenter
{
    /**
     * Replaces every `{{key}}` by `value` in the given template and returns the resulting text.
     *
     * @param string   $templateFile Name of default template file or path to custom template file.
     * @param string[] $data         A key - value array containing the keys used in the template. The only
     *                               depend on the template.
     * @return string The resulting text.
     */
    public static function createText($templateFile, array $data)
    {
        // load template
        $template = self::getTemplate($templateFile);

        // remove optional parts
        $output = self::evalTemplateConditionals($template, $data);

        // insert text and return result
        $output = self::textInsertion($output, $data);

        // remove unused placeholders
        return self::removeUnusedPlaceholders($output);
    }

    /**
     * Calls FileRoutingSlip::createText and uses the result as source for a PDF file.
     *
     * @param string   $templateFile   The path to the template file or the name of a file in the
     *                                 template directory. This template should contain HTML code
     *                                 and can contain some special commands used by mPDF.
     * @param string[] $data           See FileRoutingSlip::createText() for details
     * @return string PDF file         The PDF file content. Write this string into a PDF file.
     * @throws \MpdfException
     */
    public static function createPDF($templateFile, array $data)
    {
        // get HTML code
        $fileRoutingSlipHTML = self::createText($templateFile, $data);

        return self::mPDFWrapper($fileRoutingSlipHTML);
    }

}