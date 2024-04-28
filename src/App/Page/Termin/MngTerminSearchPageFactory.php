<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Form\Search\DefTerminSearchForm;
use App\Model\Termin\TerminRepositoryInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngTerminSearchPageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngTerminSearchPage
    {
        $page = new MngTerminSearchPage();

        // repository
        $page->setTerminRepository($container->get(TerminRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $page->setForm('def-termin-search-form', $formManager->get(DefTerminSearchForm::class));

        parent::init($page, $container);

        return $page;
    }
}
