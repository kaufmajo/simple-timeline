<?php

declare(strict_types=1);

namespace App\Handler;

use App\Enum\TerminAnsichtEnum;
use App\Service\HelperService;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateTime;
use Laminas\Form\FormInterface;

abstract class AbstractDefTerminHandler extends AbstractBaseHandler
{
    use FormStorageAwareTrait;

    use MediaRepositoryAwareTrait;

    use TerminRepositoryAwareTrait;

    public function getTerminSearchForm(): FormInterface
    {
        $form = $this->getForm('def-termin-search-form');
        $form->setAttribute('method', 'GET');
        $form->setAttribute('action', '/search');

        // set default data
        $form->setData([
            'search_suchtext' => '',
        ]);

        return $form;
    }

    public function getMappedCalendarSearchValues(?string $date = null): array
    {
        $searchValues              = [];
        $searchValues['start']     = HelperService::getMonthFirstDayForCalender($date)->format('Y-m-d');
        $searchValues['ende']      = HelperService::getMonthLastDayForCalender($date)->format('Y-m-d');
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;
        $searchValues['anzeige']   = false;

        return $searchValues;
    }

    public function getMappedTerminSearchValues(array $formData): array
    {
        $searchValues              = [];
        $searchValues['anzeige']   = true;
        $searchValues['start']     = (new DateTime())->format('Y-m-d');
        $searchValues['suchtext']  = $formData['search_suchtext'] ?? '';
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;

        return $searchValues;
    }

    public function getMappedDatalistSearchValues(): array
    {
        $searchValues              = [];
        $searchValues['anzeige']   = true;
        $searchValues['start']     = (new DateTime())->format('Y-m-d');
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;

        return $searchValues;
    }
}
