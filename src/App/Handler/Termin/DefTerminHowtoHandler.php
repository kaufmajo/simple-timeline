<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractDefTerminHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function ray;

class DefTerminHowtoHandler extends AbstractDefTerminHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // init
        $myInitConfig = $this->getMyInitConfig();

        // view
        $viewData = [
            'default_def_tage' => $myInitConfig['default_def_tage'],
            'search_def_tage'  => $myInitConfig['search_def_tage'],
        ];

        // send response to client
        return new HtmlResponse($this->templateRenderer->render('app::def/termin/def-termin-howto', $viewData), 200);
    }
}
