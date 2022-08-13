<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use App\Entity\Contact;

class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', []);
    }

    #[Route('/rainbow-six', name: 'rainbow-six')]
    public function rainbowSix(): Response
    {
        return $this->render('base/rainbow-six.html.twig', []);
    }

    #[Route('/rocket-league', name: 'rocket-league')]
    public function rocketLeague(): Response
    {
        return $this->render('base/rocket-league.html.twig', []);
    }

    #[Route('/master-duel', name: 'master-duel')]
    public function masterDuel(): Response
    {
        return $this->render('base/master-duel.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, ManagerRegistry $doctrine): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em = $doctrine->getManager();
                $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('esportcactus@outlook.fr')
                ->subject($contact->getObjet())
                ->htmlTemplate('emails/email.html.twig')
                ->context([
                    'nom'=>$contact->getNom(),
                    'objet'=>$contact->getObjet(),
                    'message'=>$contact->getMessage(),
                ]);


                $em->persist($contact);
                $em->flush();

                $mailer->send($email);

                $this->addFlash('notice','Message envoyé !');
                return $this->redirectToRoute('contact');
            }
        }
        return $this->render('base/contact.html.twig', [ 'form' => $form->createView()]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('base/about.html.twig', []);
    }
    
    #[Route('/news', name: 'news')]
    public function news(): Response
    {
        return $this->render('base/news.html.twig', []);
    }
    
    #[Route('/shop', name: 'shop')]
    public function shop(): Response
    {
        return $this->render('base/shop.html.twig', []);
    }    

    #[Route('/liste-contact', name: 'liste-contact')]
    public function listeContact(ManagerRegistry $doctrine, Request $request): Response
    {
        $contact = new Contact();
        $em = $doctrine->getManager();
        if($request->get('id') != null){
            $c = $doctrine->getRepository(Contact::class)->find($request->get('id'));
            $em->remove($c);
            $em->flush();
            $this->addFlash('notice','Contact supprimé !');
        } 
        $contacts = $doctrine->getRepository(Contact::class)->findAll();


        return $this->render('base/liste-contact.html.twig', ['contacts' => $contacts]);
    }

}
