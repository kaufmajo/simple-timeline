<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\Entity\EntityInterface;
use DateInterval;

interface TerminEntityInterface extends EntityInterface
{
    public function getTerminId(): ?int;

    public function setTerminId(?int $value);

    public function getTerminStatus(): ?string;

    public function setTerminStatus(?string $value);

    public function getTerminDatumStart(): ?string;

    public function setTerminDatumStart(?string $value);

    public function getTerminDatumEnde(): ?string;

    public function setTerminDatumEnde(?string $value);

    public function getTerminZeitStart(): ?string;

    public function setTerminZeitStart(?string $value);

    public function getTerminZeitEnde(): ?string;

    public function setTerminZeitEnde(?string $value);

    public function getTerminZeitGanztags(): ?int;

    public function setTerminZeitGanztags(?int $value);

    public function getTerminBetreff(): ?string;

    public function setTerminBetreff(?string $value);

    public function getTerminText(): ?string;

    public function setTerminText(?string $value);

    public function getTerminKategorie(): ?string;

    public function setTerminKategorie(?string $value);

    public function getTerminMitvon(): ?string;

    public function setTerminMitvon(?string $value);

    public function getTerminImage(): ?string;

    public function setTerminImage(?string $value);

    public function getTerminLink(): ?string;

    public function setTerminLink(?string $value);

    public function getTerminLinkTitel(): ?string;

    public function setTerminLinkTitel(?string $value);

    public function getTerminLink2(): ?string;

    public function setTerminLink2(?string $value);

    public function getTerminLink2Titel(): ?string;

    public function setTerminLink2Titel(?string $value);

    public function getTerminSerieIntervall(): ?string;

    public function setTerminSerieIntervall(?string $value);

    public function getTerminSerieWiederholung(): ?string;

    public function setTerminSerieWiederholung(?string $value);

    public function getTerminSerieEnde(): ?string;

    public function setTerminSerieEnde(?string $value);

    public function getTerminIstGeloescht(): ?int;

    public function setTerminIstGeloescht(?int $value);

    public function getTerminZeigeKonflikt(): ?int;

    public function setTerminZeigeKonflikt(?int $value);

    public function getTerminAktiviereDrucken(): ?int;

    public function setTerminAktiviereDrucken(?int $value);

    public function getTerminAnsicht(): ?string;

    public function setTerminAnsicht(?string $value);

    public function getTerminIstKonfliktrelevant(): ?int;

    public function setTerminIstKonfliktsrelevant(?int $value);

    public function getTerminZeigeEinmalig(): ?int;

    public function setTerminZeigeEinmalig(?int $value);

    public function getTerminZeigeTagezuvor(): ?int;

    public function setTerminZeigeTagezuvor(?int $value);

    public function getTerminNotiz(): ?string;

    public function setTerminNotiz(?string $value);

    public function isSerie(): bool;

    public function getIntervalDifference(): DateInterval;

    public function getEntityLabel();
}
