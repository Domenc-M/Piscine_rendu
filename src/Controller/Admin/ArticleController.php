<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/admin/insertArticle", "insert_article")
     */
    //Auto-wire du entity manager
    public function InsertArticle(EntityManagerInterface $manager)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
    $article= new Article();

    //Puisque les setters sont fluent, on peut enchainer les methodes.
    $article->setTitle("titre")
        ->setContent("contenu très recherché")
        ->setImage("")
        ->setIsPublished(true)
        ->setCreatedAt(new \DateTime("NOW"));

    //Envoie de l'entité au manager
    $manager->persist($article);

    //Le manager envoie toutes les entités stockées en bdd
    $manager->flush();

    //Page de confirmation de l'opération
    return $this->render('insert_success.html.twig', ["title"=>$article->getTitle()]);
    }
}