<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response // Request n'est pas un service
    {

        return $this->render('event/index.html.twig', [
        ]);
    }
}
