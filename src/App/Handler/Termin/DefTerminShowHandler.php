<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractDefTerminHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DefTerminShowHandler extends AbstractDefTerminHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // param
        $terminIdParam = (int) $request->getAttribute('p1');

        // init
        $myInitConfig     = $this->getMyInitConfig();
        $terminRepository = $this->getTerminRepository();

        // fetch termin
        $terminEntity = $terminRepository->findTerminById($terminIdParam);

        // view
        $viewData = [
            'terminEntity' => $terminEntity,
            'back' => $request->getQueryParams()['back'] ?? '/',
        ];

        // send response to client
        return new HtmlResponse($this->templateRenderer->render('app::def/termin/def-termin-show', $viewData), 200);
    }
}
