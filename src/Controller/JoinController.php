<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\JoinType;
use App\Entity\Candidature;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class JoinController extends AbstractController
{
    #[Route('/join', name: 'join')]
    public function join(Request $request, ManagerRegistry $doctrine): Response
    {
        $candidature = new Candidature();
        $form = $this->createForm(JoinType::class, $candidature);

        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em = $doctrine->getManager();
                $candidature->setDateEnvoi(new \Datetime());
                $em->persist($candidature);
                $em->flush();

                $this->addFlash('notice','Candidature envoyée !');
                return $this->redirectToRoute('join');
            }
        }

        return $this->render('join/join.html.twig', ['form' => $form->createView()]);
    }
}
