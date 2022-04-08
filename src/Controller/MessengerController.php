<?php

namespace App\Controller;

use App\Message\Fail;
use App\Message\SimpleHelloWorld;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class MessengerController extends AbstractController
{
    private $bus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->bus = $messageBus;
    }

    /**
     * @Route("/messenger", name="messenger_index")
     */
    public function index(Request $request): Response
    {

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, ['label' => 'label.name', 'attr' => ['placeholder' => 'placeholder.name']])
            ->add('send', SubmitType::class, ['label' => 'label.send'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch(new SimpleHelloWorld($form->getData()['name']));
            $this->bus->dispatch(new Fail('Aucune erreur particulière détecté !!'));

            $this->addFlash('success', 'Votre message a été enregistré et sera traité prochainement');
            return $this->redirectToRoute('messenger_index');
        }

        return $this->renderForm('messenger/index.html.twig', [
            'form' => $form
        ]);
    }
}
