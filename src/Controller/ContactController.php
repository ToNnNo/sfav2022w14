<?php

namespace App\Controller;

use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_index")
     */
    public function index(Request $request, MailerInterface $mailer, LoggerInterface $logger, string $publicDirectory): Response
    {
        $message = <<<EOF
Bonjour,

Pouvez me faire parvenir des informations complémentaires concernant les articles de votre applications ?

D'avance merci 
John Doe
EOF;

        $data = ['name' => 'John doe', 'mail' => 'john.doe@gmail.com', 'message' => $message];
        $document = $publicDirectory."/documents/facture-001.pdf";
        $form = $this->createForm(ContactType::class, $data);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new TemplatedEmail())
                ->from(new Address($data['mail'], $data['name']))
                ->to('smenut@dawan.fr')
                ->subject("Demande d'information - App Yona")
                ->textTemplate('email/contact.txt.twig')
                ->htmlTemplate('email/contact.html.twig')
                ->context($data)
                ->attachFromPath($document)
                // ->embedFromPath($publicDirectory.'/email/images/symfony-logo.png', 'logo')
            ;

            try {
                $mailer->send($email);
                $logger->info("Email envoyé: ".implode("; ", $data));
            } catch (TransportExceptionInterface $e) {
                $logger->error("Formulaire de contact: ".$e->getMessage());
            }

            return $this->redirectToRoute('contact_index');
        }

        /**
         * en JS
         * let user = "john";
         * let obj = { user }; // { user: user }
         * En PHP
         * compact('value'); // ['value' => $value]
         */
        return $this->renderForm('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
