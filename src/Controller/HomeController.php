<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Flasher\Prime\FlasherInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', options: ['sitemap' => true])]
    public function index(Request $request, MailerInterface $mailer, FlasherInterface $flasher): Response
    {
        $contact = [];
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();
            
            $email = (new TemplatedEmail())
                ->from('no-reply@mywebcreation.be')
                ->to(new Address('xribant@gmail.com'))
                ->subject('mywebcreation.be - Formulaire de contact')
                ->htmlTemplate('documents/contact_form_email.html.twig')
                ->context([
                    'contact' => $contact
                ])
            ;

            try {
                $mailer->send($email);
                
                $flasher
                    ->options([
                        'timeout' => 5000,
                        'position' => 'top-center'
                    ])
                    ->addSuccess('<strong>Message reçu! <br>Nous y répondrons dans les meilleurs délais.</strong>')
                ;

                return $this->redirectToRoute('app_home');

            } catch (TransportExceptionInterface $e) {
                $flasher
                    ->options([
                        'timeout' => 5000,
                        'position' => 'top-center'
                    ])
                    ->addDanger('<strong>'.$e->getMessage().'</strong>')
                ;
            }

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('home/index.html.twig', [
            'active_menu' => 'home',
            'form' => $form
        ]);
    }
}
