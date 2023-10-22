<?php

declare(strict_types=1);

namespace App\Page\Media;

use App\Form\MediaForm;
use App\Model\Media\MediaCommandInterface;
use App\Model\Media\MediaRepositoryInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngMediaWritePageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngMediaWritePage
    {
        $page = new MngMediaWritePage();

        // command
        $page->setMediaCommand($container->get(MediaCommandInterface::class));

        // repository
        $page->setMediaRepository($container->get(MediaRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $page->setForm('media-form', $formManager->get(MediaForm::class));

        parent::init($page, $container);

        return $page;
    }
}
