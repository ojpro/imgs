<?php

namespace App\Traits;

use Carbon\Carbon;

class SaveImage
{
    // Directory Where all images upload
    protected static $upload_directory = "images";

    // Current User ID
    protected static $user_id = 1;

    public static function save($image)
    {
        // Store current time
        $date = new Carbon();

        // Get image extension
        $image_extension = $image->extension();

        // Set image name
        $image_full_name = 'IMG_' . $date->timestamp . '.' . $image_extension;

        // Set storing directory
        $realtime_upload_directory = static::$upload_directory . '/' . static::$user_id . '/' . $date->year . '-' . $date->month;

        // Then store the image
        $path = $image->move($realtime_upload_directory, $image_full_name);

        // And Return it's path
        return $path;
    }
}
