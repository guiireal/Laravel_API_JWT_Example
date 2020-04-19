<?php

namespace App\Support;

class Cropper {

    public static function thumb(string $uri, int $width, int $height = null): ?string {
        $cropper = new \CoffeeCode\Cropper\Cropper('../public/storage/cache');
        $pathThumb =  $cropper->make(config('filesystems.disks.public.root') . "/{$uri}", $width, $height);
        return 'cache/'. collect(explode('/', $pathThumb))->last();
    }

    public static function flush(?string $path): void
    {
        $cropper = new \CoffeeCode\Cropper\Cropper('../public/storage/cache');
        if (!empty($path)) $cropper->flush($path);
        else $cropper->flush();
    }

}
