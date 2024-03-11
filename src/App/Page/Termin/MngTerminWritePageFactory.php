<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Form\TerminForm;
use App\Model\Media\MediaCommandInterface;
use App\Model\Termin\TerminCommandInterface;
use App\Model\Termin\TerminRepositoryInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngTerminWritePageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngTerminWritePage
    {
        $page = new MngTerminWritePage();

        // command
        $page->setMediaCommand($container->get(MediaCommandInterface::class));
        $page->setTerminCommand($container->get(TerminCommandInterface::class));

        // repository
        $page->setTerminRepository($container->get(TerminRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $page->setForm('termin-form', $formManager->get(TerminForm::class));

        parent::init($page, $container);

        return $page;
    }
}
