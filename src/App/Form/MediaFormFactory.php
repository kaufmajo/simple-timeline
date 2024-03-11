<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\Media\MediaInputFilter;
use Laminas\Form\Form;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MediaFormFactory extends Form
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MediaForm
    {
        $inputFilterManager = $container->get('InputFilterManager');
        $inputFilter        = $inputFilterManager->get(MediaInputFilter::class);

        $form = new MediaForm();
        $form->setInputFilter($inputFilter);
        $form->init();

        return $form;
    }
}
