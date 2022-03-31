<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $directory;
    private $filesystem;
    private $publicDirectory;

    public function __construct(Filesystem $filesystem, string $publicDirectory)
    {
        $this->filesystem = $filesystem;
        $this->publicDirectory = $publicDirectory;
    }

    public function uploadFile(UploadedFile $file): string
    {
        if( null === $this->directory ) {
            throw new IOException('The directory does not be empty');
        }

        $absolutePathDirectory = $this->publicDirectory. DIRECTORY_SEPARATOR . $this->directory;

        if( !$this->filesystem->exists($this->directory) ) {
            $this->filesystem->mkdir($absolutePathDirectory);
        }

        $date = new \DateTime();
        $datetimestring = $date->format('YmdHis');
        $name = $datetimestring . $file->getClientOriginalName();

        $file->move($absolutePathDirectory, $name);

        return $name;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

}
