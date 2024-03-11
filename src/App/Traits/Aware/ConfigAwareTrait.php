<?php

declare(strict_types=1);

namespace App\Traits\Aware;

trait ConfigAwareTrait
{
    protected array $config;

    protected array $myInitConfig;

    public function getConfig(string $key): ?array
    {
        $config = $this->config;

        if (! isset($config[$key])) {
            return null;
        }

        return $key ? $config[$key] : $config;
    }

    public function setConfig(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getMyInitConfig(?string $key = null): ?array
    {
        $myInitConfig = $this->myInitConfig ?? $this->getConfig('my_init_config');

        if (null !== $key && ! isset($myInitConfig[$key])) {
            return null;
        }

        return $key ? $myInitConfig[$key] : $myInitConfig;
    }

    public function setMyInitConfig(array $config): static
    {
        $this->myInitConfig = $config;

        return $this;
    }
}
