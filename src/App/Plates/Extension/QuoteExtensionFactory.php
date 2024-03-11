<?php

declare(strict_types=1);

namespace App\Plates\Extension;

use Psr\Container\ContainerInterface;

class QuoteExtensionFactory
{
    public function __invoke(ContainerInterface $container): QuoteExtension
    {
        return new QuoteExtension();
    }
}
