<?php

namespace Wearesho\Deployer\Repositories\Container;

/**
 * Class Entity
 * @package Wearesho\Deployer\Repositories\Container
 */
class Entity
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $status;

    public function __construct(string $name, string $status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isUp(): bool
    {
        return strpos($this->status, "Up") === 0;
    }
}
