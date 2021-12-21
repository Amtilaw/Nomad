<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

class FileUploader extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function upload($uploadDir, $file, $filename)
    {
        try {

            $file->move($uploadDir, $filename);
        } catch (FileException $e) {

            $this->logger->error('failed to upload image: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }
}
