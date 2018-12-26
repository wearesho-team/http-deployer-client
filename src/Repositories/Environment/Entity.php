<?php

namespace Wearesho\Deployer\Repositories\Environment;

/**
 * Class Entity
 * @package Wearesho\Deployer\Repositories\Environment
 */
class Entity
{
    /** @var string */
    protected $host;

    /** @var string */
    protected $key;

    /** @var string|null */
    protected $value;

    public function __construct(string $host, string $key, string $value = null)
    {
        $this->host = $host;
        $this->key = $key;
        $this->value = $value;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
