<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Form\Search\DefTerminSearchForm;
use App\Handler\AbstractBaseHandlerFactory;
use App\Model\Media\MediaRepositoryInterface;
use App\Model\Termin\TerminRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefTerminIndexHandlerFactory extends AbstractBaseHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefTerminIndexHandler
    {
        $handler = new DefTerminIndexHandler();

        // repository
        $handler->setMediaRepository($container->get(MediaRepositoryInterface::class));
        $handler->setTerminRepository($container->get(TerminRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $handler->setForm('def-termin-search-form', $formManager->get(DefTerminSearchForm::class));

        parent::init($handler, $container);

        return $handler;
    }
}
