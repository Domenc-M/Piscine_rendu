<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/", name="category_articles")
     */
    public function categoryList(CategoryRepository $repo)
    {

        $category = $repo->findAll();
        //On retourne une page qui loop toutes les catégories et les articles à l'intérieur.
        return $this->render('productscategory.html.twig', ["categories"=>$category]);
    }

    /**
     * @Route("/admin/category/insert", name="category_insert")
     */
    //Auto-wire du entity manager
    public function InsertCategory(EntityManagerInterface $manager,
                                  Request $request,
                                  EntityManagerInterface $entityManager)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $category= new Category();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        //On demande au formulaire de traiter les inputs récupérés par Request
        $categoryForm->handleRequest($request);

        //On vérifie si les inputs traités ont été confirmés et sont valide.
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            //Si c'est le cas, on stock ces inputs traités dans le nouvel objet
            $article = $categoryForm->getData();

            //Ensuite, on stock l'article en base de donnée via entityManager
            $entityManager->persist($article);
            $entityManager->flush();
        }


        //Page de confirmation de l'opération
        return $this->render('/admin/admin_category_create.html.twig', ["categoryForm"=>$categoryForm->createView()]);
    }

    /**
     * @Route("/admin/category/update/{id}", name="category_update")
     */
    //Auto-wire du entity manager et du repository
    public function UpdateArticle(Request $request,
                                  EntityManagerInterface $entityManager,
                                  CategoryRepository $repo,
                                  $id)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $category = $repo->find($id);
        if (is_null($category)) {
            return $this->render('error_message.html.twig', ["content" => "Aucune catégorie"]);
        }

        $categoryForm = $this->createForm(CategoryType::class, $category);

        //On demande au formulaire de traiter les inputs récupérés par Request
        $categoryForm->handleRequest($request);

        //On vérifie si les inputs traités ont été confirmés et sont valide.
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            //Si c'est le cas, on stock ces inputs traités dans le nouvel objet
            $article = $categoryForm->getData();

            //Ensuite, on stock l'article en base de donnée via entityManager
            $entityManager->persist($article);
            $entityManager->flush();
        }
        return $this->render('/admin/admin_category_update.html.twig', ["categoryForm"=>$categoryForm->createView()]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="category_delete")
     */
    //Auto-wire du entity manager et du repository
    public function DeleteArticle( $id, EntityManagerInterface $manager, CategoryRepository $repo)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $article= $repo->find($id);
        if (is_null($article))
        {
            return $this->render('error_message.html.twig', ["content"=>"Aucun objet trouvé"]);
        }

        $manager->remove($article);

        //Pas de persist puisque l'objet est deja enregistré (avec find)
        $manager->flush();

        $this->addFlash("success", "l'article a bien été supprimé");

        //Page de confirmation de l'opération
        return $this->redirectToRoute('admin_category_list');
    }
}