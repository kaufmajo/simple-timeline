<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use Mezzio\Template\TemplateRendererInterface;

trait TemplateRendererAwareTrait
{
    protected TemplateRendererInterface $templateRenderer;

    public function getTemplateRenderer(): TemplateRendererInterface
    {
        return $this->templateRenderer;
    }

    public function setTemplateRenderer(TemplateRendererInterface $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }
}
