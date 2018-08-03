<?php

namespace App\Traits;

use Uuid;
use Image;
use Storage;
use Symfony\Component\HttpFoundation\File\File;

trait Avatar
{
    /**
     * Get picture attribute from model
     * 
     * @return array
     */
    public function getPictureAttribute()
    {
        $image = asset('/assets/img/default-avatar.png');
        $thumb = asset('/assets/img/default-avatar-thumb.png');

        if ($this->avatar_id) {
            $image = asset("/storage/avatars/{$this->avatar_id}.picture.jpg");
            $thumb = asset("/storage/avatars/{$this->avatar_id}.thumb.jpg");
        }

        return [
            'image' => $image,
            'thumb' => $thumb
        ];
    }

    /**
     * Set file as picture
     */
    public function setPictureAttribute(File $file)
    {
        $picture = Image::make($file);
        $uuid =  (string) Uuid::generate();
        $storage = Storage::disk('public');

        $pictureTarget = sprintf('avatars/%s.picture.jpg', $uuid);
        $thumbTarget = sprintf('avatars/%s.thumb.jpg', $uuid);

        $storage->put($pictureTarget, $picture->fit(512)->stream('jpg', 75));
        $storage->put($thumbTarget, $picture->fit(64)->stream('jpg', 50));

        $this->deletePicture();

        $this->attributes['avatar_id'] = $uuid;
    }

    /**
     * Delete model picture from disk
     */
    public function deletePicture()
    {
        Storage::disk('public')->delete([
            "avatars/{$this->avatar_id}.picture.jpg",
            "avatars/{$this->avatar_id}.thumb.jpg"
        ]);
    }
}
