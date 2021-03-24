<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/articles/{category}", name="category_articles")
     */
    public function categoryList($category, CategoryRepository $repo)
    {

        if ($category == "neuf" OR $category == "occasion")
        {
            $article = $repo->findBy(["category"=>$category]);

        }
        else
        {
            $article = $repo->findAll();
        }
        return $this->render('productscategory.html.twig', ["products"=>$article]);
    }
}