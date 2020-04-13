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

require __DIR__ . '/../vendor/autoload.php';

use Mpdf\MpdfException;
use PHPUnit\Framework\TestCase;
use AbcAeffchen\SepaDocumentor\FileRoutingSlip;

class TestFileRoutingSlip extends TestCase
{

    protected $dataWithBIC;
    protected $dataWithoutBIC;
    protected $expectedOutputWithBIC;
    protected $expectedOutputWithoutBIC;

    protected function setUp() : void
    {
        $this->dataWithBIC = ['file_name'              => 'fileName',
                              'scheme_version'         => 'pain.001.002.03',
                              'payment_type'           => 'credit transfer',
                              'message_id'             => 'some message id',
                              'creation_date_time'     => '13.01.2017',
                              'initialising_party'     => 'Some Guy',
                              'collection_reference'   => 'some collection id',
                              'bic'                    => 'some bic',
                              'iban'                   => 'some iban',
                              'due_date'               => 'some day',
                              'number_of_transactions' => '5',
                              'control_sum'            => '123,45 €',
                              'current_date'           => '11.04.2017'];

        $this->dataWithoutBIC = ['file_name'              => 'fileName',
                                 'scheme_version'         => 'pain.001.002.03',
                                 'payment_type'           => 'credit transfer',
                                 'message_id'             => 'some message id',
                                 'creation_date_time'     => '13.01.2017',
                                 'initialising_party'     => 'Some Guy',
                                 'collection_reference'   => 'some collection id',
                                 'iban'                   => 'some iban',
                                 'due_date'               => 'some day',
                                 'number_of_transactions' => '5',
                                 'control_sum'            => '123,45 €',
                                 'current_date'           => '11.04.2017'];

        $this->expectedOutputWithBIC    = <<<'HTML'
<h2 style="text-align: center;">Sephpa Begleitzettel</h2>

<table style="margin-top: 2cm; border: 0; width: 100%;">
    <tr>
        <td>Dateiname</td>
        <td style="text-align: right;">fileName</td>
    </tr>
    <tr>
        <td>Schemaversion</td>
        <td style="text-align: right;">pain.001.002.03</td>
    </tr>
    <tr>
        <td>Zahlungsart</td>
        <td style="text-align: right;">credit transfer</td>
    </tr>
    <tr>
        <td>Nachrichten-ID</td>
        <td style="text-align: right;">some message id</td>
    </tr>
    <tr>
        <td>Erstellungsdatum und -zeit</td>
        <td style="text-align: right;">13.01.2017</td>
    </tr>
    <tr>
        <td>Auftraggeber</td>
        <td style="text-align: right;">Some Guy</td>
    </tr>
    <tr>
        <td>Sammlerreferenz</td>
        <td style="text-align: right;">some collection id</td>
    </tr>
    
    <tr>
        <td>BIC</td>
        <td style="text-align: right;">some bic</td>
    </tr>
    
    <tr>
        <td>IBAN</td>
        <td style="text-align: right;">some iban</td>
    </tr>
    <tr>
        <td>Ausführungstermin</td>
        <td style="text-align: right;">some day</td>
    </tr>
    <tr>
        <td>Anzahl der Zahlungssätze</td>
        <td style="text-align: right;">5</td>
    </tr>
    <tr>
        <td>Summe der Beträge</td>
        <td style="text-align: right;">123,45 €</td>
    </tr>
</table>

<table style="width: 100%; margin-top: 2cm; border: 0 solid;">
    <tr>
        <td></td>
        <td style="width: 25%; border-bottom: 1px solid; text-align: right;"></td>
        <td> 11.04.2017</td>
        <td style="width: 35%; border-bottom: 1px solid;"></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">Ort</td>
        <td></td>
        <td style="text-align: center;">Unterschrift</td>
        <td></td>
    </tr>
</table>

HTML;
        $this->expectedOutputWithoutBIC = <<<'HTML'
<h2 style="text-align: center;">Sephpa Begleitzettel</h2>

<table style="margin-top: 2cm; border: 0; width: 100%;">
    <tr>
        <td>Dateiname</td>
        <td style="text-align: right;">fileName</td>
    </tr>
    <tr>
        <td>Schemaversion</td>
        <td style="text-align: right;">pain.001.002.03</td>
    </tr>
    <tr>
        <td>Zahlungsart</td>
        <td style="text-align: right;">credit transfer</td>
    </tr>
    <tr>
        <td>Nachrichten-ID</td>
        <td style="text-align: right;">some message id</td>
    </tr>
    <tr>
        <td>Erstellungsdatum und -zeit</td>
        <td style="text-align: right;">13.01.2017</td>
    </tr>
    <tr>
        <td>Auftraggeber</td>
        <td style="text-align: right;">Some Guy</td>
    </tr>
    <tr>
        <td>Sammlerreferenz</td>
        <td style="text-align: right;">some collection id</td>
    </tr>
    
    <tr>
        <td>IBAN</td>
        <td style="text-align: right;">some iban</td>
    </tr>
    <tr>
        <td>Ausführungstermin</td>
        <td style="text-align: right;">some day</td>
    </tr>
    <tr>
        <td>Anzahl der Zahlungssätze</td>
        <td style="text-align: right;">5</td>
    </tr>
    <tr>
        <td>Summe der Beträge</td>
        <td style="text-align: right;">123,45 €</td>
    </tr>
</table>

<table style="width: 100%; margin-top: 2cm; border: 0 solid;">
    <tr>
        <td></td>
        <td style="width: 25%; border-bottom: 1px solid; text-align: right;"></td>
        <td> 11.04.2017</td>
        <td style="width: 35%; border-bottom: 1px solid;"></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">Ort</td>
        <td></td>
        <td style="text-align: center;">Unterschrift</td>
        <td></td>
    </tr>
</table>

HTML;
    }

    public function testFileRoutingSlipText()
    {
        $generatedHTML = FileRoutingSlip::createText('file_routing_slip_german.tpl',
                                                     $this->dataWithBIC);

        self::assertSame($this->expectedOutputWithBIC, $generatedHTML);
        self::assertNotSame($this->expectedOutputWithoutBIC, $generatedHTML);
    }

    public function testFileRoutingSlipWithoutBICText()
    {
        $generatedHTML = FileRoutingSlip::createText('file_routing_slip_german.tpl',
                                                     $this->dataWithoutBIC);

        self::assertSame($this->expectedOutputWithoutBIC, $generatedHTML);
        self::assertNotSame($this->expectedOutputWithBIC, $generatedHTML);
    }

    /**
     * @throws MpdfException
     */
    public function testFileRoutingSlipPDF()
    {
        // create PDF with BIC
        $file_name = __DIR__ . '/file_routing_slip_with_bic.pdf';
        $html = FileRoutingSlip::createPDF('file_routing_slip_german.tpl',
                                            $this->dataWithBIC);

        $file = fopen($file_name, 'wb');
        fwrite($file, $html);
        fclose($file);

        self::assertTrue(file_exists($file_name) && filesize($file_name) > 0);

        // create PDF without BIC
        $file_name = __DIR__ . '/file_routing_slip_without_bic.pdf';
        $html = FileRoutingSlip::createPDF('file_routing_slip_german.tpl',
                                            $this->dataWithoutBIC);

        $file = fopen($file_name, 'wb');
        fwrite($file, $html);
        fclose($file);

        self::assertTrue(file_exists($file_name) && filesize($file_name) > 0);
    }
}
