<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use Laminas\Log\LoggerInterface;

trait LoggerAwareTrait
{
    protected LoggerInterface $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
