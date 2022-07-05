<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function contact(): Response
    {
        return $this->render('base/contact.html.twig', []);
    }
}
