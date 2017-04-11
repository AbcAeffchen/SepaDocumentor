<?php

require __DIR__ . '/../vendor/autoload.php';

use AbcAeffchen\SepaDocumenter\FileRoutingSlip;

$pdf = FileRoutingSlip::createPDF('file_routing_slip_german.tpl',
                               ['file_name' => 'fileName',
                                'scheme_version' => 'pain.001.002.03',
                                'payment_type' => 'credit transfer',
                                'message_id' => 'some message id',
                                'creation_date_time' => '13.01.2017',
                                'initialising_party' => 'Some Guy',
                                'collection_reference' => 'some collection id',
                                'bic' => 'some bic',
                                'iban' => 'some iban',
                                'due_date' => 'some day',
                                'number_of_transactions' => '5',
                                'control_sum' => '123,45 €',
                                'current_date' => date('d.m.Y')
                               ]);

$file = fopen(__DIR__ . '/file_routing_slip.pdf', "w");
fwrite($file, $pdf);
fclose($file);