<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Enum\TerminAnsichtEnum;
use App\Enum\TerminStatusEnum;
use App\Form\Element\Select\TerminAnsichtElementSelect;
use App\Form\Element\Select\TerminKategorieElementSelect;
use App\Form\Element\Select\TerminStatusElementSelect;
use App\Model\Termin\TerminCollection;
use App\Page\AbstractBasePage;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateInterval;
use DateTime;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Form\FormInterface;
use Psr\Http\Message\ServerRequestInterface;

class MngTerminReadPage extends AbstractBasePage
{
    use FormStorageAwareTrait;
    use TerminRepositoryAwareTrait;

    public const LAST_URL_NAMESPACE = "mng-termin";

    public function indexAction(ServerRequestInterface $request): HtmlResponse
    {
        $this->getUrlpoolService()->saveUrl();

        // init
        $terminRepository = $this->getTerminRepository();

        // collection
        $terminCollection = new TerminCollection();

        // form
        $mngTerminSearchForm = $this->getTerminSearchForm($request->getQueryParams());
        $isFormValid         = $mngTerminSearchForm->isValid();
        $formData            = $mngTerminSearchForm->getData();
        $searchValues        = $this->getMappedTerminSearchValues($formData);

        // view
        $viewData = [
            'terminCollection'    => $terminCollection,
            'mngTerminSearchForm' => $mngTerminSearchForm,
            'searchValues'        => $searchValues,
        ];

        // check form
        if (! $isFormValid) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/termin/mng-termin-read-index', $viewData));
        }

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($searchValues);

        // init collection
        $terminCollection->init($terminResultSet->toArray());

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/termin/mng-termin-read-index', $viewData)
        );
    }

    /**
     * @throws Exception
     */
    public function getTerminSearchForm(array $params): FormInterface
    {
        $myInitConfig = $this->getMyInitConfig();

        $form = $this->getForm('termin-mng-search-form');
        $form->setAttribute('method', 'GET');
        $form->setAttribute('action', '/manage/termin-read');

        // search_kategorie
        /** @var TerminKategorieElementSelect $kategorieElement */
        $kategorieElement = $form->get('search_kategorie');
        $kategorieElement->setValueOptionsFromDb();

        // search_status
        /** @var TerminStatusElementSelect $statusElement */
        $statusElement = $form->get('search_status');
        $statusElement->setValueOptionsFromConfig();

        // search_ansicht
        /** @var TerminAnsichtElementSelect $ansichtElement */
        $ansichtElement = $form->get('search_ansicht');
        $ansichtElement->setValueOptionsFromConfig();

        // set default search values if form is initial loaded without submit
        if (empty($_GET)) {
            $form->setData([
                'search_start'   => (new DateTime())->format('Y-m-d'),
                'search_ende'    => (new DateTime())->add(new DateInterval('P' . $myInitConfig['default_mng_tage'] . 'D'))->format('Y-m-d'),
                'search_status'  => $myInitConfig['default_mng_search_status'],
                'search_ansicht' => $myInitConfig['default_mng_search_ansicht'],
                'search_tage'    => ['1', '2', '3', '4', '5', '6', '7'],
            ]);
        } else {
            $form->setData($params);
        }

        return $form;
    }

    public function getMappedTerminSearchValues(array $formData): array
    {
        $searchValues              = [];
        $searchValues['start']     = $formData['search_start'];
        $searchValues['ende']      = $formData['search_ende'];
        $searchValues['suchtext']  = $formData['search_suchtext'];
        $searchValues['kategorie'] = $formData['search_kategorie'];
        $searchValues['status']    = empty($formData['search_status']) ? TerminStatusEnum::getValueArray() : $formData['search_status'];
        $searchValues['ansicht']   = empty($formData['search_ansicht']) ? TerminAnsichtEnum::getValueArray() : $formData['search_ansicht'];
        $searchValues['tage']      = $formData['search_tage'];
        $searchValues['anzeige']   = $formData['search_anzeige'];

        return $searchValues;
    }
}
