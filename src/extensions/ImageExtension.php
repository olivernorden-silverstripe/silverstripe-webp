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
        $source = $this->AssetsPath().$image;
        $destination = $this->DestinationPath($image);
        $options = $this->Options();
        WebPConvert::convert($source, $destination, $options);
        $webpRelative = $this->RelativeLink($destination);

        return $this->PictureTag($image, $webpRelative, $class);
    }

    public function RelativeLink($file) {
        return 'assets/'.$file;
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
        return ASSETS_PATH.'/webp/'.$imagePathNoAssets.'.webp';
    }

    public function AssetsPath() {
        return str_replace("assets", "", ASSETS_PATH);
    }

    public function Options() {
        return [];
    }
}