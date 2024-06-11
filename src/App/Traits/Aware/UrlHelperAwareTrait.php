<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use Mezzio\Helper\UrlHelper;

trait UrlHelperAwareTrait
{
    protected ?UrlHelper $urlHelper = null;

    public function setUrlHelper(UrlHelper $urlHelper): void
    {
        $this->urlHelper = $urlHelper;
    }

    public function getUrlHelper(): UrlHelper
    {
        return $this->urlHelper;
    }
}