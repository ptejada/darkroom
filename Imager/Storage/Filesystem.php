<?php

namespace Imager\Storage;

use Imager\Image;

class Filesystem
{
    public function saveImageSnapshot(Image $image)
    {
        $directory = $image->file()->directory() . DIRECTORY_SEPARATOR . $image->file()->name();
        $filename  = (new \DateTime())->format('Y-m-d-Hisu') . '.' . $image->file()->extension();

        $filePath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (!is_dir($directory)) {
            mkdir($directory, '0777', true);
        }

        if ($image->renderTo($filePath)) {
            return new ImageReference($filePath);
        }

        // Todo: throw Exception
    }
}
