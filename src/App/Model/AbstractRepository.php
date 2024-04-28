<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Entity\AbstractEntity;
use App\Model\Entity\EntityInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\SqlInterface;
use Laminas\Hydrator\HydratorInterface;

use function in_array;

abstract class AbstractRepository implements RepositoryInterface
{
    protected DbRunnerInterface $dbRunner;

    protected HydratorInterface $hydrator;

    protected EntityInterface $prototype;

    public function __construct(DbRunnerInterface $dbRunner, HydratorInterface $hydrator, AbstractEntity $prototype)
    {
        $this->dbRunner  = $dbRunner;
        $this->hydrator  = $hydrator;
        $this->prototype = $prototype;
    }

    public function getDbRunner(): DbRunnerInterface
    {
        return $this->dbRunner;
    }

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function setPrototype(AbstractEntity $prototype)
    {
        $this->prototype = $prototype;
    }

    public function find(SqlInterface|string $select, bool $logSqlString = false): HydratingResultSet
    {
        $result = $this->dbRunner->executeSelect($select, $logSqlString);

        $resultSet = new HydratingResultSet($this->hydrator, $this->prototype);

        $resultSet->initialize($result);

        return $resultSet;
    }

    public function findFirst(SqlInterface|string $select, bool $logSqlString = false): ?EntityInterface
    {
        $result = $this->find($select, $logSqlString)->current();

        return ! $result instanceof EntityInterface ? null : $result;
    }

    public function fetch(SqlInterface|string $select, bool $logSqlString = false): ResultSet
    {
        $result = $this->dbRunner->executeSelect($select, $logSqlString);

        $resultSet = new ResultSet();

        $resultSet->initialize($result);

        return $resultSet;
    }

    public function isParamValid(array $params, string $key, array $falseValue = [null, '']): bool
    {
        if (! isset($params[$key])) {
            return false;
        }

        if (in_array($params[$key], $falseValue, true)) {
            return false;
        }

        return true;
    }
}
