<?php

namespace App\Traits;

use Uuid;
use Image;
use Storage;
use Intervention\Image\Image as ImageObject;
use Symfony\Component\HttpFoundation\File\File;

trait Picture
{
    /**
     * Get default image path when picture is not available
     * 
     * @return string
     */
    protected function getDefaultImage()
    {
        return '/assets/img/default-avatar.png';
    }

    /**
     * Get default thumbnail path when picture is not available
     * 
     * @return string
     */
    protected function getDefaultThumbnail()
    {
        return '/assets/img/default-avatar-thumb.png';
    }
    
    /**
     * Image dimensions in width x height
     * 
     * @return int[]
     */
    protected function getImageDimensions()
    {
        return [512, 512];
    }

    /**
     * Thumbnail dimensions in width x height
     * 
     * @return int[]
     */
    protected function getThumbnailDimensions()
    {
        return [64, 64];
    }

    /**
     * Create thumbnails for images
     * 
     * @return boolean
     */
    protected function isCreateThumbnails()
    {
        return true;
    }

    /**
     * Base filesystem directory
     * 
     * @return string
     */
    protected function getBasePath()
    {
        return 'uploads';
    }

    /**
     * Get picture attribute from model
     * 
     * @return array
     */
    public function getPictureAttribute()
    {
        if ($this->picture_id) {
            return [
                'image' => asset($this->getFileName($this->picture_id, 'image', true)),
                'thumbnail' => asset($this->getFileName($this->picture_id, 'thumbnail', true))
            ];
        }

        return [
            'image' => asset($this->getDefaultImage()),
            'thumbnail' => asset($this->getDefaultThumbnail())
        ];
    }

    /**
     * Set file as picture
     *
     * @param File $file Uploaded file object
     */
    public function setPictureAttribute(File $file)
    {
        $uuid =  (string) Uuid::generate();
        $image = Image::make($file);

        $this->saveImage($uuid, $image, $this->getImageDimensions(), 90, 'image');

        if ($this->isCreateThumbnails()) {
            $this->saveImage($uuid, $image,
                $this->getThumbnailDimensions(), 50, 'thumbnail');
        }

        $this->deletePicture();

        $this->attributes['picture_id'] = $uuid;
    }

    /**
     * Delete model picture from disk
     */
    public function deletePicture()
    {
        $this->getStorage()->delete(
            $this->getFileName($this->picture_id, 'image'));

        if ($this->isCreateThumbnails()) {
            $this->getStorage()->delete(
                $this->getFileName($this->picture_id, 'thumbnail'));
        }
    }

    /**
     * Get storage instance
     * 
     * @return Storage
     */
    protected function getStorage()
    {
        return Storage::disk('public');
    }

    /**
     * Save image to storage
     * 
     * @param  string       $uuid       Image UUID in string
     * @param  ImageManager $image      Image resource
     * @param  int[]        $dimensions Image dimensions in width x height
     * @param  int          $quality    JPEG quality
     * @param  string       $suffix     File name suffix
     */
    protected function saveImage($uuid, $image, $dimensions, $quality, $suffix)
    {
        $target = $this->getFileName($uuid, $suffix);
        
        $image = $this->processImage($image, $dimensions, $quality);

        $this->getStorage()->put($target, $image);
    }

    /**
     * Get/generate filename for UUID
     * 
     * @param  string  $uuid      Image UUID in string
     * @param  string  $suffix    Image suffix
     * @param  boolean $hasPrefix Prepend /storage/ prefix in filename
     * 
     * @return string
     */
    protected function getFileName($uuid, $suffix, $hasPrefix = false)
    {
        $format = '/%s/%s-%s.jpg';

        if ($hasPrefix) {
            $format = '/storage/%s/%s-%s.jpg';
        }

        return sprintf($format, $this->getBasePath(), $uuid, $suffix);
    }

    /**
     * Process image
     * 
     * @param  Image        $image      Image resource
     * @param  int          $dimensions Image dimensions in width x height
     * @param  integer      $quality    JPEG quality
     * 
     * @return ImageManager
     */
    protected function processImage(ImageObject $image, $dimensions, $quality = 75)
    {
        $resource = null;

        if (count($dimensions) == 1) {
            $resource = $image->fit($dimensions[0]);
        }

        if (count($dimensions) == 2) {
            $resource = $image->fit($dimensions[0], $dimensions[1]);
        }

        return $resource->stream('jpg', $quality);
    }
}
