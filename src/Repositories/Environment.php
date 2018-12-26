<?php

namespace Wearesho\Deployer\Repositories;

use GuzzleHttp;
use Horat1us\Yii\Exceptions\ModelException;
use Wearesho\Deployer;

/**
 * Class Environment
 * @package Wearesho\Deployer\Repositories
 */
class Environment
{
    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    public function __construct(GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Deployer\Models\Project $project
     * @return string[][]
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function fetch(Deployer\Models\Project $project): array
    {
        /** @var Environment\Entity $environment */
        $environments = [];

        foreach ($project->servers as $server) {
            $response = $this->client->request(
                'GET',
                rtrim($server->host, '/') . '/env',
                [
                    GuzzleHttp\RequestOptions::HEADERS => [
                        'x-project' => $project->name,
                        'x-authorization' => $server->secret,
                    ]
                ]
            );
            $body = json_decode((string)$response->getBody(), true);
            foreach ($body as $key => $value) {
                if (!array_key_exists($key, $environments)) {
                    $environments[$key] = [];
                }
                $environments[$key][$server->hostName] = $value;
            }
        }

        foreach ($environments as $key => &$entities) {
            ksort($entities);
        }
        ksort($environments);

        return $environments;
    }

    /**
     * @param Deployer\Models\Project $project
     * @param string $key
     * @return array
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws \Horat1us\Yii\Interfaces\ModelExceptionInterface
     */
    public function delete(Deployer\Models\Project $project, string $key): array
    {
        $previous = [];

        foreach ($project->servers as $server) {
            $response = $this->client->request(
                'DELETE',
                rtrim($server->host, '/') . '/env/' . urlencode($key),
                [
                    GuzzleHttp\RequestOptions::HEADERS => [
                        'x-project' => $project->name,
                        'x-authorization' => $server->secret,
                    ]
                ]
            );
            $headers = $response->getHeader('x-previous');
            $previous[$server->hostName] = $headers[0] ?? null;
        }

        $history = new Deployer\Models\Project\History([
            'project' => $project,
            'key' => $key,
            'value' => null,
        ]);
        ModelException::saveOrThrow($history);

        return $previous;
    }

    /**
     * @param Deployer\Models\Project\Server $server
     * @param string $key
     * @return null|string
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function get(Deployer\Models\Project\Server $server, string $key): ?string
    {
        $response = $this->client->request(
            'GET',
            rtrim($server->host, '/') . '/env/' . urlencode($key),
            [
                GuzzleHttp\RequestOptions::HEADERS => [
                    'x-project' => $server->project->name,
                    'x-authorization' => $server->secret,
                ],
            ]
        );
        $body = json_decode((string)$response->getBody(), true);

        return $body['value'];
    }

    /**
     * @param Deployer\Models\Project $project
     * @param string $key
     * @param string $value
     * @return array
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws \Horat1us\Yii\Interfaces\ModelExceptionInterface
     */
    public function put(Deployer\Models\Project $project, string $key, string $value): array
    {
        $previous = [];

        foreach ($project->servers as $server) {
            $response = $this->client->request(
                'PUT',
                rtrim($server->host, '/') . '/env/' . urlencode($key),
                [
                    GuzzleHttp\RequestOptions::HEADERS => [
                        'x-project' => $project->name,
                        'x-authorization' => $server->secret,
                    ],
                    GuzzleHttp\RequestOptions::JSON => compact('value'),
                ]
            );
            $body = json_decode((string)$response->getBody(), true);
            $previous[$server->hostName] = $body['previousValue'] ?? null;
        }

        $history = new Deployer\Models\Project\History([
            'project' => $project,
            'key' => $key,
            'value' => $value,
        ]);
        ModelException::saveOrThrow($history);

        return $previous;
    }

    /**
     * @param string $projectName
     * @param Deployer\Models\Project\Server $server
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function test(string $projectName, Deployer\Models\Project\Server $server): void
    {
        try {
            $this->client->request(
                'GET',
                rtrim($server->host, '/') . '/env',
                [
                    GuzzleHttp\RequestOptions::HEADERS => [
                        'x-project' => $projectName,
                        'x-authorization' => $server->secret,
                    ],
                ]
            );
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            $response = $exception->getResponse();
            if (!$response instanceof GuzzleHttp\Psr7\Response) {
                throw $exception;
            }
            $body = json_decode((string)$response->getBody(), true);
            switch ($response->getStatusCode()) {
                case 403:
                    throw new Environment\Exception\InvalidSecret(
                        $body['message'] ?? "Invalid secret",
                        $body['code'] ?? 0
                    );
                case 422:
                    throw new Environment\Exception\MissingConfiguration(
                        $body['message'] ?? "Missing environemtn configuration",
                        $body['code'] ?? 0
                    );
            }
            throw $exception;
        }
    }
}
