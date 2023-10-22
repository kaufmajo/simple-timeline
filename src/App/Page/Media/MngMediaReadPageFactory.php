<?php

declare(strict_types=1);

namespace App\Page\Media;

use App\Form\Search\MngMediaSearchForm;
use App\Model\Media\MediaRepositoryInterface;
use App\Page\AbstractBasePageFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MngMediaReadPageFactory extends AbstractBasePageFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MngMediaReadPage
    {
        $page = new MngMediaReadPage();

        // repository
        $page->setMediaRepository($container->get(MediaRepositoryInterface::class));

        // form
        $formManager = $container->get('FormElementManager');
        $page->setForm('media-mng-search-form', $formManager->get(MngMediaSearchForm::class));

        parent::init($page, $container);

        return $page;
    }
}
