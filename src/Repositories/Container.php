<?php

declare(strict_types=1);

namespace Wearesho\Deployer\Repositories;

use GuzzleHttp;
use Wearesho\Deployer;

/**
 * Class Container
 * @package Wearesho\Deployer\Repositories
 */
class Container
{
    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    public function __construct(GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Deployer\Models\Project\Server $server
     * @return array
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function fetch(Deployer\Models\Project\Server $server): array
    {
        try {
            $response = $this->client->request(
                'GET',
                rtrim($server->host, '/') . '/status/' . $server->project->name,
                [
                    GuzzleHttp\RequestOptions::HEADERS => [
                        'x-authorization' => $server->secret,
                    ],
                    GuzzleHttp\RequestOptions::TIMEOUT => 5,
                ]
            );
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            $body = $exception->getResponse()->getBody()->__toString();
            if ($exception->getResponse() && $exception->getResponse()->getStatusCode() === 404) {
                return [];
            }
            throw $exception;
        }

        $body = json_decode((string)$response->getBody(), true);
        if (!array_key_exists('code', $body) || $body['code'] !== 0) {
            return [];
        }

        return array_map(function (array $row): Container\Entity {
            return new Deployer\Repositories\Container\Entity($row['name'], $row['status']);
        }, $body['containers']);
    }
}
