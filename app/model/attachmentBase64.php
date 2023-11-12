<?php

namespace App\Model;

use App\Model\Attachment;
use finfo;

class AttachmentBase64 extends Attachment
{
    public function upload($base64)
    {
        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
        $decoded = base64_decode($base64);

        $uniqueFileName = uniqid() . '.jpg';

        $destination = parent::UPLOAD_DIR . '/' . $uniqueFileName;

        if (file_put_contents($destination, $decoded)) {
            $this->fileName = $uniqueFileName;
            return true;
        } else {
            return false;
        }
    }

    public function validateFile($base64)
    {
        $maxFileSize = 5242880;
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (strlen(base64_decode($base64)) > $maxFileSize) {
            $this->errMsg = "Ukuran file terlalu besar. Maksimal $maxFileSize byte.";
            return false;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer(base64_decode($base64));
    
        $ext = explode('/', $mime_type)[1];

        if (!in_array($ext, $allowedExtensions)) {

            $this->errMsg = "Ekstensi file tidak diizinkan. Hanya file gambar (jpg, jpeg, png, gif) yang diizinkan.";
            return false;
        }

        return true;
    }
}
