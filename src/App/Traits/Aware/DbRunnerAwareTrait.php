<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Model\DbRunnerInterface;

trait DbRunnerAwareTrait
{
    protected DbRunnerInterface $dbRunner;

    public function getDbRunner(): DbRunnerInterface
    {
        return $this->dbRunner;
    }

    public function setDbRunner(DbRunnerInterface $dbRunner): void
    {
        $this->dbRunner = $dbRunner;
    }
}
