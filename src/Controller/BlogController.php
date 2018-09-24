<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        //INJECTION DEP $repo= $this->getDoctrine()->getRepository(Article::class);

        //$article=$repo->find(12);
        //$article=$repo->findOneByTitle('Titre de l\'article);
        //$articles=$repo->findByTitle('Titre de l\'article);
        $articles=$repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('blog/home.html.twig',[
            "titre" => "Titre",
            "age" => 31
        ]);
    }

    
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {

        if (!$article) {
            $article= new Article();
        }

        /*$form = $this->CreateFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm();
        */

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        dump($article); //Affiche le resultat JSON du request soumis
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show',['id' => $article->getId()]);


        }

        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article) {
        //INJECT DEP $repo= $this->getDoctrine()->getRepository(Article::class);
        //show(ArticleRepository $repo, $id) {
        //PLUS BESOIN car INJECT DEP
        //$article=$repo->find($id);
        return $this->render('blog/show.html.twig',[
            'article' => $article
        ]);
    }

}
