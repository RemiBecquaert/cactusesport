<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/private-liste-user', name: 'liste-user')]
    public function listeUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        if($request->get('id') != null){
            $c = $doctrine->getRepository(User::class)->find($request->get('id'));
            $em->remove($c);
            $em->flush();
            $this->addFlash('notice','Contact supprimé !');

        }
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('base/liste-user.html.twig', ['users' => $users]);
    }
}
