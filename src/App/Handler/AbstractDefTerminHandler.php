<?php

declare(strict_types=1);

namespace App\Handler;

use App\Enum\TerminAnsichtEnum;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateInterval;
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

    public function getStartSearchValue(): string
    {
        return (new DateTime())->format('Y-m-d');
    }

    public function getEndeSearchValue(bool $extend = false): string
    {
        $myInitConfig = $this->getMyInitConfig();

        $tage = $extend ? $myInitConfig['search_def_tage'] : $myInitConfig['default_def_tage'];

        return (new DateTime())->add(new DateInterval('P' . $tage . 'D'))->format('Y-m-d');
    }

    public function getMappedGridSearchValues(?string $date = null): array
    {
        $date = $date ? new DateTime($date) : new DateTime();

        $searchValues              = [];
        $searchValues['start']     = $date->modify('first day of this month')->format('Y-m-d');
        $searchValues['ende']      = $date->modify('last day of this month')->format('Y-m-d');
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;
        $searchValues['anzeige']   = false;

        return $searchValues;
    }

    public function getMappedTerminSearchValues(array $formData): array
    {
        $searchValues              = [];
        $searchValues['anzeige']   = true;
        $searchValues['start']     = $this->getStartSearchValue();
        $searchValues['ende']      = $this->getEndeSearchValue((bool) ($formData['search_suchtext'] ?? false));
        $searchValues['suchtext']  = $formData['search_suchtext'] ?? '';
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;
        
        return $searchValues;
    }

    public function getMappedDatalistSearchValues(): array
    {
        $searchValues              = [];
        $searchValues['anzeige']   = true;
        $searchValues['start']     = $this->getStartSearchValue();
        $searchValues['ende']      = $this->getEndeSearchValue();
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;

        return $searchValues;
    }
}