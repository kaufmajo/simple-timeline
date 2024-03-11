<?php

declare(strict_types=1);

namespace App\Service;

use function call_user_func;
use function exif_imagetype;
use function floor;
use function imagealphablending;
use function imagecolorallocate;
use function imagecolortransparent;
use function imagecopyresampled;
use function imagecreatetruecolor;
use function imagesavealpha;
use function imagesx;
use function imagesy;

use const IMAGETYPE_GIF;
use const IMAGETYPE_JPEG;
use const IMAGETYPE_PNG;

class ThumbService
{
    // Link image type to correct image loader and saver
    // - makes it easier to add additional types later on
    // - makes the function easier to read
    const IMAGE_HANDLERS = [
        IMAGETYPE_JPEG => [
            'load'    => 'imagecreatefromjpeg',
            'save'    => 'imagejpeg',
            'quality' => 75, // ist optional und es kann ein Wert zwischen 0 (schlechteste Qualität, kleine Datei) und 100 (beste Qualität, größte Datei) übergeben werden. Der Standardwert (-1) verwendet den standardmäßigen IJG-Qualitätswert (ungefähr 75).
        ],
        IMAGETYPE_PNG  => [
            'load'    => 'imagecreatefrompng',
            'save'    => 'imagepng',
            'quality' => 6, // Kompressionsstufe: Von 0 (keine Kompression) bis 9. Die Voreinstellung (-1) verwendet die voreingestellte zlib Komprimierung. Die derzeitige Voreinstellung ist 6.
        ],
        IMAGETYPE_GIF  => [
            'load' => 'imagecreatefromgif',
            'save' => 'imagegif',
        ],
    ];

    /**
     * @param $src - a valid file location
     * @param $dest - a valid file target
     * @param $targetWidth - desired output width
     * @param $targetHeight - desired output height or null
     */
    public static function createThumbnail(string $src, string $dest, int $targetWidth, ?int $targetHeight = null)
    {
        // 1. Load the image from the given $src
        // - see if the file actually exists
        // - check if it's of a valid image type
        // - load the image resource

        // get the type of the image
        // we need the type to determine the correct loader
        $type = exif_imagetype($src);

        // if no valid type or no handler found -> exit
        if (! $type || ! self::IMAGE_HANDLERS[$type]) {
            return null;
        }

        // load the image with the correct loader
        $image = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $src);

        // no image found at supplied location -> exit
        if (! $image) {
            return null;
        }

        // 2. Create a thumbnail and resize the loaded $image
        // - get the image dimensions
        // - define the output size appropriately
        // - create a thumbnail based on that size
        // - set alpha transparency for GIFs and PNGs
        // - draw the final thumbnail

        // get original image width and height
        $width  = imagesx($image);
        $height = imagesy($image);

        // maintain aspect ratio when no height set
        if ($targetHeight == null) {
            // get width to height ratio
            $ratio = $width / $height;

            // if is portrait
            // use ratio to scale height to fit in square
            if ($width > $height) {
                $targetHeight = (int) floor($targetWidth / $ratio);
            }
            // if is landscape
            // use ratio to scale width to fit in square
            else {
                $targetHeight = $targetWidth;
                $targetWidth  = (int) floor($targetWidth * $ratio);
            }
        }

        // create duplicate image based on calculated target size
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

        // set transparency options for GIFs and PNGs
        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {
            // make image transparent
            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );

            // additional settings for PNGs
            if ($type == IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        // copy entire source image to duplicate image and resize
        imagecopyresampled(
            $thumbnail,
            $image,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $width,
            $height
        );

        // 3. Save the $thumbnail to disk
        // - call the correct save method
        // - set the correct quality level

        // save the duplicate version of the image to disk
        return call_user_func(
            self::IMAGE_HANDLERS[$type]['save'],
            $thumbnail,
            $dest,
            self::IMAGE_HANDLERS[$type]['quality']
        );
    }
}
