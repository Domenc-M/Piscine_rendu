<?php

//namespace = identifie cette classe au sein du projet
// (pour éviter les conflits en cas d'autres classes dans d'autres fichiers)
//On utilise le chemin du  fichier pour le namespace (app = src)
//Indispensable pour utiliser la classe.
namespace App\Controller;

//On precise le namespace utilise par Route.
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Nom de classe doit toujours correspondre au nom de fichier.
class ArticleController extends AbstractController
{

    //ACCUEIL AVEC 2 ARTICLES RECENTS ET PUBLIES
    /**
     *  @Route("/homepage", name="article_homepage")
     */
    public function homePage(ArticleRepository $repo)
    {
        $article = $repo->findBy(["isPublished"=>true], ["createdAt" => "DESC"]);
        //On retourne la page Produit avec l'id envoyée, et en utilisant le nom du produit comme titre.
        return $this->render('producthomepage.html.twig', ["title"=>"Accueil", "products"=>$article]);
        //render prend un tableau qui associe les cles twig a leur variable respective
    }
    //LISTE DES ARTICLES
    /**
     *  @Route("/articles", name="articles_list")
     */
    public function articlesList(ArticleRepository $repo) : Response
    {

        $title = "Tout mes produits";

        $articles = $repo->findAll();

        //Methode render de AbstractController pour retourner un fichier html
        //render prend un tableau qui associe les cles twig a leur variable respective
        return $this->render('products.html.twig', ["title"=>$title, "products"=>$articles]);
    }

    //INFO SUR UN SEUL ARTICLE
    /**
     *  @Route("/article/{id}", name="article_info")
     */
    public function articleInfo($id, ArticleRepository $repo)
    {
        $article = $repo->findOneBy(["id"=>$id]);
        //$title = $article[0]->getTitle();
        //dump($title);
        //dump($article); die;
        //On retourne la page Produit avec l'id envoyée, et en utilisant le nom du produit comme titre.
        return $this->render('productinfo.html.twig', ["title"=>$article->getTitle(), "product"=>$article]);
        //render prend un tableau qui associe les cles twig a leur variable respective
    }

}