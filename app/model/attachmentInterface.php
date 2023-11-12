<?php
namespace App\Model;

interface AttachmentInteface {
    public function upload($file);

    public function validateFile($file);
}
