<?php

declare(strict_types=1);

namespace App\Provider;

use App\Model\Media\MediaEntityInterface;

interface MediaStorageProviderInterface
{
    public static function getFilename(MediaEntityInterface $media): string;

    public static function getDirPath(MediaEntityInterface $media): string;

    public static function getFilePath(MediaEntityInterface $media): string;

    public static function createDirPath(MediaEntityInterface $media): static;

    public static function isInStorage(MediaEntityInterface $media): bool;

    public static function isThumbInStorage(MediaEntityInterface $media, int $width): bool;

    public static function isAnImage(MediaEntityInterface $media): bool;

    public static function getThumbname(MediaEntityInterface $media, int $width): string;

    public static function getThumbFilePath(MediaEntityInterface $media, int $width): string;

    public static function getExtension(MediaEntityInterface $media): string;

    public static function isMediaInvalid(?MediaEntityInterface $media, ?bool $isUserLoggedIn = false): bool|string;

    public static function getMediaIdFromUrl(string $url): ?int;
}
