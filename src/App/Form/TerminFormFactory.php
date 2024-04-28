<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\Termin\TerminInputFilter;
use interop\container\containerinterface;
use Laminas\Form\Form;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TerminFormFactory extends Form
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(containerinterface $container): TerminForm
    {
        $inputFilterManager = $container->get('InputFilterManager');
        $inputFilter        = $inputFilterManager->get(TerminInputFilter::class);

        $form = new TerminForm();
        $form->setInputFilter($inputFilter);
        $form->init();

        return $form;
    }
}
