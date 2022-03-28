<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    /**
     * @Route("/cache", name="app_cache")
     */
    public function index(): Response
    {
        return $this->render('cache/index.html.twig', [
            'controller_name' => 'CacheController',
        ]);
    }
}
