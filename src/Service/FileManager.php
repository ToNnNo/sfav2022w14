<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $filesystem;
    private $publicDirectory;

    private $directory;
    private $existingImage = null;

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

        if( $this->hasExistingImage() ) {
            $this->removeExistingImage();
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

    public function setExistingImage(?string $image): self
    {
        if(null !== $image) {
            $this->existingImage = $image;
        }

        return $this;
    }

    public function hasExistingImage(): bool
    {
        return $this->existingImage !== null;
    }

    public function removeExistingImage(): self
    {
        $image = $this->publicDirectory. DIRECTORY_SEPARATOR . $this->directory . $this->existingImage;
        if($this->filesystem->exists($image)) {
            $this->filesystem->remove($image);
        }

        return $this;
    }
}
