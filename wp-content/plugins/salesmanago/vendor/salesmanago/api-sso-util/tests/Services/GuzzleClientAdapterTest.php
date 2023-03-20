<?php

namespace Tests\src\Services;

use SALESmanago\Services\GuzzleClientAdapter;
use \GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;
use SALESmanago\Services\RequestService;
use SALESmanago\Entity\Configuration as Settings;
use Tests\Model\ContactModelTest;


class GuzzleClientAdapterTest extends TestCase
{

    public function testGetClientReturnsProperObjectOfGuzzle()
    {
        $guzzleAdapter = new GuzzleClientAdapter();
        $Settings = new Settings();
        $Settings->setEndpoint('app3.salesmanago.pl');
        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
        );
        $guzzleAdapter->setClient($Settings, $headers);


        $this->assertIsObject($guzzleAdapter->getClient());
    }

    /**
     * Test method uses endpoint from https://requestbin.com/
     * to make a real request
     * This test method is still working on guzzle v. 6.5.5, 7.2.0, 5.3.4
     */
    public function testTransferMethodReturnsASuccessResponse()
    {
        $Settings = new Settings();
        $Settings->setClientId('ye4vodnswfo6zp75')
            ->setDefaultApiKey()
            ->setApiSecret('123')
            ->generateSha()
            ->setOwner('qa.benhauer@gmx.com')
            ->setEndpoint('http://entwgc5779cid.x.pipedream.net', false);

        $requestService = new RequestService($Settings);

        $dummyData           = ContactModelTest::prepareDummyDataForContactEntity();
        $contactFromPlatform = $dummyData['contactFromPlatform'];

        $result = $requestService->request('POST', '', $contactFromPlatform );
        $this->assertArrayHasKey('success', $result);

    }

    /**
     * Try to create mock object and then test it.
     */
    public function testTransferMethodsAdaptsToDifferentVersion()
    {
        $dummyData           = ContactModelTest::prepareDummyDataForContactEntity();
        $contactFromPlatform = $dummyData['contactFromPlatform'];

        $mock = $this->createMock(GuzzleClientAdapter::class);
        $mock->method('transfer')
            ->with('POST', '', $contactFromPlatform);

        $this->assertNull($mock->transfer('POST', '', $contactFromPlatform));
    }
    /**
     * DataProvider
     * @return array
     */
    public function guzzleClientStructureProvider()
    {
        $clientOne = new GuzzleClient([
            'base_url' => 'app3.salesmanago.pl',
            'defaults' => array(
                'verify' => false,
                'timeout' => 45.0,
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json;charset=UTF-8')
            )
        ]);
        $clientTwo = new GuzzleClient([
            'base_uri' => 'app3.salesmanago.pl',
            'verify' => false,
            'timeout' => 45.0,
            'defaults' => [
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json;charset=UTF-8')
            ]
        ]);
        $clientTree = new GuzzleClient([
            'base_uri' => 'app3.salesmanago.pl',
            'verify' => false,
            'timeout' => 45.0,
            'defaults' => [
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json;charset=UTF-8')
            ]
        ]);
        return [
            [$clientOne],
            [$clientTwo],
            [$clientTree]
        ];
    }
}