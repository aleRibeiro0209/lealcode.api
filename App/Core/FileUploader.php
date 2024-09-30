<?php

namespace App\Core;

class FileUploader {

    protected $uploadDir;

    public function __construct($uploadDir = '') {
        $uploadDiret = 'uploads/' . $uploadDir;
        $this->uploadDir = $uploadDiret;

        // Cria o diretório se ele não existir
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function uploadBase64File($base64File, $fileName, $fileType){
        // Decodifica o arquivo base64
        $fileData = base64_decode($base64File);

        // Define a extensão
        $extension = explode('/', $fileType)[1];

        // Define o caminho completo onde o arquivo será salvo
        $filePath = $this->uploadDir . md5($fileName . strtotime("now")) . "." . $extension;

        // Salva o arquivo no servidor
        if (file_put_contents($filePath, $fileData)) {
            return $filePath;
        }

        return false; // Retorna false se o upload falhar
    }

    public function getUploadDir(){
        return $this->uploadDir;
    }
}