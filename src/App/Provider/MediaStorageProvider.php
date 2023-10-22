<?php

declare(strict_types=1);

namespace App\Provider;

use App\Model\Media\MediaEntityInterface;
use DateTime;
use Exception;
use finfo;

use function file_exists;
use function getcwd;
use function is_dir;
use function is_file;
use function mkdir;
use function pathinfo;
use function preg_match;
use function substr;

use const DIRECTORY_SEPARATOR;
use const FILEINFO_MIME_TYPE;
use const PATHINFO_EXTENSION;

class MediaStorageProvider implements MediaStorageProviderInterface
{
    /**
     * @throws Exception
     */
    public static function getFilename(MediaEntityInterface $media): string
    {
        if (null === $media->getMediaId()) {
            throw new Exception('media_id is required.');
        }

        $filename = 'media_' . $media->getMediaId();

        $pathinfo = pathinfo($media->getMediaName());

        return isset($pathinfo['extension']) ? $filename . '.' . $pathinfo['extension'] : $filename;
    }

    /**
     * @throws Exception
     */
    public static function getDirPath(MediaEntityInterface $media): string
    {
        if (null === $media->getMediaHash()) {
            throw new Exception('media_hash is required.');
        }

        $path  = DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR;
        $path .= substr($media->getMediaHash(), 0, 2) . DIRECTORY_SEPARATOR;
        $path .= substr($media->getMediaHash(), 2, 2) . DIRECTORY_SEPARATOR;

        return getcwd() . $path;
    }

    /**
     * @throws Exception
     */
    public static function getFilePath(MediaEntityInterface $media): string
    {
        return self::getDirPath($media) . self::getFilename($media);
    }

    /**
     * @throws Exception
     */
    public static function createDirPath(MediaEntityInterface $media): static
    {
        $path = self::getDirPath($media);

        if (! is_dir($path) && ! file_exists($path)) {
            mkdir($path, 0755, true);
        }

        return new static();
    }

    public static function isInStorage(MediaEntityInterface $media): bool
    {
        try {
            return is_file(self::getFilePath($media));
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public static function isThumbInStorage(MediaEntityInterface $media, int $width): bool
    {
        return is_file(self::getThumbFilePath($media, $width));
    }

    /**
     * @throws Exception
     */
    public static function isAnImage(MediaEntityInterface $media): bool
    {
        // init
        $path = self::getFilePath($media);

        // process
        if (is_file($path)) {
            $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($path);

            return match ($mimeType) {
                'image/gif', 'image/jpg', 'image/jpeg', 'image/png' => true,
                default => false,
            };
        } else {
            $pathinfo = pathinfo($media->getMediaName());

            return isset($pathinfo['extension']) && match ($pathinfo['extension']) {
                    'gif', 'jpg', 'jpeg', 'png' => true,
                    default => false,
            };
        }
    }

    /**
     * @throws Exception
     */
    public static function getThumbname(MediaEntityInterface $media, int $width): string
    {
        return 'thumb_w' . $width . '_' . self::getFilename($media);
    }

    /**
     * @throws Exception
     */
    public static function getThumbFilePath(MediaEntityInterface $media, int $width): string
    {
        return self::getDirPath($media) . self::getThumbname($media, $width);
    }

    public static function getExtension(MediaEntityInterface $media): string
    {
        return pathinfo($media->getMediaName(), PATHINFO_EXTENSION);
    }

    /**
     * @throws Exception
     */
    public static function isMediaInvalid(?MediaEntityInterface $media, ?bool $isUserLoggedIn = false): bool|string
    {
        if (! $media) {
            return 'Media is invalid.';
        } elseif ($media->getMediaPrivat() && ! $isUserLoggedIn) {
            return 'Media is not public.';
        } elseif ($media->getMediaParentId() && ! $isUserLoggedIn) {
            return 'Media is a version.';
        } elseif (
            (new DateTime($media->getMediaVon()) > new DateTime("midnight") || new DateTime($media->getMediaBis()) < new DateTime("midnight")) &&
            ! $isUserLoggedIn
        ) {
            return 'Media is not published.';
        } elseif (! file_exists(self::getFilePath($media))) {
            return 'Media data is missing.';
        }

        return false;
    }

    public static function getMediaIdFromUrl(string $url): ?int
    {
        if (preg_match('/^\/media\/([0-9]+)$/', $url, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}
