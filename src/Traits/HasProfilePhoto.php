<?php

namespace HasinHayder\TyroDashboard\Traits;

use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto($photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $path = $this->processAndStorePhoto($photo);

            $this->forceFill([
                'profile_photo_path' => $path,
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Process the photo and store it.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return string
     */
    protected function processAndStorePhoto($photo)
    {
        $width = config('tyro-dashboard.profile_photo.width', 400);
        $height = config('tyro-dashboard.profile_photo.height', 400);
        $quality = config('tyro-dashboard.profile_photo.quality', 90);

        if (extension_loaded('gd') && $resizedContent = $this->resizeImage($photo, $width, $height, $quality)) {
            $filename = $photo->hashName();
            $directory = config('tyro-dashboard.profile_photo.directory', 'profile-photos');
            $path = $directory.'/'.$filename;

            Storage::disk($this->profilePhotoDisk())->put($path, $resizedContent, 'public');

            return $path;
        }

        return $photo->storePublicly(
            config('tyro-dashboard.profile_photo.directory', 'profile-photos'),
            ['disk' => $this->profilePhotoDisk()]
        );
    }

    /**
     * Resize the given image to the given dimensions and return as strong.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @param  int  $width
     * @param  int  $height
     * @param  int  $quality
     * @return string|null
     */
    protected function resizeImage($photo, $width, $height, $quality)
    {
        $imageInfo = getimagesize($photo->getRealPath());
        if (! $imageInfo) {
            return null;
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($photo->getRealPath());

                // Fix orientation if EXIF is available
                if (function_exists('exif_read_data')) {
                    try {
                        $exif = @exif_read_data($photo->getRealPath());
                        if ($exif && ! empty($exif['Orientation'])) {
                            switch ($exif['Orientation']) {
                                case 3:
                                    $sourceImage = imagerotate($sourceImage, 180, 0);
                                    break;
                                case 6:
                                    $sourceImage = imagerotate($sourceImage, -90, 0);
                                    // Swap dimensions for calculations
                                    $temp = $originalWidth;
                                    $originalWidth = $originalHeight;
                                    $originalHeight = $temp;
                                    break;
                                case 8:
                                    $sourceImage = imagerotate($sourceImage, 90, 0);
                                    // Swap dimensions for calculations
                                    $temp = $originalWidth;
                                    $originalWidth = $originalHeight;
                                    $originalHeight = $temp;
                                    break;
                            }
                        }
                    } catch (\Exception $e) {
                        // Ignore EXIF errors
                    }
                }
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($photo->getRealPath());
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($photo->getRealPath());
                break;
            default:
                return null;
        }

        if (! $sourceImage) {
            return null;
        }

        // Calculate aspect ratios
        $originalAspect = $originalWidth / $originalHeight;
        $targetAspect = $width / $height;

        $cropPosition = config('tyro-dashboard.profile_photo.crop_position', 'center');

        if ($originalAspect >= $targetAspect) {
            // Original is wider
            $newHeight = $originalHeight;
            $newWidth = $originalHeight * $targetAspect;
            $srcY = 0;

            switch ($cropPosition) {
                case 'top': // Left-most for wider images
                    $srcX = 0;
                    break;
                case 'bottom': // Right-most for wider images
                    $srcX = $originalWidth - $newWidth;
                    break;
                case 'center':
                default:
                    $srcX = ($originalWidth - $newWidth) / 2;
                    break;
            }
        } else {
            // Original is taller
            $newWidth = $originalWidth;
            $newHeight = $originalWidth / $targetAspect;
            $srcX = 0;

            switch ($cropPosition) {
                case 'top':
                    $srcY = 0;
                    break;
                case 'bottom':
                    $srcY = $originalHeight - $newHeight;
                    break;
                case 'center':
                default:
                    $srcY = ($originalHeight - $newHeight) / 2;
                    break;
            }
        }

        $targetImage = imagecreatetruecolor($width, $height);

        // Handle transparency for PNG/WebP
        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_WEBP) {
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
        }

        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0,
            $srcX, $srcY,
            $width, $height,
            $newWidth, $newHeight
        );

        ob_start();
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($targetImage, null, $quality);
                break;
            case IMAGETYPE_PNG:
                // Quality for PNG is 0-9 (0 is no compression)
                $pngQuality = (int) round((100 - $quality) / 10);
                imagepng($targetImage, null, $pngQuality);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($targetImage, null, $quality);
                break;
        }
        $content = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return $content;
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (is_null($this->profile_photo_path)) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->use_gravatar && $this->email) {
            return $this->gravatar_url;
        }

        return $this->profile_photo_path
                    ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
                    : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim($this->name);

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the Gravatar URL for the user.
     *
     * @return string
     */
    public function getGravatarUrlAttribute()
    {
        $hash = md5(strtolower(trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}?s=200&d=mp";
    }

    /**
     * Determine if the user model has the profile photo path column.
     *
     * @return bool
     */
    public function hasProfilePhotoColumn()
    {
        return \Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'profile_photo_path');
    }

    /**
     * Determine if the user model has the use gravatar column.
     *
     * @return bool
     */
    public function hasGravatarColumn()
    {
        return \Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'use_gravatar');
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return config('tyro-dashboard.profile_photo.disk', 'public');
    }
}
