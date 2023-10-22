<?php

declare(strict_types=1);

namespace App\Form\Search;

use interop\container\containerinterface;
use Laminas\Form\Form;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DefTerminSearchFormFactory extends Form
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(containerinterface $container): DefTerminSearchForm
    {
        $inputFilterManager = $container->get('InputFilterManager');
        $inputFilter        = $inputFilterManager->get(DefTerminSearchInputFilter::class);

        $form = new DefTerminSearchForm();
        $form->setInputFilter($inputFilter);
        $form->init();

        return $form;
    }
}
