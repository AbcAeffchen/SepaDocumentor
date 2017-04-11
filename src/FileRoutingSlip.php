<?php


namespace AbcAeffchen\SepaDocumenter;


class FileRoutingSlip
{
    /**
     * @param string   $templateFile   The path to the template file or the name of a file in the
     *                                 template directory.
     * @param string[] $data           Contains the data of the SEPA file. The valid keys are:
     *                                 - file_name: the name of the SEPA file
     *                                 - scheme_version: The version of the SEPA file scheme.
     *                                 - payment_type: The type of the payment, e.g. credit transfer.
     *                                 - message_id: The ID of the SEPA message
     *                                 - creation_date_time: The date and time of the file creation. The input format should be "Y-m-d\TH:i:s" todo check this!!
     *                                 - initialising_party: The name of who has initiated the payment
     *                                 - collection_reference:
     *                                 - bic (optional):
     *                                 - iban:
     *                                 - due_date: The date when the payment is executed
     *                                 - number_of_transactions
     *                                 - control_sum: The sum of all transaction
     * @param string[] $options        Some options:
     *                                 - 'date_time_format': You can change this to change the
     *                                 formatting of the creation date. Default is "m.d.Y H:i:s"
     * @return string HTML code
     */
    public static function createHTML($templateFile, array $data, array $options = ['date_time_format' => 'm.d.Y H:i:s'])
    {
        // file_name, scheme_version, payment type, message_id, creation date/time, auftraggeber, sammlerreferenz ??, BIC, IBAN, due date, number of payments, total amount of money.
        // load template
        if(file_exists($templateFile))
            $template = file_get_contents($templateFile);
        elseif(file_exists(__DIR__ . '/templates/' . $templateFile))
            $template = file_get_contents(__DIR__ . '/templates/' . $templateFile);
        else
            throw new \InvalidArgumentException('Template file not found.');

        // remove optional parts (ausführungszeitpunkt, BIC)
        if(isset($data['bic']))
            $template = str_replace(['{{ifdef bic}}', '{{endif bic}}'], '', $template);
        else
            $template = preg_replace('#\{\{ifdef bic\}\}[\s\S]*\{\{endif bic\}\}#', '', $template);

        // todo is there more optional? maybe the due date?

        // prepare replacement:
        $replacement = [];
        foreach($data as $key => $value)
            $replacement['{{' . $key . '}}'] = $value;

        // replace placeholder with data
        return strtr($template, $replacement);
    }

    /**
     * @param string   $templateFile   The path to the template file or the name of a file in the
     *                                 template directory.
     * @param string[] $data           Contains the data of the SEPA file. The valid keys are:
     *                                 - file_name: the name of the SEPA file
     *                                 - scheme_version: The version of the SEPA file scheme.
     *                                 - payment_type: The type of the payment, e.g. credit transfer.
     *                                 - message_id: The ID of the SEPA message
     *                                 - creation_date_time: The date and time of the file creation. The input format should be "Y-m-d\TH:i:s" todo check this!!
     *                                 - initialising_party: The name of who has initiated the payment
     *                                 - collection_reference:
     *                                 - bic (optional):
     *                                 - iban:
     *                                 - due_date: The date when the payment is executed
     *                                 - number_of_transactions
     *                                 - control_sum: The sum of all transaction
     * @param string[] $options        Some options:
     *                                 - 'date_time_format': You can change this to change the
     *                                 formatting of the creation date. Default is "m.d.Y H:i:s"
     * @return string PDF file
     * @throws \MpdfException
     */
    public static function createPDF($templateFile, array $data, array $options = ['date_time_format' => 'm.d.Y H:i:s'])
    {
        // get HTML code
        $fileRoutingSlipHTML = self::createHTML($templateFile, $data, $options);

        $pdf = new \mPDF();
        $pdf->WriteHTML($fileRoutingSlipHTML);

        return $pdf->Output('','S');    // returns the PDF as a string
    }

}