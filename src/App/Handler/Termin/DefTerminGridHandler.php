<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractDefTerminHandler;
use App\Model\Termin\TerminCollection;
use App\Service\UrlpoolService;
use DateTime;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Validator\Date;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DefTerminGridHandler extends AbstractDefTerminHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $request
            ->getAttribute(UrlpoolService::class)
            ->save($request);

        // param
        $dateParam = (string)($request->getQueryParams()['date'] ?? (new DateTime())->format('Y-m-d'));
        $terminIdParam = (int)($request->getQueryParams()['id'] ?? 0);

        if (!(new Date())->isValid($dateParam)) {
            return new TextResponse('No valid date is given');
        }

        // init
        $myInitConfig     = $this->getMyInitConfig();
        $terminRepository = $this->getTerminRepository();

        // collection
        $terminCollection      = new TerminCollection();

        // view
        $viewData = [
            'terminCollection' => $terminCollection,
            'terminIdParam' => $terminIdParam,
            'dateParam' => $dateParam,
        ];

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($this->getMappedGridSearchValues($dateParam));

        // init collection
        $terminCollection->init($terminResultSet->toArray());

        // send response to client
        return new HtmlResponse($this->templateRenderer->render('app::def/termin/def-termin-grid', $viewData), 200);
    }
}
