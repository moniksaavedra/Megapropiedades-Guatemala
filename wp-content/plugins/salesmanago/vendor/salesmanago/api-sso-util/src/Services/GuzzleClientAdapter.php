<?php

namespace SALESmanago\Services;

use \GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;

class GuzzleClientAdapter
{

    private $client;

    /**
     * Checks const of GuzzleClient
     * @return array
     * @throws \ReflectionException
     */
    public function getConstants()
    {
        $object = new \ReflectionClass(GuzzleClient::class);
        return $object->getConstants();
    }

    /**
     * Checking GuzzleClient version.
     * In version 7+ const VERSION doesn't exist.
     * Instead const MAJOR_VERSION.
     * @param ConfigurationInterface $conf
     * @param $headers
     * @return void
     */
    public function setClient(ConfigurationInterface $conf, $headers)
    {
        $arrayOfConstant = $this->getConstants();

        if (array_key_exists('VERSION', $arrayOfConstant)) {

            if (version_compare(GuzzleClient::VERSION, '6.0.0', '<')) {
                $this->client = new GuzzleClient([
                    'base_url' => $conf->getEndpoint(),
                    'defaults' => array(
                        'verify' => false,
                        'timeout' => 45.0,
                        'headers' => $headers
                    )
                ]);

            } elseif (version_compare(GuzzleClient::VERSION, '6.0.0', '>')) {
                $this->client = new GuzzleClient([
                    'base_uri' => $conf->getEndpoint(),
                    'verify' => false,
                    'timeout' => 45.0,
                    'defaults' => [
                        'headers' => $headers,
                    ]
                ]);
            }
        } else {
            $this->client = new GuzzleClient([
                'base_uri' => $conf->getEndpoint(),
                'verify' => false,
                'timeout' => 45.0,
                'defaults' => [
                    'headers' => $headers,
                ]
            ]);
        }
    }

    /**
     * @param $data
     * @param $method
     * @param $uri
     * @return mixed
     */
    public function transfer($method, $uri, $data)
    {
        if (method_exists(GuzzleClient::class, 'post')) {
            $response = $this->client->post($uri, array('json' => $data));
        } else {
            $response = $this->client->request($method, $uri, array('json' => $data));
        }

        return $response;
    }

    public function getClient()
    {
        return $this->client;
    }
}
