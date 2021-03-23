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
     *  @Route("/admin/articles", name="admin_articles_list")
     */
    public function articlesList(ArticleRepository $repo) : Response
    {
        $articles = $repo->findAll();

        //Methode render de AbstractController pour retourner un fichier html
        //render prend un tableau qui associe les cles twig a leur variable respective
        return $this->render('adminproducts.html.twig', ["products"=>$articles]);
    }

    /**
     * @Route("/admin/article/insert", name="article_insert")
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
    return $this->render('error_message.html.twig', ["content"=>"l'article ".$article->getTitle()." a bien été ajouté"]);
    }

    /**
     * @Route("/admin/article/update/{id}", name="article_update")
     */
    //Auto-wire du entity manager et du repository
    public function UpdateArticle(EntityManagerInterface $manager, ArticleRepository $repo, $id)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $article= $repo->find($id);
        if (is_null($article))
        {
            return $this->render('error_message.html.twig', ["content"=>"Aucun objet trouvé"]);
        }

        //Puisque les setters sont fluent, on peut enchainer les methodes.
        $article->setContent("contenu encore plus recherché");

        //Pas de persist puisque l'objet est deja enregistré (avec find)
        $manager->flush();

        //Page de confirmation de l'opération
        return $this->render('error_message.html.twig', ["content"=>"L'article ".$article->getTitle()." a bien été modifié"]);
    }

    /**
     * @Route("/admin/article/delete/{id}", name="article_delete")
     */
    //Auto-wire du entity manager et du repository
    public function DeleteArticle( $id, EntityManagerInterface $manager, ArticleRepository $repo)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $article= $repo->find($id);
        if (is_null($article))
        {
            return $this->render('error_message.html.twig', ["content"=>"Aucun objet trouvé"]);
        }

        //Puisque les setters sont fluent, on peut enchainer les methodes.
        $manager->remove($article);

        //Pas de persist puisque l'objet est deja enregistré (avec find)
        $manager->flush();

        //Page de confirmation de l'opération
        return $this->render('error_message.html.twig', ["content"=>"L'article ".$article->getTitle()." a bien été supprimé"]);
    }
}