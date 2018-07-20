<?php

namespace App\Traits;

use Image;
use Storage;
use Ramsey\Uuid\Uuid;
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

        if ($this->avatar_picture) {
            $image = asset('/storage/' . $this->avatar_picture);
        }

        if ($this->avatar_thumbnail) {
            $thumb = asset('/storage/' . $this->avatar_thumbnail);
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
        $uuid = Uuid::uuid4()->toString();
        $storage = Storage::disk('public');

        $pictureTarget = sprintf('avatars/%s.picture.jpg', $uuid);
        $thumbTarget = sprintf('avatars/%s.thumb.jpg', $uuid);

        $storage->put($pictureTarget, $picture->fit(512)->stream('jpg', 75));
        $storage->put($thumbTarget, $picture->fit(64)->stream('jpg', 50));

        $this->deletePicture();

        $this->attributes['avatar_picture'] = $pictureTarget;
        $this->attributes['avatar_thumbnail'] = $thumbTarget;
    }

    /**
     * Delete model picture from disk
     */
    public function deletePicture()
    {
        Storage::disk('public')->delete([
            $this->avatar_picture,
            $this->avatar_thumbnail
        ]);
    }
}
