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

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use AbcAeffchen\SepaDocumenter\ControlList;

class TestControlList extends TestCase
{

    protected $dataWithBIC;
    protected $dataWithoutBIC;
    protected $transactions;
    protected $expectedOutputWithBIC;
    protected $expectedOutputWithoutBIC;

    protected function setUp()
    {
        $this->dataWithBIC = ['file_name'              => 'fileName',
                              'message_id'             => 'some message id',
                              'creation_date'          => '13.01.2017',
                              'creation_time'          => '12:34',
                              'debtor_name'            => 'Some Guy',
                              'bic'                    => 'some bic',
                              'iban'                   => 'some iban',
                              'number_of_transactions' => 5,
                              'control_sum'            => '123,45 €',
                              'current_date'           => '11.04.2017'];

        $this->dataWithoutBIC = ['file_name'              => 'fileName',
                                 'message_id'             => 'some message id',
                                 'creation_date'          => '13.01.2017',
                                 'creation_time'          => '12:34',
                                 'debtor_name'            => 'Some Guy',
                                 'iban'                   => 'some iban',
                                 'number_of_transactions' => 5,
                                 'control_sum'            => '123,45 €',
                                 'current_date'           => '11.04.2017'];

        $this->transactions = [['due_date'              => '14.01.2017',
                                'creditor_name'         => 'some other guy 1',
                                'iban'                  => 'some iban 1',
                                'bic'                   => 'some bic 1',
                                'remittance_information' => 'some remittance information 1',
                                'amount'                => '1,23 €'],
                               ['due_date'              => '15.01.2017',
                                'creditor_name'         => 'some other guy 2',
                                'iban'                  => 'some iban 2',
                                'remittance_information' => 'some remittance information 2',
                                'amount'                => '45,60  €'],
                               ['due_date'              => '16.01.2017',
                                'creditor_name'         => 'some other guy 3',
                                'iban'                  => 'some iban 3',
                                'bic'                   => 'some bic 3',
                                'remittance_information' => 'some remittance information 3',
                                'amount'                => '789,00 €'],
                               [    // empty entry
                               ]];

        $this->expectedOutputWithBIC = <<<'HTML'
<h2 style="text-align: center;">Sephpa Kontroll-Liste</h2>
<br>
<table style="margin-top: 1cm; width: 100%;">
    <tr>
        <td style="width: 50%;">Dateiname</td>
        <td style="width: 50%; text-align: right;">fileName</td>
    </tr>
    <tr>
        <td>Nachrichten-ID</td>
        <td style="width: 50%; text-align: right;">some message id</td>
    </tr>
    <tr>
        <td>Datum</td>
        <td style="width: 50%; text-align: right;">13.01.2017</td>
    </tr>
    <tr>
        <td>Uhrzeit</td>
        <td style="width: 50%; text-align: right;">12:34</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="width: 50%; text-align: right;"></td>
    </tr>
    <tr>
        <td>Inhaber</td>
        <td style="width: 50%; text-align: right;">Some Guy</td>
    </tr>
    <tr>
        <td>IBAN</td>
        <td style="width: 50%; text-align: right;">some iban</td>
    </tr>
    
    <tr>
        <td>BIC</td>
        <td style="width: 50%; text-align: right;">some bic</td>
    </tr>
    
    <tr>
        <td>&nbsp;</td>
        <td></td>
    </tr>
    <tr>
        <td>Anzahl</td>
        <td style="width: 50%; text-align: right;">5</td>
    </tr>
    <tr>
        <td>Summe</td>
        <td style="width: 50%; text-align: right;">123,45 €</td>
    </tr>
</table>

<table style="margin-top: 2cm; border-collapse: collapse; width: 100%; topntail: 2px;">
    <thead>
    <tr>
        <td style="font-weight: bold;">Fälligkeit</td>
        <td style="font-weight: bold;">Empfänger<br>
        IBAN / BIC</td>
        <td style="font-weight: bold;">Verwendungszweck</td>
        <td style="font-weight: bold;"></td>
        <td style="font-weight: bold;">Betrag</td>
    </tr>
    </thead>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;">14.01.2017</td>
        <td style="border-bottom: 1px solid #999;">some other guy 1<br>
        some iban 1  / some bic 1</td>
        <td style="border-bottom: 1px solid #999;">some remittance information 1</td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;">1,23 €</td>
    </tr>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;">15.01.2017</td>
        <td style="border-bottom: 1px solid #999;">some other guy 2<br>
        some iban 2 </td>
        <td style="border-bottom: 1px solid #999;">some remittance information 2</td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;">45,60  €</td>
    </tr>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;">16.01.2017</td>
        <td style="border-bottom: 1px solid #999;">some other guy 3<br>
        some iban 3  / some bic 3</td>
        <td style="border-bottom: 1px solid #999;">some remittance information 3</td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;">789,00 €</td>
    </tr>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;"><br>
         </td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;"></td>
    </tr>
    
</table>

HTML;

        $this->expectedOutputWithoutBIC = <<<'HTML'
<h2 style="text-align: center;">Sephpa Kontroll-Liste</h2>
<br>
<table style="margin-top: 1cm; width: 100%;">
    <tr>
        <td style="width: 50%;">Dateiname</td>
        <td style="width: 50%; text-align: right;">fileName</td>
    </tr>
    <tr>
        <td>Nachrichten-ID</td>
        <td style="width: 50%; text-align: right;">some message id</td>
    </tr>
    <tr>
        <td>Datum</td>
        <td style="width: 50%; text-align: right;">13.01.2017</td>
    </tr>
    <tr>
        <td>Uhrzeit</td>
        <td style="width: 50%; text-align: right;">12:34</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="width: 50%; text-align: right;"></td>
    </tr>
    <tr>
        <td>Inhaber</td>
        <td style="width: 50%; text-align: right;">Some Guy</td>
    </tr>
    <tr>
        <td>IBAN</td>
        <td style="width: 50%; text-align: right;">some iban</td>
    </tr>
    
    <tr>
        <td>&nbsp;</td>
        <td></td>
    </tr>
    <tr>
        <td>Anzahl</td>
        <td style="width: 50%; text-align: right;">5</td>
    </tr>
    <tr>
        <td>Summe</td>
        <td style="width: 50%; text-align: right;">123,45 €</td>
    </tr>
</table>

<table style="margin-top: 2cm; border-collapse: collapse; width: 100%; topntail: 2px;">
    <thead>
    <tr>
        <td style="font-weight: bold;">Fälligkeit</td>
        <td style="font-weight: bold;">Empfänger<br>
        IBAN / BIC</td>
        <td style="font-weight: bold;">Verwendungszweck</td>
        <td style="font-weight: bold;"></td>
        <td style="font-weight: bold;">Betrag</td>
    </tr>
    </thead>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;">14.01.2017</td>
        <td style="border-bottom: 1px solid #999;">some other guy 1<br>
        some iban 1  / some bic 1</td>
        <td style="border-bottom: 1px solid #999;">some remittance information 1</td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;">1,23 €</td>
    </tr>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;">15.01.2017</td>
        <td style="border-bottom: 1px solid #999;">some other guy 2<br>
        some iban 2 </td>
        <td style="border-bottom: 1px solid #999;">some remittance information 2</td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;">45,60  €</td>
    </tr>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;">16.01.2017</td>
        <td style="border-bottom: 1px solid #999;">some other guy 3<br>
        some iban 3  / some bic 3</td>
        <td style="border-bottom: 1px solid #999;">some remittance information 3</td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;">789,00 €</td>
    </tr>
    
    <tr style="border-bottom: 1px solid;">
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;"><br>
         </td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;"></td>
        <td style="border-bottom: 1px solid #999;"></td>
    </tr>
    
</table>

HTML;
    }

    public function testControlListText()
    {
        $generatedHTML = ControlList::createText('credit_transfer_control_list_german.tpl',
                                                     $this->dataWithBIC, $this->transactions);

        self::assertSame($this->expectedOutputWithBIC, $generatedHTML);
        self::assertNotSame($this->expectedOutputWithoutBIC, $generatedHTML);
    }

    public function testControlListWithoutBICText()
    {
        $generatedHTML = ControlList::createText('credit_transfer_control_list_german.tpl',
                                                 $this->dataWithoutBIC, $this->transactions);

        self::assertSame($this->expectedOutputWithoutBIC, $generatedHTML);
        self::assertNotSame($this->expectedOutputWithBIC, $generatedHTML);
    }

    public function testFileRoutingSlipPDF()
    {
        // create PDF with BIC
        $file_name = __DIR__ . '/control_list_with_bic.pdf';
        $html = ControlList::createPDF('credit_transfer_control_list_german.tpl',
                                            $this->dataWithBIC, $this->transactions);

        $file = fopen($file_name, "wb");
        fwrite($file, $html);
        fclose($file);

        self::assertTrue(file_exists($file_name) && filesize($file_name) > 0);


        // create PDF without BIC
        $file_name = __DIR__ . '/control_list_without_bic.pdf';
        $html = ControlList::createPDF('credit_transfer_control_list_german.tpl',
                                       $this->dataWithoutBIC, $this->transactions);

        $file = fopen($file_name, "wb");
        fwrite($file, $html);
        fclose($file);

        self::assertTrue(file_exists($file_name) && filesize($file_name) > 0);

        // create PDF with many transactions
        $file_name = __DIR__ . '/control_list_long.pdf';
        $manyTransactions = array_merge($this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions,
                                        $this->transactions, $this->transactions);
        $html = ControlList::createPDF('credit_transfer_control_list_german.tpl',
                                       $this->dataWithoutBIC, $manyTransactions);

        $file = fopen($file_name, "wb");
        fwrite($file, $html);
        fclose($file);

        self::assertTrue(file_exists($file_name) && filesize($file_name) > 0);
    }

}
