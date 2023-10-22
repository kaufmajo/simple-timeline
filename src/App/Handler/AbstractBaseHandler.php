<?php

declare(strict_types=1);

namespace App\Handler;

use App\Traits\Aware\ConfigAwareTrait;
use App\Traits\Aware\LoggerAwareTrait;
use App\Traits\Aware\TemplateRendererAwareTrait;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractBaseHandler implements RequestHandlerInterface
{
    use ConfigAwareTrait;

    use LoggerAwareTrait;

    use TemplateRendererAwareTrait;
}
