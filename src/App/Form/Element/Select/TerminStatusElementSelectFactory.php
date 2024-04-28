<?php

declare(strict_types=1);

namespace App\Form\Element\Select;

use App\Form;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class TerminStatusElementSelectFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @param array|null $options
     * @return TerminStatusElementSelect|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new Form\Element\Select\TerminStatusElementSelect();
    }
}
