<?php

declare(strict_types=1);

namespace App\Plates\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use League\Plates\Template\Template;

class QuoteExtension implements ExtensionInterface
{
    protected Engine $engine;
    public Template $template;

    public function __construct()
    {
    }

    public function register(Engine $engine)
    {
        $this->engine = $engine;

        $engine->registerFunction('quote', [$this, 'quote']);
    }

    public function quote(): string
    {
        $data = $this->template->data();

        return 'Character, not circumstances makes the man.';
    }
}
