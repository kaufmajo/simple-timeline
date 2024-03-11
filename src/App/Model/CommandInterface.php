<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Entity\EntityInterface;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Update;

interface CommandInterface
{
    public function getEntityData(EntityInterface $entity);

    public function insert(Insert|string $insert, int &$generatedValue, bool $logSqlString = false): int;

    public function update(Update|string $update, bool $logSqlString = false): int;

    public function delete(Delete|string $delete, bool $logSqlString = false): int;
}
