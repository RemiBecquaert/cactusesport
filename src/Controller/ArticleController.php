<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Images;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;


class ArticleController extends AbstractController
{
    #[Route('/news', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/news.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/private-new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Images();
                $img->setName($fichier);
                $article->setDatePublication(new \Datetime());
                $article->addImage($img);
            }
            $em = $doctrine->getManager();
            $em->persist($article);
            $em->persist($img);
            $em->flush();
            $this->addFlash('notice','Article créé !');

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/news-create.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }    
    
    #[Route('/article', name: 'article')]
    public function article(ManagerRegistry $doctrine, Request $request): Response
    {
        if (isset($_GET['id'])){
            $em = $doctrine->getManager();
            $cetArticle = $doctrine->getRepository(Article::class)->find($_GET['id']);
        } else{
            $this->addFlash('notice','Aucun article n\'est spécifié !');
        }
        return $this->render('article/article.html.twig', ['article'=>$cetArticle]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
}
