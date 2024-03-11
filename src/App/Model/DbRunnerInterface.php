<?php

declare(strict_types=1);

namespace App\Model;

use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\SqlInterface;
use Laminas\Db\Sql\Where;

interface DbRunnerInterface
{
    public function getDb(): DbAdapter;

    public function executeSelect(SqlInterface|string $select, bool $logSqlString = false): ?ResultInterface;

    public function executeCommand(SqlInterface|string $command, bool $logSqlString = false): ResultInterface;

    public function executeSql(SqlInterface|string $query, bool $logSqlString = false): ResultInterface;

    public function whereLikeSearchWithSqlObject(string $suchtext, array $columns): ?Where;
}
