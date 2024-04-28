<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\AbstractReflectionHydrator;
use InvalidArgumentException;

use function substr;

class TerminReflectionHydrator extends AbstractReflectionHydrator
{
    public function hydrate(array $data, object $object): TerminEntityInterface
    {
        if (! $object instanceof TerminEntity) {
            throw new InvalidArgumentException('$object must be an instance of Entity');
        }

        // hydrate from parent class
        /** @var TerminEntity $object */
        $object = parent::hydrate($data, $object);

        $object->setTerminZeitStart(substr($object->getTerminZeitStart(), 0, -3));
        $object->setTerminZeitEnde(substr($object->getTerminZeitEnde(), 0, -3));

        return $object;
    }

    public function extract(object $object): array
    {
        if (! $object instanceof TerminEntity) {
            throw new InvalidArgumentException('$object must be an instance of Entity');
        }

        // extract from parent class
        $data = parent::extract($object);

        // time fields
        if ($object->getTerminZeitGanztags()) {
            $data['termin_zeit_start'] = '00:00:00';
            $data['termin_zeit_ende']  = '23:59:59';
        }

        // unset fields
        unset($data['fields']);
        unset($data['termin_serie_intervall']);
        unset($data['termin_serie_wiederholung']);
        unset($data['termin_serie_ende']);

        return $data;
    }
}
