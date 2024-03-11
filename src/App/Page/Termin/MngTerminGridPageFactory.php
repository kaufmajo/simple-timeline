<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Model\Termin\TerminRepositoryInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngTerminGridPageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngTerminGridPage
    {
        $page = new MngTerminGridPage();

        // repository
        $page->setTerminRepository($container->get(TerminRepositoryInterface::class));

        parent::init($page, $container);

        return $page;
    }
}
