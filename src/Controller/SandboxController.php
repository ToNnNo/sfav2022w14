<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SandboxController extends AbstractController
{
    /**
     * @Route("/sandbox", name="sandbox_index")
     */
    public function index(Filesystem $filesystem, string $translationDirectory): Response
    {
        $finder = new Finder();
        dump($translationDirectory);
        foreach ($finder->files()->in($translationDirectory) as $file) {
            dump($file);
        }


        return $this->render('sandbox/index.html.twig', [

        ]);
    }
}
