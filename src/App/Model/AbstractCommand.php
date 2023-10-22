<?php

declare(strict_types=1);

namespace App\Model;

use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Update;
use Laminas\Hydrator\HydratorInterface;

abstract class AbstractCommand implements CommandInterface
{
    protected DbRunnerInterface $dbRunner;

    protected HydratorInterface $hydrator;

    public function __construct(DbRunnerInterface $dbRunner, HydratorInterface $hydrator)
    {
        $this->dbRunner = $dbRunner;

        $this->hydrator = $hydrator;
    }

    public function insert(Insert|string $insert, ?int &$generatedValue, bool $logSqlString = false): int
    {
        $result = $this->dbRunner->executeCommand($insert, $logSqlString);

        $generatedValue = (int) $result->getGeneratedValue();

        return $result->getAffectedRows();
    }

    public function update(Update|string $update, bool $logSqlString = false): int
    {
        $result = $this->dbRunner->executeCommand($update, $logSqlString);

        return $result->getAffectedRows();
    }

    public function delete(Delete|string $delete, bool $logSqlString = false): int
    {
        $result = $this->dbRunner->executeCommand($delete, $logSqlString);

        return $result->getAffectedRows();
    }
}
