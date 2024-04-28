<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\Entity\EntityInterface;

interface MediaEntityInterface extends EntityInterface
{
    public function getMediaId(): ?int;

    public function setMediaId(?int $value);

    public function getMediaParentId(): ?int;

    public function setMediaParentId(?int $value);

    public function getMediaGroesse(): ?int;

    public function setMediaGroesse(?int $value);

    public function getMediaMimetype(): ?string;

    public function setMediaMimetype(?string $value);

    public function getMediaName(): ?string;

    public function setMediaName(?string $value);

    public function getMediaAnzeige(): ?string;

    public function setMediaAnzeige(?string $value);

    public function getMediaVon(): ?string;

    public function setMediaVon(?string $value);

    public function getMediaBis(): ?string;

    public function setMediaBis(?string $value);

    public function getMediaHash(): ?string;

    public function setMediaHash(?string $value);

    public function getMediaTag(): ?string;

    public function setMediaTag(?string $value);

    public function getMediaPrivat(): ?int;

    public function setMediaPrivat(?int $value);

    public function getEntityLabel(): array;
}
