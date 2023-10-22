<?php

declare(strict_types=1);

namespace App\Page\Home;

use App\Model\DbRunnerInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngHomeReadPageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngHomeReadPage
    {
        $page = new MngHomeReadPage();

        parent::init($page, $container);

        // dbRunner
        $dbRunner = $container->get(DbRunnerInterface::class);

        $page->setDbRunner($dbRunner);

        return $page;
    }
}
