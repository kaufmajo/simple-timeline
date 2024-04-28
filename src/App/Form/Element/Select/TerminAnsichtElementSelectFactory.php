<?php

declare(strict_types=1);

namespace App\Form\Element\Select;

use App\Form;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class TerminAnsichtElementSelectFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TerminAnsichtElementSelect
    {
        return new Form\Element\Select\TerminAnsichtElementSelect();
    }
}
