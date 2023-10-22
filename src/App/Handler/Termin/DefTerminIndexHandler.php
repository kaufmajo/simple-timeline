<?php

declare(strict_types=1);

namespace App\Handler\Termin;

use App\Handler\AbstractDefTerminHandler;
use App\Model\Media\MediaCollection;
use App\Model\Termin\TerminCollection;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;

class DefTerminIndexHandler extends AbstractDefTerminHandler
{
    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // init
        $myInitConfig     = $this->getMyInitConfig();
        $mediaRepository  = $this->getMediaRepository();
        $terminRepository = $this->getTerminRepository();

        // collection
        $headerCollection      = new TerminCollection();
        $informationCollection = new TerminCollection();
        $terminCollection      = new TerminCollection();
        $mediaCollection       = new MediaCollection();

        // datalist data
        $mitvonData    = $terminRepository->fetchMitvon($this->getMappedDatalistSearchValues())->toArray();
        $kategorieData = $terminRepository->fetchKategorie($this->getMappedDatalistSearchValues())->toArray();
        $betreffData   = $terminRepository->fetchBetreff($this->getMappedDatalistSearchValues())->toArray();

        // form
        $defTerminSearchForm = $this->getTerminSearchForm();
        $defTerminSearchForm->setAttribute('method', 'GET');
        $defTerminSearchForm->setAttribute('action', '/');
        $defTerminSearchForm->setData($request->getQueryParams());
        $isFormValid  = $defTerminSearchForm->isValid();
        $formData     = $defTerminSearchForm->getData();
        $searchValues = $this->getMappedTerminSearchValues($formData);

        // populate form data from query
        //if (isset($_GET['cache'])) $defTerminSearchForm->setData($request->getQueryParams());

        // view
        $viewData = [
            'headerCollection'      => $headerCollection,
            'informationCollection' => $informationCollection,
            'terminCollection'      => $terminCollection,
            'mediaCollection'       => $mediaCollection,
            'defTerminSearchForm'   => $defTerminSearchForm,
            'datalist'              => array_merge([['Sonntag'], ['Montag'], ['Dienstag'], ['Mittwoch'], ['Donnerstag'], ['Freitag'], ['Samstag']], $kategorieData, $betreffData, $mitvonData),
            'searchValues'          => $searchValues,
        ];

        if (! $isFormValid) {
            return new HtmlResponse(
                $this->templateRenderer->render('app::def/termin/def-termin-index', $viewData)
            );
        }

        // fetch media
        $mediaResultSet = $mediaRepository->fetchMedia($this->getMappedMediaSearchValues());

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($searchValues);

        // fetch information
        $informationResultSet = $terminRepository->fetchTermin(
            $this->getMappedInfoboxSearchValues(),
            ['t3.datum_datum', 't4.termin_id'],
            't4.termin_sortierung ASC, t4.termin_label DESC, t4.termin_datum_start DESC, t4.termin_datum_ende ASC, t4.termin_zeit_start DESC, t4.termin_zeit_ende ASC'
        );

        // fetch header
        $headerResultSet = $terminRepository->fetchTermin(
            $this->getMappedHeaderSearchValues(),
            ['t3.datum_datum', 't4.termin_id'],
            't4.termin_sortierung ASC, t4.termin_label DESC, t4.termin_datum_start DESC, t4.termin_datum_ende ASC, t4.termin_zeit_start DESC, t4.termin_zeit_ende ASC'
        );

        // init collection
        $headerCollection->init($headerResultSet->toArray());
        $informationCollection->init($informationResultSet->toArray());
        $terminCollection->init($terminResultSet->toArray());
        $mediaCollection->init($mediaResultSet->toArray());

        // send response to client
        return new HtmlResponse($this->templateRenderer->render('app::def/termin/def-termin-index', $viewData), 200);
    }
}
