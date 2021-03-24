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

        if ($categoryName == "neuf" OR $categoryName == "occasion")
        {
            $category = $repo->findBy(["title"=>$categoryName]);
        }
        else
        {
            $category = $repo->findAll();
        }
        return $this->render('productscategory.html.twig', ["categories"=>$category]);
    }
}