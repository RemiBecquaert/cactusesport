<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Sujet;
use App\Form\SujetType;

class SujetController extends AbstractController
{
    #[Route('/private-control-sujet', name: 'app_create_sujet')]
    public function createSujet(ManagerRegistry $doctrine, Request $request): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetType::class, $sujet);
        $em = $doctrine->getManager();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $em->persist($sujet);
                $em->flush();
                $this->addFlash('notice','Un sujet d\'article a été ajoutée !');
                return $this->redirectToRoute('app_create_sujet');
            }
        }

        if($request->get('id') != null){
            $leSujet = $doctrine->getRepository(Sujet::class)->find($request->get('id'));
            $em->remove($leSujet);
            $em->flush();
            $this->addFlash('danger','Sujet supprimé !');
            return $this->redirectToRoute('app_create_sujet');
        }

        $repoSujet = $doctrine->getRepository(Sujet::class);
        $sujets = $repoSujet->findAll();

        return $this->render('sujet/sujet.html.twig', ['sujets'=>$sujets, 'form'=>$form->createView()        
        ]);
    }
}
