<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Model\Termin\TerminCollection;
use App\Page\AbstractBasePage;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Form\FormInterface;
use Psr\Http\Message\ServerRequestInterface;

class MngTerminSearchPage extends AbstractBasePage
{
    use FormStorageAwareTrait;
    use TerminRepositoryAwareTrait;

    public function indexAction(ServerRequestInterface $request): HtmlResponse
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
        $defTerminSearchForm = $this->getTerminSearchForm([]);
        $defTerminSearchForm->setData($request->getQueryParams());
        $isFormValid  = $defTerminSearchForm->isValid();
        $formData     = $defTerminSearchForm->getData();
        $searchValues = $this->getMappedTerminSearchValues($formData);

        // view
        $viewData = [
            'terminCollection'      => $terminCollection,
            'defTerminSearchForm'   => $defTerminSearchForm,
            'datalist'              => array_merge([['Sonntag'], ['Montag'], ['Dienstag'], ['Mittwoch'], ['Donnerstag'], ['Freitag'], ['Samstag']], $kategorieData, $betreffData, $mitvonData),
            'redirectUrl'           => $this->getUrlpoolService()->get(),
        ];

        if (empty($_GET) || !$isFormValid) {

            return new HtmlResponse(
                $this->templateRenderer->render('app::mng/termin/mng-termin-search-index', $viewData)
            );
        }

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($searchValues, ['t4.termin_id']);

        // init collection
        $terminCollection->init($terminResultSet->toArray());

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/termin/mng-termin-search-index', $viewData)
        );
    }

    /**
     * @throws Exception
     */
    public function getTerminSearchForm(array $params): FormInterface
    {
        $form = $this->getForm('def-termin-search-form');
        $form->setAttribute('method', 'GET');
        $form->setAttribute('action', '/manage/termin-search');

        // set default data
        $form->setData($params);

        return $form;
    }

    public function getMappedTerminSearchValues(array $formData): array
    {
        $myInitConfig = $this->getMyInitConfig();

        $searchValues              = [];
        $searchValues['anzeige']   = true;
        $searchValues['suchtext']  = $formData['search_suchtext'] ?? '';
        $searchValues['ansicht']   = $myInitConfig['default_mng_search_ansicht'];

        return $searchValues;
    }

    public function getMappedDatalistSearchValues(): array
    {
        $myInitConfig = $this->getMyInitConfig();

        $searchValues              = [];
        $searchValues['anzeige']   = true;
        $searchValues['ansicht']   = $myInitConfig['default_mng_search_ansicht'];

        return $searchValues;
    }
}
