<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\AbstractCommand;
use App\Model\DbRunnerInterface;
use App\Model\Entity\EntityInterface;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Laminas\Db\Sql;
use Laminas\Hydrator\HydratorInterface;
use RuntimeException;

use function str_replace;

class TerminCommand extends AbstractCommand implements TerminCommandInterface
{
    use TerminRepositoryAwareTrait;

    public function __construct(DbRunnerInterface $dbRunner, HydratorInterface $hydrator)
    {
        parent::__construct($dbRunner, $hydrator);
    }

    public function getEntityData(EntityInterface $entity): array
    {
        return $this->hydrator->extract($entity);
    }

    public function insertTermin(TerminEntityInterface $terminEntity): int
    {
        // process
        $insert = new Sql\Insert('tajo1_termin');
        $insert->values($this->getEntityData($terminEntity));

        $affectedRows = $this->insert($insert, $generatedValue);

        // set entity id
        $terminEntity->setEntityId($generatedValue);

        $terminEntity->setLastEntityAction('insert');

        return $affectedRows;
    }

    public function updateTermin(TerminEntityInterface $terminEntity): int
    {
        if (! $terminEntity->getTerminId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }

        // process
        $update = new Sql\Update('tajo1_termin');
        $update->where(['termin_id = ?' => $terminEntity->getTerminId()]);
        $update->set($this->getEntityData($terminEntity));

        $affectedRows = $this->update($update);

        $terminEntity->setLastEntityAction('update');

        return $affectedRows;
    }

    public function deleteTermin(TerminEntityInterface $terminEntity): int
    {
        if (! $terminEntity->getTerminId()) {
            throw new RuntimeException('Cannot delete entity; missing identifier');
        }

        $terminEntity->setTerminIstGeloescht(1);

        // process
        $update = new Sql\Update('tajo1_termin');
        $update->where(['termin_id = ?' => $terminEntity->getTerminId()]);
        $update->set($this->getEntityData($terminEntity));

        return $this->update($update);
    }

    /**
     * @throws Exception
     */
    public function saveTermin(TerminEntityInterface $terminEntity): void
    {
        if (null === $terminEntity->getTerminId()) {
            // insert
            $this->insertTermin($terminEntity);
        } else {
            // update
            $this->updateTermin($terminEntity);
        }

        if ($terminEntity->isSerie()) {
            $this->insertSerie($terminEntity);
        }
    }

    /**
     * @throws Exception
     */
    public function insertSerie(TerminEntityInterface $terminEntity): void
    {
        $datePeriod        = $this->getSeriePeriod($terminEntity);
        $terminEntityClone = clone $terminEntity;

        $i = 0;

        foreach ($datePeriod as $dt) {
            // skip first iteration
            if (0 === $i++) {
                continue;
            }
            $terminEntityClone->setTerminId(null);
            $terminEntityClone->setTerminDatumStart($dt->format('Y-m-d'));
            $terminEntityClone->setTerminDatumEnde($dt->add($this->getTerminDiffInterval($terminEntity))->format('Y-m-d'));
            $this->insertTermin($terminEntityClone);
            $i++;
        }
    }

    /**
     * @throws Exception
     */
    public function getSeriePeriod(TerminEntityInterface $terminEntity): DatePeriod
    {
        $dateFromString = str_replace(
            '[day]',
            (new DateTime($terminEntity->getTerminDatumStart()))->format('l'),
            $terminEntity->getTerminSerieWiederholung()
        );

        return new DatePeriod(
            new DateTime($terminEntity->getTerminDatumStart()),
            DateInterval::createFromDateString($dateFromString),
            new DateTime($terminEntity->getTerminSerieEnde())
        );
    }

    /**
     * @throws Exception
     */
    public function getTerminDiffInterval(TerminEntityInterface $terminEntity): DateInterval
    {
        // dates termin
        $startTermin = new DateTime($terminEntity->getTerminDatumStart());
        $endeTermin  = new DateTime($terminEntity->getTerminDatumEnde());

        // define intervall
        return $startTermin->diff($endeTermin);
    }
}
