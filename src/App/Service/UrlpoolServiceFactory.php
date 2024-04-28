<?php

declare(strict_types=1);

namespace App\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class UrlpoolServiceFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UrlpoolService
    {
        // logger
        $logger = $container->get('AppLogger');

        $urlpoolService = new UrlpoolService();
        $urlpoolService->setLogger($logger);

        return $urlpoolService;
    }
}
