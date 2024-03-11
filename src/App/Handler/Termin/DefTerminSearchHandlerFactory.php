<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Form\Search\DefTerminSearchForm;
use App\Handler\AbstractBaseHandlerFactory;
use App\Model\Termin\TerminRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefTerminSearchHandlerFactory extends AbstractBaseHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DefTerminSearchHandler
    {
        $handler = new DefTerminSearchHandler();

        // repository
        $handler->setTerminRepository($container->get(TerminRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $handler->setForm('def-termin-search-form', $formManager->get(DefTerminSearchForm::class));

        parent::init($handler, $container);

        return $handler;
    }
}
