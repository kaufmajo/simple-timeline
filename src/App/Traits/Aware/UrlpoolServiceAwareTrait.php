<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Service\UrlpoolService;

trait UrlpoolServiceAwareTrait
{
    protected ?UrlpoolService $urlpoolService = null;

    public function setUrlpoolService(UrlpoolService $urlpoolService): void
    {
        $this->urlpoolService = $urlpoolService;
    }

    public function getUrlpoolService(): UrlpoolService
    {
        return $this->urlpoolService;
    }
}