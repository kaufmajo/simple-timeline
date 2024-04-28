<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\Entity\AbstractEntity;
use DateInterval;
use DateTime;
use Exception;

class TerminEntity extends AbstractEntity implements TerminEntityInterface
{
    protected ?int $termin_id = null;

    protected ?string $termin_kategorie = null;

    protected ?string $termin_status = null;

    protected ?string $termin_datum_start = null;

    protected ?string $termin_datum_ende = null;

    protected ?string $termin_zeit_start = '00:00';

    protected ?string $termin_zeit_ende = '23:59';

    protected ?int $termin_zeit_ganztags = 1;

    protected ?string $termin_betreff = null;

    protected ?string $termin_text = null;

    protected ?string $termin_mitvon = null;

    protected ?string $termin_image = null;

    protected ?string $termin_link = null;

    protected ?string $termin_link_titel = null;

    protected ?string $termin_link2 = null;

    protected ?string $termin_link2_titel = null;

    protected ?string $termin_serie_intervall = null; // 0 = einzeltermin; 1 = serie; 2 = einzeltermin einer serie

    protected ?string $termin_serie_wiederholung = null;

    protected ?string $termin_serie_ende = null;

    protected ?int $termin_ist_geloescht = 0;

    protected ?int $termin_zeige_konflikt = 1;

    protected ?int $termin_aktiviere_drucken = 1;

    protected ?string $termin_ansicht = null;

    protected ?int $termin_ist_konfliktrelevant = 1;

    protected ?int $termin_zeige_einmalig = 0;

    protected ?int $termin_zeige_tagezuvor = null;

    protected ?string $termin_notiz = null;

    private array $fields = [
        'termin_id',
        'termin_status',
        'termin_datum_start',
        'termin_datum_ende',
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
        'termin_link2',
        'termin_link2_titel',
        'termin_serie_intervall',
        'termin_serie_wiederholung',
        'termin_serie_ende',
        'termin_ist_geloescht',
        'termin_zeige_konflikt',
        'termin_aktiviere_drucken',
        'termin_ansicht',
        'termin_ist_konfliktrelevant',
        'termin_zeige_einmalig',
        'termin_zeige_tagezuvor',
        'termin_notiz',
    ];

    public function getArrayCopy(): array
    {
        $data = [];

        foreach ($this->fields as $field) {
            $this->getExchangeValue($data, $field);
        }

        return $data;
    }

    public function exchangeArray(array $data): static
    {
        foreach ($this->fields as $field) {
            $this->setExchangeValue($data, $field);
        }

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->termin_id;
    }

    public function setEntityId(int $entityId): static
    {
        $this->termin_id = $entityId;

        return $this;
    }

    public function getTerminId(): ?int
    {
        return $this->termin_id;
    }

    public function setTerminId(?int $value): static
    {
        $this->termin_id = $value;

        return $this;
    }

    public function getTerminStatus(): ?string
    {
        return $this->termin_status;
    }

    public function setTerminStatus(?string $value): void
    {
        $this->termin_status = $value;
    }

    public function getTerminDatumStart(): ?string
    {
        return $this->termin_datum_start;
    }

    public function setTerminDatumStart(?string $value): void
    {
        $this->termin_datum_start = $value;
    }

    public function getTerminDatumEnde(): ?string
    {
        return $this->termin_datum_ende;
    }

    public function setTerminDatumEnde(?string $value): void
    {
        $this->termin_datum_ende = $value;
    }

    public function getTerminZeitStart(): ?string
    {
        return $this->termin_zeit_start;
    }

    public function setTerminZeitStart(?string $value): void
    {
        $this->termin_zeit_start = $value;
    }

    public function getTerminZeitEnde(): ?string
    {
        return $this->termin_zeit_ende;
    }

    public function setTerminZeitEnde(?string $value): void
    {
        $this->termin_zeit_ende = $value;
    }

    public function getTerminZeitGanztags(): ?int
    {
        return $this->termin_zeit_ganztags;
    }

    public function setTerminZeitGanztags(?int $value): void
    {
        $this->termin_zeit_ganztags = $value;
    }

    public function getTerminBetreff(): ?string
    {
        return $this->termin_betreff;
    }

    public function setTerminBetreff(?string $value): void
    {
        $this->termin_betreff = $value;
    }

    public function getTerminText(): ?string
    {
        return $this->termin_text;
    }

    public function setTerminText(?string $value): void
    {
        $this->termin_text = $value;
    }

    public function getTerminKategorie(): ?string
    {
        return $this->termin_kategorie;
    }

    public function setTerminKategorie(?string $value): void
    {
        $this->termin_kategorie = $value;
    }

    public function getTerminMitvon(): ?string
    {
        return $this->termin_mitvon;
    }

    public function setTerminMitvon(?string $value): void
    {
        $this->termin_mitvon = $value;
    }

    public function getTerminImage(): ?string
    {
        return $this->termin_image;
    }

    public function setTerminImage(?string $value): void
    {
        $this->termin_image = $value;
    }

    public function getTerminLink(): ?string
    {
        return $this->termin_link;
    }

    public function setTerminLink(?string $value): void
    {
        $this->termin_link = $value;
    }

    public function getTerminLinkTitel(): ?string
    {
        return $this->termin_link_titel;
    }

    public function setTerminLinkTitel(?string $value): void
    {
        $this->termin_link_titel = $value;
    }

    public function getTerminLink2(): ?string
    {
        return $this->termin_link2;
    }

    public function setTerminLink2(?string $value): void
    {
        $this->termin_link2 = $value;
    }

    public function getTerminLink2Titel(): ?string
    {
        return $this->termin_link2_titel;
    }

    public function setTerminLink2Titel(?string $value): void
    {
        $this->termin_link2_titel = $value;
    }

    public function getTerminSerieIntervall(): ?string
    {
        return $this->termin_serie_intervall;
    }

    public function setTerminSerieIntervall(?string $value): void
    {
        $this->termin_serie_intervall = $value;
    }

    public function getTerminSerieWiederholung(): ?string
    {
        return $this->termin_serie_wiederholung;
    }

    public function setTerminSerieWiederholung(?string $value): void
    {
        $this->termin_serie_wiederholung = $value;
    }

    public function getTerminSerieEnde(): ?string
    {
        return $this->termin_serie_ende;
    }

    public function setTerminSerieEnde(?string $value): void
    {
        $this->termin_serie_ende = $value;
    }

    public function getTerminIstGeloescht(): ?int
    {
        return $this->termin_ist_geloescht;
    }

    public function setTerminIstGeloescht(?int $value): void
    {
        $this->termin_ist_geloescht = $value;
    }

    public function getTerminZeigeKonflikt(): ?int
    {
        return $this->termin_zeige_konflikt;
    }

    public function setTerminZeigeKonflikt(?int $value): void
    {
        $this->termin_zeige_konflikt = $value;
    }

    public function getTerminAktiviereDrucken(): ?int
    {
        return $this->termin_aktiviere_drucken;
    }

    public function setTerminAktiviereDrucken(?int $value): void
    {
        $this->termin_aktiviere_drucken = $value;
    }

    public function getTerminAnsicht(): ?string
    {
        return $this->termin_ansicht;
    }

    public function setTerminAnsicht(?string $value): void
    {
        $this->termin_ansicht = $value;
    }

    public function getTerminIstKonfliktrelevant(): ?int
    {
        return $this->termin_ist_konfliktrelevant;
    }

    public function setTerminIstKonfliktsrelevant(?int $value): void
    {
        $this->termin_ist_konfliktrelevant = $value;
    }

    public function getTerminZeigeEinmalig(): ?int
    {
        return $this->termin_zeige_einmalig;
    }

    public function setTerminZeigeEinmalig(?int $value): void
    {
        $this->termin_zeige_einmalig = $value;
    }

    public function getTerminZeigeTagezuvor(): ?int
    {
        return $this->termin_zeige_tagezuvor;
    }

    public function setTerminZeigeTagezuvor(?int $value): void
    {
        $this->termin_zeige_tagezuvor = $value;
    }

    public function getTerminNotiz(): ?string
    {
        return $this->termin_notiz;
    }

    public function setTerminNotiz(?string $value): void
    {
        $this->termin_notiz = $value;
    }

    /**
     * @throws Exception
     */
    public function getEntityLabel(): array
    {
        $start = DateTime::createFromFormat('Y-m-d', $this->termin_datum_start);
        $ende  = DateTime::createFromFormat('Y-m-d', $this->termin_datum_ende);

        $return              = [];
        $return['ID']        = $this->termin_id;
        $return['Kategorie'] = $this->termin_kategorie;
        $return['Betreff']   = $this->termin_betreff;
        $return['Mit/Von']   = $this->termin_mitvon;
        $return['Start']     = $start ? $start->format('d.m.Y') : '';
        $return['Ende']      = $ende ? $ende->format('d.m.Y') : '';

        return $return;
    }

    public function isSerie(): bool
    {
        return (bool) $this->getTerminSerieIntervall();
    }

    public function getIntervalDifference(): DateInterval
    {
        $startTermin = new DateTime($this->getTerminDatumStart());
        $endeTermin  = new DateTime($this->getTerminDatumEnde());

        return $startTermin->diff($endeTermin);
    }
}
