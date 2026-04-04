<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    | Ép Laravel phải nạp đúng cái URL từ file .env vào đây
    */
    'cloud_url' => env('CLOUDINARY_URL'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
];