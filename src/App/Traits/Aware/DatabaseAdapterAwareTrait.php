<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use Laminas\Db\Adapter\Adapter as DbAdapter;

trait DatabaseAdapterAwareTrait
{
    protected DbAdapter $databaseAdapter;

    public function getDatabaseAdapter(): DbAdapter
    {
        return $this->databaseAdapter;
    }

    public function setDatabaseAdapter(DbAdapter $databaseAdapter): void
    {
        $this->databaseAdapter = $databaseAdapter;
    }
}
