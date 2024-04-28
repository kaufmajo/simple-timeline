<?php

declare(strict_types=1);

namespace App\Page\Termin;

use App\Model\Termin\TerminCollection;
use App\Page\AbstractBasePage;
use App\Service\HelperService;
use App\Service\UrlpoolService;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateTime;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Form\FormInterface;
use Laminas\Validator\Date;
use Psr\Http\Message\ServerRequestInterface;

class MngTerminCalendarPage extends AbstractBasePage
{
    use FormStorageAwareTrait;
    use MediaRepositoryAwareTrait;
    use TerminRepositoryAwareTrait;

    public function indexAction(ServerRequestInterface $request): HtmlResponse
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
        $terminRepository = $this->getTerminRepository();

        // collection
        $terminCollection      = new TerminCollection($dateParam);

        // view
        $viewData = [
            'terminCollection'      => $terminCollection,
            'terminIdParam' => $terminIdParam,
            'dateParam' => $dateParam,
        ];

        // fetch termin
        $terminResultSet = $terminRepository->fetchTermin($this->getMappedTerminSearchValues($dateParam));

        // init collection
        $terminCollection->init($terminResultSet->toArray());

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/termin/mng-termin-calendar-index', $viewData)
        );
    }

    /**
     * @throws Exception
     */
    public function getTerminSearchForm(array $params): FormInterface
    {
        $form = $this->getForm('termin-mng-search-form');
        $form->setAttribute('method', 'GET');
        $form->setAttribute('action', '/manage/termin-calendar');

        $form->setData($params);

        return $form;
    }

    public function getMappedTerminSearchValues(string $date): array
    {
        $myInitConfig = $this->getMyInitConfig();

        $searchValues              = [];
        $searchValues['start']     = HelperService::getMonthFirstDayForCalender($date)->format('Y-m-d');
        $searchValues['ende']      = HelperService::getMonthLastDayForCalender($date)->format('Y-m-d');
        $searchValues['ansicht']   = $myInitConfig['default_mng_search_ansicht'];

        return $searchValues;
    }
}
