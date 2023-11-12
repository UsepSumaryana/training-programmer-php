<?php
namespace App\Model;

class Attachment implements AttachmentInteface
{

    const UPLOAD_DIR = '../public/attachments';
    
    public $errMsg;
    public $fileName;
    public function upload($files)
    {

        if (!file_exists(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0777, true);
        }

        $uploadedFile = $files['tmp_name'];
        $originalFileName = $files['name'];

        $uniqueFileName = uniqid() . '_' . $originalFileName;

        $destination = self::UPLOAD_DIR .'/'. $uniqueFileName;

        if (move_uploaded_file($uploadedFile, $destination)) {
            $this->fileName = $uniqueFileName;
            return true;
        } else {
            return false;
        }
    }

    public function validateFile($file)
    {
        $maxFileSize = 5242880; // Contoh: 5 MB
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
        $fileName = $file['name'];
        $fileSize = $file['size'];
    
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
        if ($fileSize > $maxFileSize) {

            $this->errMsg = "Ukuran file terlalu besar. Maksimal $maxFileSize byte.";
            return false;
        }
    
        if (!in_array($fileExtension, $allowedExtensions)) {

            $this->errMsg = "Ekstensi file tidak diizinkan. Hanya file gambar (jpg, jpeg, png, gif) yang diizinkan.";
            return false;
        }
        
        return true;// File valid
    }

    public function deleteFile($fileName)
    {
        $filePath = self::UPLOAD_DIR.'/'.$fileName;
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return "File berhasil dihapus.";
            } else {
                return "Gagal menghapus file.";
            }
        } else {
            return "File tidak ditemukan.";
        }
    }
    
}
