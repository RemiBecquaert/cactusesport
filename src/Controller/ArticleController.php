<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;

use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use App\Entity\Article;

class ArticleController extends AbstractController
{
    #[Route('/news', name: 'news')]
    public function news(): Response
    {
        return $this->render('article/news.html.twig', []);
    }

    #[Route('/news-create', name: 'news-create')]
    public function newsCreate(Request $request, MailerInterface $mailer, ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);


        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $em = $doctrine->getManager();
                $emailArticle = (new TemplatedEmail())
                ->from('remi.becquaert35@gmail.com')
                ->to('esportcactus@outlook.fr')
                ->subject($article->getTitre())
                ->htmlTemplate('emails/emailArticle.html.twig')
                ->context([
                    'titre'=>$article->getTitre(),
                ]);
                $article->setDatePublication(new \Datetime());

                $em->persist($article);
                $em->flush();
                $mailer->send($emailArticle);

                $this->addFlash('notice','Article créé !');
            }
        }
        return $this->render('article/news-create.html.twig', ['form' => $form->createView()]);
    }
}
