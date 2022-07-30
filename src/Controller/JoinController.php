<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoinController extends AbstractController
{
    #[Route('/join', name: 'join')]
    public function join(): Response
    {
        return $this->render('join/join.html.twig', []);
    }
}
