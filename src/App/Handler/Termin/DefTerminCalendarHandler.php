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

class DefTerminCalendarHandler extends AbstractDefTerminHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->getUrlpoolService()->save();

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
        $terminCollection      = new TerminCollection($dateParam);

        // view
        $viewData = [
            'terminCollection' => $terminCollection,
            'terminIdParam' => $terminIdParam,
            'dateParam' => $dateParam,
        ];

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($this->getMappedCalendarSearchValues($dateParam));

        // init collection
        $terminCollection->init($terminResultSet->toArray());

        // send response to client
        return new HtmlResponse($this->templateRenderer->render('app::def/termin/def-termin-calendar', $viewData), 200);
    }
}
