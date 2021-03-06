<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<=10; $i++) {
            $article= new Article();
            $article->SetTitle("Titre article $i")
                    ->SetContent("Contenue de l'article")
                    ->SetImage("http://placehold.it/350x150")
                    ->SetCreatedAt(new \DateTime());
            $manager->persist($article);
        }
        $manager->flush();

        $manager->flush();
    }
}
