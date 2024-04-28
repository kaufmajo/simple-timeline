<?php

declare(strict_types=1);

namespace App\Model\Media;

use Laminas\Diactoros\UploadedFile;

interface MediaCommandInterface
{
    public function insertMedia(MediaEntityInterface $mediaEntity): int;

    public function updateMedia(MediaEntityInterface $mediaEntity): int;

    public function deleteMedia(MediaEntityInterface $mediaEntity): int;

    public function saveMedia(MediaEntityInterface $mediaEntity): int;

    public function storeMedia(MediaEntityInterface $mediaEntity, UploadedFile $uploadedFile);
}
