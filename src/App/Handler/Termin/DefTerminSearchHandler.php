<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractDefTerminHandler;
use App\Model\Termin\TerminCollection;
use App\Service\UrlpoolService;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DefTerminSearchHandler extends AbstractDefTerminHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // init
        $myInitConfig     = $this->getMyInitConfig();
        $terminRepository = $this->getTerminRepository();

        // collection
        $terminCollection = new TerminCollection();

        // datalist data
        $mitvonData    = $terminRepository->fetchMitvon($this->getMappedDatalistSearchValues())->toArray();
        $kategorieData = $terminRepository->fetchKategorie($this->getMappedDatalistSearchValues())->toArray();
        $betreffData   = $terminRepository->fetchBetreff($this->getMappedDatalistSearchValues())->toArray();

        // form
        $defTerminSearchForm = $this->getTerminSearchForm();
        $defTerminSearchForm->setData($request->getQueryParams());
        $isFormValid  = $defTerminSearchForm->isValid();
        $formData     = $defTerminSearchForm->getData();
        $searchValues = $this->getMappedTerminSearchValues($formData);

        // view
        $viewData = [
            'terminCollection'      => $terminCollection,
            'defTerminSearchForm'   => $defTerminSearchForm,
            'datalist'              => array_merge([['Sonntag'], ['Montag'], ['Dienstag'], ['Mittwoch'], ['Donnerstag'], ['Freitag'], ['Samstag']], $kategorieData, $betreffData, $mitvonData),
            'redirectUrl'           => $request->getAttribute(UrlpoolService::class)->keep()->get(),
        ];

        if (empty($_GET) || !$isFormValid) {

            return new HtmlResponse(
                $this->templateRenderer->render('app::def/termin/def-termin-search', $viewData)
            );
        }

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($searchValues, ['t4.termin_id']);

        // init collection
        $terminCollection->init($terminResultSet->toArray());

        // send response to client
        return new HtmlResponse($this->templateRenderer->render('app::def/termin/def-termin-search', $viewData), 200);
    }
}
