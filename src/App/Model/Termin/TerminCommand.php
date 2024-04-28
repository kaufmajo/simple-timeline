<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\AbstractCommand;
use App\Model\DbRunnerInterface;
use App\Model\Entity\EntityInterface;
use App\Service\HelperService;
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
        if (!$terminEntity->getTerminId()) {
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
        if (!$terminEntity->getTerminId()) {
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
        $datePeriod        = HelperService::getSeriePeriod(
            $terminEntity->getTerminDatumStart(),
            $terminEntity->getTerminSerieEnde(),
            $terminEntity->getTerminSerieWiederholung()
        );
        
        $terminEntityClone = clone $terminEntity;

        $i = 0;

        foreach ($datePeriod as $dt) {

            $dt = DateTime::createFromInterface($dt);

            // skip first iteration
            if (0 === $i++) {
                continue;
            }
            $terminEntityClone->setTerminId(null);
            $terminEntityClone->setTerminDatumStart($dt->format('Y-m-d'));
            $terminEntityClone->setTerminDatumEnde($dt->add($terminEntity->getIntervalDifference())->format('Y-m-d'));
            $this->insertTermin($terminEntityClone);
            $i++;
        }
    }
}
