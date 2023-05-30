<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\Purchases\Facades\Subscription;
use JsonException;
use Tests\TestCase;

class AppleFakeReceiptTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @throws GuzzleException
     * @throws InvalidReceiptException|JsonException
     */
    public function test_example(): void
    {
        // Create the expected body
        $responseBody = [
            'environment' => 'Sandbox',
            'status' => 0,
            'latest_receipt_info' => [
                [
                    'product_id' => 'A001',
                    'quantity' => '1',
                    'transaction_id' => 'W12wer3453442323242322feWWDCDCc',
                    // other fields omitted
                ],
            ],
            // other fields omitted
        ];

        // Create the response instance. It requires to JSON encode the body.
        $responseMock = new Response(200, [], json_encode($responseBody, JSON_THROW_ON_ERROR));

        // Use the client factory to mock the response.
        $client = ClientFactory::mock($responseMock);

        // --------------------------------------------------------------
        // The created client could be injected into a service
        // --------------------------------------------------------------
        // The part is up to you as a developer.
        //
        // Inside that service you can use the client as follows
        $verifyResponse = Subscription::appStore($client)->receiptData('fake_receipt_data')->verifyReceipt();
        // The returned response will contain the data from the response body you provided in the first line.

        $this->assertTrue(TRUE);
    }
}
