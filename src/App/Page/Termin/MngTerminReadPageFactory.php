<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Form\Search\MngTerminSearchForm;
use App\Model\Termin\TerminRepositoryInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngTerminReadPageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngTerminReadPage
    {
        $page = new MngTerminReadPage();

        // repository
        $page->setTerminRepository($container->get(TerminRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $page->setForm('termin-mng-search-form', $formManager->get(MngTerminSearchForm::class));

        parent::init($page, $container);

        return $page;
    }
}
