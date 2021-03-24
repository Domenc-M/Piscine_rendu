<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/articles/{categoryName}", name="category_articles")
     */
    public function categoryList($categoryName, CategoryRepository $repo)
    {
        //On récupère la wildcard et on la compare aux catégories.
        //Si la wildcard n'est pas valide, on récupère tout.
        //Si elle est valide, on récupère la catégorie associée.
        if ($categoryName == "neuf" OR $categoryName == "occasion")
        {
            $category = $repo->findBy(["title"=>$categoryName]);
        }
        else
        {
            $category = $repo->findAll();
        }
        //On retourne une page qui loop toutes les catégories et les articles à l'intérieur.
        return $this->render('productscategory.html.twig', ["categories"=>$category]);
    }
}