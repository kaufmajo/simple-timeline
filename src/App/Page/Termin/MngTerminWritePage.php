<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Form\Element\Select\TerminAnsichtElementSelect;
use App\Form\Element\Select\TerminStatusElementSelect;
use App\Model\Media\MediaEntity;
use App\Model\Termin\TerminEntity;
use App\Model\Termin\TerminEntityInterface;
use App\Page\AbstractBasePage;
use App\Service\HelperService;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaCommandAwareTrait;
use App\Traits\Aware\TerminCommandAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateInterval;
use DateTime;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\UploadedFile;
use Laminas\Form\Form;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge_recursive;
use function date;

class MngTerminWritePage extends AbstractBasePage
{
    use FormStorageAwareTrait;
    use MediaCommandAwareTrait;
    use TerminCommandAwareTrait;
    use TerminRepositoryAwareTrait;

    public function insertAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // script will stop when ...
        HelperService::isPostMaxSizeReached();

        // init
        $dateParam    = (string)$request->getAttribute('p1');
        $terminEntity = new TerminEntity();

        // datalist data
        [$mitvonData, $kategorieData, $betreffData, $linkData, $linkTitelData, $imageData] = $this->datalistData();

        //form
        $terminForm = $this->getTerminForm();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminForm'   => $terminForm,
            'terminEntity' => $terminEntity,
            'redirectUrl'  => $this->getUrlpoolService()->get(),
            'datalist'     => ['mitvon' => $mitvonData, 'kategorie' => $kategorieData, 'betreff' => $betreffData, 'link' => $linkData, 'link_titel' => $linkTitelData, 'image' => $imageData],
        ];

        if ('POST' !== $request->getMethod()) {

            $formData                       = $terminEntity->getArrayCopy();
            $formData['termin_datum_start'] = $dateParam;
            $formData['termin_datum_ende']  = $dateParam;

            $terminForm->setData($formData);

            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-insert', $viewData));
        }

        $terminForm->setData(array_merge_recursive($request->getParsedBody(), $request->getUploadedFiles()));

        // process
        if (!$terminForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-insert', $viewData));
        }

        // save
        $terminEntity = $this->save($terminEntity, $terminForm);

        $this->flashMessages($request)->flash('primary', 'default');

        return new RedirectResponse($this->getUrlpoolService()->query(['date' => $terminEntity->getTerminDatumStart()], reset: true)->fragment($terminEntity->getTerminDatumStart())->get());
    }

    public function updateAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // script will stop when ...
        HelperService::isPostMaxSizeReached();

        // init
        $terminIdParam    = (int)$request->getAttribute('p1');
        $terminEntity = $this->getTerminRepository()->findTerminById($terminIdParam);

        // datalist data
        [$mitvonData, $kategorieData, $betreffData, $linkData, $linkTitelData, $imageData] = $this->datalistData();

        //form
        $terminForm = $this->getTerminForm();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminEntity' => $terminEntity,
            'terminForm'   => $terminForm,
            'redirectUrl'  => $this->getUrlpoolService()->query(['date' => $terminEntity->getTerminDatumStart()], reset: true)->fragment($terminEntity->getTerminDatumStart())->get(),
            'datalist'     => ['mitvon' => $mitvonData, 'kategorie' => $kategorieData, 'betreff' => $betreffData, 'link' => $linkData, 'link_titel' => $linkTitelData, 'image' => $imageData],
        ];

        if ('POST' !== $request->getMethod()) {
            $terminForm->setData($terminEntity->getArrayCopy());
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-update', $viewData));
        }

        $terminForm->setData(array_merge_recursive($request->getParsedBody(), $request->getUploadedFiles()));

        // process
        if (!$terminForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-update', $viewData));
        }

        // save
        $terminEntity = $this->save($terminEntity, $terminForm);

        $this->flashMessages($request)->flash('primary', 'default');

        return new RedirectResponse($this->getUrlpoolService()->query(['date' => $terminEntity->getTerminDatumStart()], reset: true)->fragment($terminEntity->getTerminDatumStart())->get());
    }

    public function copyAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // script will stop when ...
        HelperService::isPostMaxSizeReached();

        // init
        $terminIdParam    = (int)$request->getAttribute('p1');
        $terminEntity = $this->getTerminRepository()->findTerminById($terminIdParam);

        // datalist data
        [$mitvonData, $kategorieData, $betreffData, $linkData, $linkTitelData, $imageData] = $this->datalistData();

        // set id to null, since this is a copy and should be inserted as new record
        $terminEntity->setTerminId(null);

        //form
        $terminForm = $this->getTerminForm();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminForm'   => $terminForm,
            'terminEntity' => $terminEntity,
            'redirectUrl'  => $this->getUrlpoolService()->fragment($terminEntity->getTerminDatumStart())->get(),
            'datalist'     => ['mitvon' => $mitvonData, 'kategorie' => $kategorieData, 'betreff' => $betreffData, 'link' => $linkData, 'link_titel' => $linkTitelData, 'image' => $imageData],
        ];

        if ('POST' !== $request->getMethod()) {
            $terminForm->setData($terminEntity->getArrayCopy());

            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-copy', $viewData));
        }

        $terminForm->setData(array_merge_recursive($request->getParsedBody(), $request->getUploadedFiles()));

        // process
        if (!$terminForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-copy', $viewData));
        }

        // save
        $terminEntity = $this->save($terminEntity, $terminForm);

        $this->flashMessages($request)->flash('primary', 'default');

        return new RedirectResponse($this->getUrlpoolService()->query(['date' => $terminEntity->getTerminDatumStart()], reset: true)->fragment($terminEntity->getTerminDatumStart())->get());
    }

    public function deleteAction(ServerRequestInterface $request): HtmlResponse|TextResponse|RedirectResponse
    {
        // init
        $terminIdParam     = (int)$request->getAttribute('p1');
        $terminEntity  = $this->getTerminRepository()->findTerminById($terminIdParam);
        $terminCommand = $this->getTerminCommand();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminEntity' => $terminEntity,
        ];

        // ask for confirmation
        if ('POST' !== $request->getMethod()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-delete', $viewData));
        }

        // redirect if confirmation is not given
        if (!isset($request->getParsedBody()['confirm']) || 'Löschen' !== $request->getParsedBody()['confirm']) {
            return new RedirectResponse($this->getUrlpoolService()->fragment($terminEntity->getTerminDatumStart())->get());
        }

        // ok ... now execute delete
        $terminCommand->deleteTermin($terminEntity);

        $this->flashMessages($request)->flash('primary', 'default');

        return new RedirectResponse($this->getUrlpoolService()->query(['date' => $terminEntity->getTerminDatumStart()], reset: true)->fragment($terminEntity->getTerminDatumStart())->get());
    }

    public function save(TerminEntityInterface $terminEntity, Form $terminForm): TerminEntityInterface
    {
        // init
        $mediaEntity   = null;
        $mediaUrl     = [];
        $mediaCommand  = $this->getMediaCommand();
        $terminCommand = $this->getTerminCommand();

        // form data
        $formData = $terminForm->getData();
        $terminEntity->exchangeArray($formData);
        $mediaDatumEnde = ($terminEntity->isSerie()
            ? DateTime::createFromInterface(HelperService::getSeriePeriod(
                $terminEntity->getTerminDatumStart(),
                $terminEntity->getTerminSerieEnde(),
                $terminEntity->getTerminSerieWiederholung()
            )->getEndDate())
            : new DateTime($terminEntity->getTerminDatumEnde()))->add(new DateInterval('P31D'))->format('Y-m-d');

        // store media
        if (!$mediaEntity) {
            foreach (['media_datei_link', 'media_datei_link2', 'media_datei_bild'] as $media) {
                // user selects a file
                if ($formData[$media] instanceof UploadedFile && 0 === $formData[$media]->getError()) {
                    $mediaEntity = new MediaEntity();
                    $mediaEntity->setMediaTag('Terminformular');
                    $mediaEntity->setMediaVon(date('Y-m-d'));
                    $mediaEntity->setMediaBis($mediaDatumEnde);
                    $mediaEntity->setMediaPrivat(0);
                    $mediaCommand->storeMedia($mediaEntity, $formData[$media]);
                    $mediaUrl[$media] = '/media/' . $mediaEntity->getMediaId();
                }
            }
        } else {
            $mediaEntity->setMediaBis($mediaDatumEnde);
            $mediaCommand->saveMedia($mediaEntity);
        }

        // save termin
        $terminEntity->setTerminLink($mediaUrl['media_datei_link'] ?? $terminEntity->getTerminLink());
        $terminEntity->setTerminLink2($mediaUrl['media_datei_link2'] ?? $terminEntity->getTerminLink2());
        $terminEntity->setTerminImage($mediaUrl['media_datei_bild'] ?? $terminEntity->getTerminImage());
        $terminCommand->saveTermin($terminEntity);

        return $terminEntity;
    }

    public function getTerminForm(): Form
    {
        /** @var Form $terminForm */
        $terminForm = $this->getForm('termin-form');
        $terminForm->setAttribute('method', 'POST');
        $terminForm->setAttribute('enctype', 'multipart/form-data');
        //$terminForm->bind($terminEntity); --> not used, because of multipart/form-data file-upload
        //$terminForm->setData($formData);

        /**
         * ansicht Element - set value
         *
         * @var TerminAnsichtElementSelect $ansichtElement
         */
        $ansichtElement = $terminForm->get('termin_ansicht');
        $ansichtElement->setValueOptions($ansichtElement->getValueOptionsFromConfig());

        /**
         * status Element - set value
         *
         * @var TerminStatusElementSelect $statusElement
         */
        $statusElement = $terminForm->get('termin_status');
        $statusElement->setValueOptions($statusElement->getValueOptionsFromConfig());


        // $terminForm->get('termin_datum_start')->setAttributes(['disabled' => 'disabled']);
        // $terminForm->get('termin_datum_ende')->setAttributes(['disabled' => 'disabled']);
        // $terminForm->get('termin_serie_intervall')->setAttributes(['disabled' => 'disabled']);
        // $terminForm->get('termin_serie_wiederholung')->setAttributes(['disabled' => 'disabled']);
        // $terminForm->get('termin_serie_ende')->setAttributes(['disabled' => 'disabled']);

        // $terminForm->setValidationGroup([
        //     'termin_id',
        //     'termin_ansicht',
        //     'termin_status',
        //     'termin_zeit_start',
        //     'termin_zeit_ende',
        //     'termin_zeit_ganztags',
        //     'termin_betreff',
        //     'termin_text',
        //     'termin_kategorie',
        //     'termin_mitvon',
        //     'termin_image',
        //     'termin_link',
        //     'termin_link_titel',
        //     'termin_link2',
        //     'termin_link2_titel',
        //     'termin_zeige_konflikt',
        //     'termin_aktiviere_drucken',
        //     'termin_ist_konfliktrelevant',
        //     'termin_zeige_einmalig',
        //     'termin_zeige_tagezuvor',
        //     'termin_notiz',
        //     'media_datei_link',
        //     'media_datei_bild',
        // ]);


        return $terminForm;
    }

    public function datalistData(): array
    {
        // init
        $terminRepository = $this->getTerminRepository();

        $mitvonData    = $terminRepository->fetchMitvon()->toArray();
        $kategorieData = $terminRepository->fetchKategorie()->toArray();
        $betreffData   = $terminRepository->fetchBetreff()->toArray();
        $linkData      = $terminRepository->fetchLink()->toArray();
        $linkTitelData = $terminRepository->fetchLinkTitel()->toArray();
        $imageData     = $terminRepository->fetchImage()->toArray();

        return [$mitvonData, $kategorieData, $betreffData, $linkData, $linkTitelData, $imageData];
    }
}
