<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
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
    public function InsertArticle(EntityManagerInterface $manager,
                                  Request $request,
                                  EntityManagerInterface $entityManager)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
    $article= new Article();

    $articleForm = $this->createForm(ArticleType::class, $article);

    //On demande au formulaire de traiter les inputs récupérés par Request
    $articleForm->handleRequest($request);

    //On vérifie si les inputs traités ont été confirmés et sont valide.
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        //Si c'est le cas, on stock ces inputs traités dans le nouvel objet
        $article = $articleForm->getData();

        //Ensuite, on stock l'article en base de donnée via entityManager
        $entityManager->persist($article);
        $entityManager->flush();
    }


    //Page de confirmation de l'opération
    return $this->render('admin_article_create.html.twig', ["articleForm"=>$articleForm->createView()]);
    }

    /**
     * @Route("/admin/article/update/{id}", name="article_update")
     */
    //Auto-wire du entity manager et du repository
    public function UpdateArticle(Request $request,
                                  EntityManagerInterface $entityManager,
                                  ArticleRepository $repo,
                                  $id)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $article = $repo->find($id);
        if (is_null($article)) {
            return $this->render('error_message.html.twig', ["content" => "Aucun objet trouvé"]);
        }

        $articleForm = $this->createForm(ArticleType::class, $article);

        //On demande au formulaire de traiter les inputs récupérés par Request
        $articleForm->handleRequest($request);

        //On vérifie si les inputs traités ont été confirmés et sont valide.
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            //Si c'est le cas, on stock ces inputs traités dans le nouvel objet
            $article = $articleForm->getData();

            //Ensuite, on stock l'article en base de donnée via entityManager
            $entityManager->persist($article);
            $entityManager->flush();
        }
        return $this->render('admin_article_create.html.twig', ["articleForm"=>$articleForm->createView()]);
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

        $manager->remove($article);

        //Pas de persist puisque l'objet est deja enregistré (avec find)
        $manager->flush();

        $this->addFlash("success", "l'article a bien été supprimé");

        //Page de confirmation de l'opération
        return $this->redirectToRoute('admin_articles_list');
    }
}