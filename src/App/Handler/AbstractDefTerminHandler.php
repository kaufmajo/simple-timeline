<?php

declare(strict_types=1);

namespace App\Handler;

use App\Enum\TerminAnsichtEnum;
use App\Enum\TerminStatusEnum;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use App\Traits\Aware\TerminRepositoryAwareTrait;
use DateInterval;
use DateTime;
use Exception;
use Laminas\Form\FormInterface;

abstract class AbstractDefTerminHandler extends AbstractBaseHandler
{
    use FormStorageAwareTrait;

    use MediaRepositoryAwareTrait;

    use TerminRepositoryAwareTrait;

    /**
     * @throws Exception
     */
    public function getTerminSearchForm(): FormInterface
    {
        $form = $this->getForm('def-termin-search-form');

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

    /**
     * @throws Exception
     */
    public function getEndeSearchValue(bool $extend = false): string
    {
        $myInitConfig = $this->getMyInitConfig();

        $tage = $extend ? $myInitConfig['search_def_tage'] : $myInitConfig['default_def_tage'];

        return (new DateTime())->add(new DateInterval('P' . $tage . 'D'))->format('Y-m-d');
    }

    /**
     * @throws Exception
     */
    public function getMappedTerminSearchValues(array $formData): array
    {
        $searchValues              = [];
        $searchValues['start']     = $this->getStartSearchValue();
        $searchValues['ende']      = $this->getEndeSearchValue((bool) ($formData['search_suchtext'] ?? false));
        $searchValues['suchtext']  = $formData['search_suchtext'] ?? '';
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;
        $searchValues['anzeige']   = true;

        return $searchValues;
    }

    public function getMappedInfoboxSearchValues(): array
    {
        $searchValues              = [];
        $searchValues['start']     = (new DateTime())->format('Y-m-d');
        $searchValues['ende']      = (new DateTime())->format('Y-m-d');
        $searchValues['ansicht']   = [TerminAnsichtEnum::INFOBOX->value];
        $searchValues['status']    = [TerminStatusEnum::GESTRICHEN->value, TerminStatusEnum::NORMAL->value, TerminStatusEnum::MITTEILUNG->value];
        $searchValues['tagezuvor'] = true;
        $searchValues['anzeige']   = true;

        return $searchValues;
    }

    public function getMappedHeaderSearchValues(): array
    {
        $searchValues              = [];
        $searchValues['start']     = (new DateTime())->format('Y-m-d');
        $searchValues['ende']      = (new DateTime())->format('Y-m-d');
        $searchValues['ansicht']   = [TerminAnsichtEnum::HEADER->value];
        $searchValues['status']    = [TerminStatusEnum::MITTEILUNG->value, TerminStatusEnum::BILD->value];
        $searchValues['tagezuvor'] = true;
        $searchValues['anzeige']   = true;

        return $searchValues;
    }

    /**
     * @throws Exception
     */
    public function getMappedDatalistSearchValues(): array
    {
        $searchValues              = [];
        $searchValues['start']     = $this->getStartSearchValue();
        $searchValues['ende']      = $this->getEndeSearchValue();
        $searchValues['ansicht']   = [TerminAnsichtEnum::TIMELINE->value];
        $searchValues['tagezuvor'] = true;
        $searchValues['anzeige']   = true;

        return $searchValues;
    }

    public function getMappedMediaSearchValues(): array
    {
        $searchValues           = [];
        $searchValues['privat'] = 0;
        $searchValues['box']    = 1;
        $searchValues['von']    = (new DateTime())->format('Y-m-d');
        $searchValues['bis']    = (new DateTime())->format('Y-m-d');

        return $searchValues;
    }
}
