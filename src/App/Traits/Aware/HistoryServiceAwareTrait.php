<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Service\HistoryService;

trait HistoryServiceAwareTrait
{
    protected HistoryService $historyService;

    public function getHistoryService(): HistoryService
    {
        return $this->historyService;
    }

    public function setHistoryService(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }
}
