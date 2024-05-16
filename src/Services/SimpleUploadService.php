<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleUploadService
{
    // accès au services.yaml via un constructeur
    public function __construct(private ParameterBagInterface $params)
    {
    }

    public function upload(UploadedFile $file)
    {
        $original_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $new_file_name = $original_file_name . '-' . uniqid() . '.' . $file->guessExtension();
        $path_destination = $this->params->get('images_directory');
        $file->move($path_destination, $new_file_name);

        return $new_file_name;
    }
}
