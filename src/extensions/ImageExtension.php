<?php

namespace OliverNorden\Modules\WebP;

use Imagick;
use Exception;
use WebPConvert\WebPConvert;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use SilverStripe\View\ArrayData;

class ImageExtension extends DataExtension {

    public function Picture($image, $class = null) {

        $webpRelative = $this->ConvertToWebP($image);
        return $this->PictureTag($image, $webpRelative, $class);
    }

    public function WebPSrc($image) {
        return $this->ConvertToWebP($image);
    }

    public function ConvertToWebP($image) {
        $source = $this->AssetsPath().$image;
        $destination = $this->DestinationPath($image);
        $options = $this->Options();
        WebPConvert::convert($source, $destination, $options);
        return $webpRelative = $this->RelativeLink($image);
    }

    public function RelativeLink($file) {
        $imagePathNoAssets = str_replace('/assets', '', $file);
        return '/assets/webp'.$imagePathNoAssets.'.webp';
    }

    public function Alt() {
        return $this->owner->Title;
    }

    public function ImageTitle() {
        return $this->owner->Title;
    }

    public function PictureTag($fallback, $webp, $class) {

        $pictureData = new ArrayData([
            'WebPSrc' => $webp,
            'ImageSrc' => $fallback,
            'Class' => $class,
            'Alt' => $this->Alt(),
            'Title' => $this->ImageTitle(),
        ]);
    
        return $pictureData->renderWith('pictureTag');
    }

    public function DestinationPath($image) {
        $imagePathNoAssets = str_replace('/assets', '', $image);
        return ASSETS_PATH.'/webp'.$imagePathNoAssets.'.webp';
    }

    public function AssetsPath() {
        return str_replace("assets", "", ASSETS_PATH);
    }

    public function Options() {
        return [];
    }
}