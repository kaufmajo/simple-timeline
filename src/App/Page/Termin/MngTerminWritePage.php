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
use DateTime;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\UploadedFile;
use Laminas\Form\Form;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;
use function array_merge_recursive;
use function count;
use function date;
use function is_array;

class MngTerminWritePage extends AbstractBasePage
{
    use FormStorageAwareTrait;
    use MediaCommandAwareTrait;
    use TerminCommandAwareTrait;
    use TerminRepositoryAwareTrait;

    public const LAST_URL_NAMESPACE = "mng-termin";
    public array $records           = [];

    public function initRecords(ServerRequestInterface $request): false|array
    {
        $params = $request->getQueryParams();

        if (! isset($params['id'])) {
            $this->records = [null];
            return $this->records;
        }

        if (! is_array($params['id']) || ! count($params['id'])) {
            return false;
        }

        foreach ($params['id'] as $value) {
            $this->records[] = (int) $value;
        }

        return $this->records;
    }

    public function getTerminEntity(): TerminEntityInterface
    {
        if (count($this->records) > 1) {
            return (new TerminEntity())->initBulk();
        } else {
            return $this->getTerminRepository()->findTerminById($this->records[0]);
        }
    }

    /**
     * @throws Exception
     */
    public function insertAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // script will stop when ...
        HelperService::isPostMaxSizeReached();

        $this->initRecords($request);

        // init
        $dateParam    = $request->getAttribute('date');
        $terminEntity = new TerminEntity();

        // datalist data
        [$mitvonData, $kategorieData, $betreffData, $labelData, $linkData, $linkTitelData, $imageData] = $this->datalistData();

        //form
        $terminForm = $this->getTerminForm();

        // view
        $viewData = [
            'dateParam'    => $dateParam,
            'myInitConfig' => $this->getMyInitConfig(),
            'terminForm'   => $terminForm,
            'terminEntity' => $terminEntity,
            'redirectUrl'  => $this->getUrlpoolService()->keep()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')),
            'datalist'     => ['mitvon' => $mitvonData, 'kategorie' => $kategorieData, 'betreff' => $betreffData, 'label' => $labelData, 'link' => $linkData, 'link_titel' => $linkTitelData, 'image' => $imageData],
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
        if (! $terminForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-insert', $viewData));
        }

        // save
        $terminEntity = $this->save($terminForm);

        $this->flashMessages()->flash('warning', 'default');

        return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($terminEntity->getTerminDatumStart()))->format('Y-m-d')));
    }

    /**
     * @throws Exception
     */
    public function updateAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        $this->initRecords($request);

        // init
        $dateParam    = $request->getAttribute('date');
        $terminEntity = $this->getTerminEntity();

        // datalist data
        [$mitvonData, $kategorieData, $betreffData, $labelData, $linkData, $linkTitelData, $imageData] = $this->datalistData();

        //form
        $terminForm = $this->getTerminForm();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminEntity' => $terminEntity,
            'terminForm'   => $terminForm,
            'redirectUrl'  => $this->getUrlpoolService()->keep()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')),
            'datalist'     => ['mitvon' => $mitvonData, 'kategorie' => $kategorieData, 'betreff' => $betreffData, 'label' => $labelData, 'link' => $linkData, 'link_titel' => $linkTitelData, 'image' => $imageData],
        ];

        if ('POST' !== $request->getMethod()) {
            $terminForm->setData($terminEntity->getArrayCopy());
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-update', $viewData));
        }

        $terminForm->setData(array_merge_recursive($request->getParsedBody(), $request->getUploadedFiles()));

        // process
        if (! $terminForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-update', $viewData));
        }

        // save
        $terminEntity = $this->save($terminForm);

        $this->flashMessages()->flash('warning', 'default');

        return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($terminEntity->getTerminDatumStart()))->format('Y-m-d')));
    }

    /**
     * @throws Exception
     */
    public function copyAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        $this->initRecords($request);

        // init
        $dateParam    = $request->getAttribute('date');
        $terminEntity = $this->getTerminEntity();

        // datalist data
        [$mitvonData, $kategorieData, $betreffData, $labelData, $linkData, $linkTitelData, $imageData] = $this->datalistData();

        // set id to null, since this is a copy and should be inserted as new record
        $terminEntity->setTerminId(null);

        //form
        $terminForm = $this->getTerminForm();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminForm'   => $terminForm,
            'terminEntity' => $terminEntity,
            'redirectUrl'  => $this->getUrlpoolService()->keep()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')),
            'datalist'     => ['mitvon' => $mitvonData, 'kategorie' => $kategorieData, 'betreff' => $betreffData, 'label' => $labelData, 'link' => $linkData, 'link_titel' => $linkTitelData, 'image' => $imageData],
        ];

        if ('POST' !== $request->getMethod()) {
            $terminForm->setData($terminEntity->getArrayCopy());

            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-copy', $viewData));
        }

        $terminForm->setData(array_merge_recursive($request->getParsedBody(), $request->getUploadedFiles()));

        // process
        if (! $terminForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-copy', $viewData));
        }

        // save
        $terminEntity = $this->save($terminForm);

        $this->flashMessages()->flash('warning', 'default');

        return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($terminEntity->getTerminDatumStart()))->format('Y-m-d')));
    }

    /**
     * @throws Exception
     */
    public function deleteAction(ServerRequestInterface $request): HtmlResponse|TextResponse|RedirectResponse
    {
        $this->initRecords($request);

        // init
        $dateParam     = $request->getAttribute('date');
        $terminEntity  = $this->getTerminEntity();
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
        if (! isset($request->getParsedBody()['confirm']) || 'Löschen' !== $request->getParsedBody()['confirm']) {
            return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')));
        }

        // ok ... now execute delete
        foreach ($this->records as $record) {
            $terminEntity = $this->getTerminRepository()->findTerminById($record);
            $terminCommand->deleteTermin($terminEntity);
        }

        $this->flashMessages()->flash('warning', 'default');

        return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')));
    }

    /**
     * @throws Exception
     */
    public function plusAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        $this->initRecords($request);

        // init
        $dateParam    = $request->getAttribute('date');
        $terminEntity = $this->getTerminEntity();

        // view
        $viewData = [
            'myInitConfig' => $this->getMyInitConfig(),
            'terminEntity' => $terminEntity,
            'redirectUrl'  => $this->getUrlpoolService()->keep()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')),
        ];

        if ('POST' !== $request->getMethod()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-write-plus', $viewData));
        }

        // redirect if confirmation is not given
        if (! isset($request->getParsedBody()['confirm']) || 'Speichern' !== $request->getParsedBody()['confirm']) {
            return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($dateParam))->format('Y-m-d')));
        }

        // ok ... now execute change
        foreach ($this->records as $record) {
            $terminEntity = $this->getTerminRepository()->findTerminById($record);
            $terminEntity->setTerminDatumStart((new DateTime($terminEntity->getTerminDatumStart()))->modify('+7 days')->format('Y-m-d'));
            $terminEntity->setTerminDatumEnde((new DateTime($terminEntity->getTerminDatumEnde()))->modify('+7 days')->format('Y-m-d'));
            $this->getTerminCommand()->saveTermin($terminEntity);
        }

        $this->flashMessages()->flash('warning', 'default');

        return new RedirectResponse($this->getUrlpoolService()->getUrlWithAnchor((new DateTime($terminEntity->getTerminDatumStart()))->format('Y-m-d')));
    }

    public function save(Form $terminForm): TerminEntityInterface
    {
        // init
        $terminEntity  = null;
        $mediaEntity   = null;
        $mediaLink     = null;
        $mediaImage    = null;
        $mediaCommand  = $this->getMediaCommand();
        $terminCommand = $this->getTerminCommand();

        // get form data
        $formData = $terminForm->getData();

        foreach ($this->records as $record) {
            $terminEntity = null === $record ? new TerminEntity() : $this->getTerminRepository()->findTerminById($record);
            $terminEntity->exchangeArray($formData);
            $mediaDatumEnde = $terminEntity->isSerie()
                ? $terminCommand->getSeriePeriod($terminEntity)->getEndDate()->format('Y-m-d')
                : $terminEntity->getTerminDatumEnde();

            // store media
            if (! $mediaEntity) {
                foreach (['media_datei_link', 'media_datei_bild'] as $media) {
                    // user selects a file
                    if ($formData[$media] instanceof UploadedFile && 0 === $formData[$media]->getError()) {
                        $mediaEntity = new MediaEntity();
                        $mediaEntity->setMediaTag('Terminformular');
                        $mediaEntity->setMediaVon(date('Y-m-d'));
                        $mediaEntity->setMediaBis($mediaDatumEnde);
                        $mediaEntity->setMediaPrivat(0);
                        $mediaEntity->setMediaBox(0);
                        $mediaCommand->storeMedia($mediaEntity, $formData[$media]);

                        $mediaLink  = 'media_datei_link' === $media ? '/media/' . $mediaEntity->getMediaId() : null;
                        $mediaImage = 'media_datei_bild' === $media ? '/media/' . $mediaEntity->getMediaId() : null;
                    }
                }
            } else {
                $mediaEntity->setMediaBis($mediaDatumEnde);
                $mediaCommand->saveMedia($mediaEntity);
            }

            // save termin
            $terminEntity->setTerminLink($mediaLink ?? $terminEntity->getTerminLink());
            $terminEntity->setTerminImage($mediaImage ?? $terminEntity->getTerminImage());
            $terminCommand->saveTermin($terminEntity);
        }

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
        $ansichtElement->setValueOptions(
            array_merge($ansichtElement->getValueOptionsFromConfig(), [IGNORE_STRING_VALUE => ['label' => IGNORE_STRING_VALUE, 'value' => IGNORE_STRING_VALUE]])
        );

        /**
         * status Element - set value
         *
         * @var TerminStatusElementSelect $statusElement
         */
        $statusElement = $terminForm->get('termin_status');
        $statusElement->setValueOptions(
            array_merge($statusElement->getValueOptionsFromConfig(), [IGNORE_STRING_VALUE => ['label' => IGNORE_STRING_VALUE, 'value' => IGNORE_STRING_VALUE]])
        );

        /**
         * bulk - disable fields and set validationGroup
         */
        if (count($this->records) > 1) {
            $terminForm->get('termin_datum_start')->setAttributes(['disabled' => 'disabled']);
            $terminForm->get('termin_datum_ende')->setAttributes(['disabled' => 'disabled']);
            $terminForm->get('termin_serie_intervall')->setAttributes(['disabled' => 'disabled']);
            $terminForm->get('termin_serie_wiederholung')->setAttributes(['disabled' => 'disabled']);
            $terminForm->get('termin_serie_ende')->setAttributes(['disabled' => 'disabled']);

            $terminForm->setValidationGroup([
                'termin_id',
                'termin_ansicht',
                'termin_status',
                'termin_zeit_start',
                'termin_zeit_ende',
                'termin_zeit_ganztags',
                'termin_betreff',
                'termin_text',
                'termin_kategorie',
                'termin_mitvon',
                'termin_image',
                'termin_link',
                'termin_link_titel',
                'termin_label',
                'termin_zeige_konflikt',
                'termin_aktiviere_drucken',
                'termin_ist_konfliktrelevant',
                'termin_zeige_einmalig',
                'termin_zeige_tagezuvor',
                'termin_notiz',
                'media_datei_link',
                'media_datei_bild',
            ]);
        }

        return $terminForm;
    }

    public function datalistData(): array
    {
        // init
        $terminRepository = $this->getTerminRepository();

        $mitvonData    = $terminRepository->fetchMitvon()->toArray();
        $kategorieData = $terminRepository->fetchKategorie()->toArray();
        $betreffData   = $terminRepository->fetchBetreff()->toArray();
        $labelData     = $terminRepository->fetchLabel()->toArray();
        $linkData      = $terminRepository->fetchLink()->toArray();
        $linkTitelData = $terminRepository->fetchLinkTitel()->toArray();
        $imageData     = $terminRepository->fetchImage()->toArray();

        return [$mitvonData, $kategorieData, $betreffData, $labelData, $linkData, $linkTitelData, $imageData];
    }
}
