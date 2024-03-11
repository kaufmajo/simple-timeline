<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\Entity\AbstractEntity;

class MediaEntity extends AbstractEntity implements MediaEntityInterface
{
    protected ?int $media_id = null;

    protected ?int $media_parent_id = null;

    protected ?int $media_groesse = null;

    protected ?string $media_mimetype = null;

    protected ?string $media_name = null;

    protected ?string $media_anzeige = null;

    protected ?string $media_von = null;

    protected ?string $media_bis = null;

    protected ?string $media_hash = null;

    protected ?string $media_tag = null;

    protected ?int $media_privat = 0;

    public function getArrayCopy(): array
    {
        $data = [];

        $this->getExchangeValue($data, 'media_id');
        $this->getExchangeValue($data, 'media_parent_id');
        $this->getExchangeValue($data, 'media_groesse');
        $this->getExchangeValue($data, 'media_mimetype');
        $this->getExchangeValue($data, 'media_name');
        $this->getExchangeValue($data, 'media_anzeige');
        $this->getExchangeValue($data, 'media_von');
        $this->getExchangeValue($data, 'media_bis');
        $this->getExchangeValue($data, 'media_hash');
        $this->getExchangeValue($data, 'media_tag');
        $this->getExchangeValue($data, 'media_privat');

        return $data;
    }

    public function exchangeArray(array $data): static
    {
        $this->setExchangeValue($data, 'media_id');
        $this->setExchangeValue($data, 'media_parent_id');
        $this->setExchangeValue($data, 'media_groesse');
        $this->setExchangeValue($data, 'media_mimetype');
        $this->setExchangeValue($data, 'media_name');
        $this->setExchangeValue($data, 'media_anzeige');
        $this->setExchangeValue($data, 'media_von');
        $this->setExchangeValue($data, 'media_bis');
        $this->setExchangeValue($data, 'media_hash');
        $this->setExchangeValue($data, 'media_tag');
        $this->setExchangeValue($data, 'media_privat');

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->media_id;
    }

    public function setEntityId(?int $entityId): static
    {
        $this->media_id = $entityId;

        return $this;
    }

    public function getMediaId(): ?int
    {
        return $this->media_id;
    }

    public function setMediaId(?int $value)
    {
        $this->media_id = $value;
    }

    public function getMediaParentId(): ?int
    {
        return $this->media_parent_id;
    }

    public function setMediaParentId(?int $value)
    {
        $this->media_parent_id = $value;
    }

    public function getMediaGroesse(): ?int
    {
        return $this->media_groesse;
    }

    public function setMediaGroesse(?int $value)
    {
        $this->media_groesse = $value;
    }

    public function getMediaMimetype(): ?string
    {
        return $this->media_mimetype;
    }

    public function setMediaMimetype(?string $value)
    {
        $this->media_mimetype = $value;
    }

    public function getMediaName(): ?string
    {
        return $this->media_name;
    }

    public function setMediaName(?string $value)
    {
        $this->media_name = $value;
    }

    public function getMediaAnzeige(): ?string
    {
        return $this->media_anzeige;
    }

    public function setMediaAnzeige(?string $value)
    {
        $this->media_anzeige = $value;
    }

    public function getMediaVon(): ?string
    {
        return $this->media_von;
    }

    public function setMediaVon(?string $value)
    {
        $this->media_von = $value;
    }

    public function getMediaBis(): ?string
    {
        return $this->media_bis;
    }

    public function setMediaBis(?string $value)
    {
        $this->media_bis = $value;
    }

    public function getMediaHash(): ?string
    {
        return $this->media_hash;
    }

    public function setMediaHash(?string $value)
    {
        $this->media_hash = $value;
    }

    public function getMediaTag(): ?string
    {
        return $this->media_tag;
    }

    public function setMediaTag(?string $value)
    {
        $this->media_tag = $value;
    }

    public function getMediaPrivat(): ?int
    {
        return $this->media_privat;
    }

    public function setMediaPrivat(?int $value)
    {
        $this->media_privat = $value;
    }

    public function getEntityLabel(): array
    {
        $return            = [];
        $return['Name']    = $this->media_name;
        $return['Anzeige'] = $this->media_anzeige;

        return $return;
    }
}
