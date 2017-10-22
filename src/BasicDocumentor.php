<?php
/**
 * SepaDocumentor
 *
 * @license   GNU LGPL v3.0 - For details have a look at the LICENSE file
 * @copyright ©2017 Alexander Schickedanz
 * @link      https://github.com/AbcAeffchen/SepaDocumentor
 *
 * @author    Alexander Schickedanz <abcaeffchen@gmail.com>
 */

namespace AbcAeffchen\SepaDocumentor;

class BasicDocumentor
{
    const PLACEHOLDER_REGEX = '#\{\{[a-zA-Z0-9_-]+\}\}#';
    const COMMAND_REGEX = '#\{\{[a-zA-Z0-9_-]+!\}\}#';

    /**
     * Tries to load the content of a template file in path. If this is not possible, an
     * exception is thrown.
     *
     * @param string $path
     * @return string
     * @throws \InvalidArgumentException
     */
    protected static function getTemplate($path)
    {
        if(file_exists($path))
            $template = file_get_contents($path);
        elseif(file_exists(__DIR__ . '/templates/' . $path))
            $template = file_get_contents(__DIR__ . '/templates/' . $path);
        else
            throw new \InvalidArgumentException('Template file not found.');

        return $template;
    }

    /**
     * Searches the template for conditionals that look like `{{ifdef [a-zA-Z0-9_-]+}}` and
     * `{{ifndef [a-zA-Z0-9_-]+}}` and stores the corresponding keys in the fields `set` respective `not_set`
     * of the returned array.
     *
     * @param string $template Content of the template, not the path to a file.
     * @return string[][] An array of the form ['set' => [...], 'not_set' => [...]]
     */
    protected static function getTemplateConditionals($template)
    {
        $conditionals = ['set' => [], 'not_set' => []];

        preg_match_all('#\{\{ifdef ([a-zA-Z0-9_-]+)\}\}#', $template, $matches);
        $conditionals['set'] = $matches[1];

        preg_match_all('#\{\{ifndef ([a-zA-Z0-9_-]+)\}\}#', $template, $matches);
        $conditionals['not_set'] = $matches[1];

        return $conditionals;
    }

    /**
     * Finds all conditionals `{{ifdef [a-zA-Z0-9_-]}}` and `{{ifndef [a-zA-Z0-9_-]}}` and
     * checks if the keys are set (or not set) in the data array.
     *
     * @param string $template
     * @param array $data
     * @return string
     */
    protected static function evalTemplateConditionals($template, array $data, array $conditionals = null)
    {
        // find all conditionals
        if(!isset($conditionals))
            $conditionals = self::getTemplateConditionals($template);

        if(empty($conditionals))
            return $template;

        // check them all
        foreach($conditionals['set'] as $conditional)
        {
            if(isset($data[$conditional]))
                $template = str_replace(['{{ifdef '. $conditional . '}}', '{{endif ' . $conditional . '}}'], '', $template);
            else
                $template = preg_replace('#\{\{ifdef ' . $conditional . '\}\}[\s\S]*\{\{endif ' . $conditional . '\}\}#', '', $template);
        }

        foreach($conditionals['not_set'] as $conditional)
        {
            if(!isset($data[$conditional]))
                $template = str_replace(['{{ifndef '. $conditional . '}}', '{{endif ' . $conditional . '}}'], '', $template);
            else
                $template = preg_replace('#\{\{ifndef ' . $conditional . '\}\}[\s\S]*\{\{endif ' . $conditional . '\}\}#', '', $template);
        }

        return $template;
    }

    /**
     * Inserts data into the placeholders in $template and returns the result.
     *
     * @param string $template
     * @param string[] $data
     * @return string
     */
    protected static function textInsertion($template, array $data)
    {
        $replacements = [];
        foreach($data as $key => $value)
        {
            $replacements['{{' . $key . '}}'] = $value;
        }

        return strtr($template, $replacements);
    }

    /**
     * Creates a PDF file from $html using mPDF.
     *
     * @param string $html
     * @return string
     * @throws \Mpdf\MpdfException
     */
    protected static function mPDFWrapper($html)
    {
        $pdf = new \Mpdf\Mpdf();
        $pdf->WriteHTML($html);

        return $pdf->Output('','S');    // returns the PDF as a string
    }

    /**
     * Removes unused placeholders from the $text and returns the result.
     *
     * @param string $text
     * @return mixed
     */
    protected static function removeUnusedPlaceholders($text)
    {
        $text = preg_replace(self::PLACEHOLDER_REGEX, '', $text);
        return preg_replace(self::COMMAND_REGEX, '', $text);
    }
}