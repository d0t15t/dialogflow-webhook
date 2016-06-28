<?php

namespace ApiAi\Method;

use ApiAi\Client;
use ApiAi\ResponseHandler;

/**
 * Class Query
 *
 * @package ApiAi\Method
 */
class Query
{
    use ResponseHandler;

    /**
     * @var Client
     */
    private $client;

    /**
     * Query constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $query
     * @param array $extraParams
     *
     * @return mixed
     */
    public function extractMeaning($query, $extraParams = [])
    {
        $query = array_merge($extraParams, [
            'query' => $query,
        ]);

        $response = $this->client->get('query', $query);

        return $this->decodeResponse($response);
    }

}